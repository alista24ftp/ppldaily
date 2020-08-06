<?php
namespace App\Infrastructure;

use Illuminate\Support\Facades\Http;
use App\Models\ArticleCategoryCrawlMapping;
use App\Models\ArticleCategory;
use App\Models\Article;
use App\Models\ArticleImage;
use App\Models\ArticleKeyword;
use App\Models\ArticleType;
use App\Models\User;

class CrawlHandler
{
    private $crawlMapping;
    private $imgHandler;
    private $user;
    private $articleType;

    public function __construct(ArticleCategoryCrawlMapping $crawlMapping, ImageHandler $imgHandler, User $user, ArticleType $articleType)
    {
        $this->crawlMapping = $crawlMapping->toArray();
        $this->imgHandler = $imgHandler;
        $this->user = $user; //User::role('Admin')->first();
        $this->articleType = $articleType;
    }

    public function crawl()
    {
        try{
            // LONG RUNNING PROCESS
            // crawl url and retrieve relevant info based on the crawl mapping
            $response = Http::get($this->crawlMapping['url']);
            if(!$response->successful()) return null;
            $articleListJson = json_decode((string)$response->getBody(), true);
            $articleList = $this->getRelevantInfoByKey($articleListJson, $this->crawlMapping['mapping']['list_key']);
            foreach($articleList as $articleItem){
                // retrieve relevant article info
                $item = $this->getRelevantInfoByKey($articleItem, $this->crawlMapping['mapping']['item_key']);
                if(empty($item)) continue;
                $title = $this->getRelevantInfoByKey($item, $this->crawlMapping['mapping']['article_title']);
                $publishTime = $this->getRelevantInfoByKey($item, $this->crawlMapping['mapping']['article_published_at']);
                if(is_null($title) || empty($title = trim($title)) || empty($publishTime)) continue;
                $source = $this->getRelevantInfoByKey($item, $this->crawlMapping['mapping']['article_source']);
                $source_link = empty($this->crawlMapping['mapping']['article_source_link'])
                    ? null : $this->getRelevantInfoByKey($item, $this->crawlMapping['mapping']['article_source_link']);
                $description = empty($this->crawlMapping['mapping']['article_description'])
                    ? null : $this->getRelevantInfoByKey($item, $this->crawlMapping['mapping']['article_description']);

                // LONG RUNNING PROCESS
                // crawl article page and fetch article content
                $article_url = $this->getArticleUrl($item);
                $article_content = $this->crawlArticlePage($article_url);
                if(empty($article_content)) continue;

                // LONG RUNNING PROCESS
                // download original images and replace image tags with updated src
                $after_imgs_replaced = $this->replaceImgs($article_content, $this->crawlMapping['target_host']);
                $article_content = $after_imgs_replaced['content'];
                $new_imgs = $after_imgs_replaced['imgs'];

                // LONG RUNNING PROCESS
                // get article thumbnail, if any
                $thumbnail = empty($this->crawlMapping['mapping']['article_thumb'])
                    ? null : $this->getRelevantInfoByKey($item, $this->crawlMapping['mapping']['article_thumb']);
                if(!empty($thumbnail)){
                    $thumbnail = $this->imgHandler->download(
                        $thumbnail,
                        $this->crawlMapping['target_host'], 'downloads/images/' . date("Ym/d", time()))['path'];
                }
                if(!empty($thumbnail)) $new_imgs[] = $thumbnail;

                // get article keywords, if any
                $keywords = $this->getArticleKeywords($item);

                // LONG RUNNING PROCESS
                // add article to database
                $article = new Article([
                    'article_title' => $title,
                    'article_category_id' => $this->crawlMapping['id'],
                    'article_type_id' => $this->articleType->id,
                    'author_id' => $this->user->id,
                    'article_source' => $source,
                    'article_source_link' => $source_link,
                    'article_description' => empty($description) ? $this->generateDescription($article_content) : $description,
                    'article_content' => $article_content,
                    'article_thumb' => $thumbnail,
                ]);
                $article->save();

                // LONG RUNNING PROCESS
                // add article images to database
                if(!empty($new_imgs)){
                    $article->images()->createMany(array_map(function($img){
                        return ['article_image_path' => $img];
                    }, $new_imgs));
                }

                // LONG RUNNING PROCESS
                // add article keywords to database
                if(!empty($keywords)){
                    $article->keywords()->createMany(array_map(function($keyword){
                        return ['article_keyword' => $keyword];
                    }, $keywords));
                }
            }
        }catch(\Exception $e){
            return null;
        }
    }

    public function crawlArticleList()
    {
        try{
            $response = Http::get($this->crawlMapping['url']);
            if(!$response->successful()) return null;
            $articleListJson = json_decode((string)$response->getBody(), true);
            $articleList = $this->getRelevantInfoByKey($articleListJson, $this->crawlMapping['mapping']['list_key']);
            return $articleList;
        }catch(\Exception $e){
            return null;
        }
    }

    public function retrieveArticleInfo($articleItem)
    {
        // retrieve relevant article info
        $item = $this->getRelevantInfoByKey($articleItem, $this->crawlMapping['mapping']['item_key']);
        if(empty($item)) return null;
        $title = $this->getRelevantInfoByKey($item, $this->crawlMapping['mapping']['article_title']);
        $publishTime = $this->getRelevantInfoByKey($item, $this->crawlMapping['mapping']['article_published_at']);
        if(is_null($title) || empty($title = trim($title)) || empty($publishTime)) return null;
        $source = $this->getRelevantInfoByKey($item, $this->crawlMapping['mapping']['article_source']);
        $source_link = empty($this->crawlMapping['mapping']['article_source_link'])
            ? null
            : $this->transformLink($this->getRelevantInfoByKey($item, $this->crawlMapping['mapping']['article_source_link']), $this->crawlMapping['target_host']);
        $description = empty($this->crawlMapping['mapping']['article_description'])
            ? null : $this->getRelevantInfoByKey($item, $this->crawlMapping['mapping']['article_description']);
        return compact('item', 'title', 'publishTime', 'source', 'source_link', 'description');
    }

    public function retrieveArticleContent($json)
    {
        $article_url = $this->getArticleUrl($json);
        return $this->crawlArticlePage($article_url);
    }

    public function retrieveArticleThumbnail($json)
    {
        if(empty($this->crawlMapping['mapping']['article_thumb'])) return null;
        $thumbnail = $this->getRelevantInfoByKey($json, $this->crawlMapping['mapping']['article_thumb']);
        if(empty($thumbnail)) return null;
        return $this->imgHandler->download($thumbnail, $this->crawlMapping['target_host'],
            'downloads/images/' . date("Ym/d", time()));
    }

    public function crawlArticlePage($url)
    {
        try{
            $response = Http::get($url);
            if(!$response->successful()) return null;
            $articleHtml = (string) $response->getBody();
            $articleHtml = $this->sanitizeHtml($articleHtml);
            $articleContent = $this->getContentByRegex($this->crawlMapping['mapping']['article_content'], $articleHtml);
            return $articleContent;
        }catch(\Exception $e){
            return null;
        }
    }

    public function downloadAndReplaceImgs($article_content)
    {
        return $this->replaceImgs($article_content, $this->crawlMapping['target_host']);
    }

    public function insertArticle($articleInfo)
    {
        $keywords = $this->getArticleKeywords($articleInfo['item']);
        $description = $articleInfo['description'];
        if(empty($description)){
            $description = $this->generateDescription($articleInfo['article_content']);
        }

        $article = new Article([
            'article_title' => $articleInfo['title'],
            'article_category_id' => $this->crawlMapping['id'],
            'article_type_id' => $this->articleType->id,
            'author_id' => $this->user->id,
            'article_source' => $articleInfo['source'],
            'article_source_link' => $articleInfo['source_link'],
            'article_description' => $description,
            'article_content' => $articleInfo['article_content'],
            'article_thumb' => $articleInfo['article_thumb'],
        ]);
        $article->save();

        if(!empty($articleInfo['article_imgs'])){
            $article->images()->createMany(array_map(function($img){
                return ['article_image_path' => $img];
            }, $articleInfo['article_imgs']));
        }

        if(!empty($keywords)){
            $article->keywords()->createMany(array_map(function($keyword){
                return ['article_keyword' => $keyword];
            }, $keywords));
        }
    }

    private function getRelevantInfoByKey($json, $key)
    {
        if(empty($key)) return $json;
        $info = $json;
        $nested_keys = explode('/', $key);
        foreach($nested_keys as $level_key){
            if(!array_key_exists($level_key, $info)) return null;
            $info = $info[$level_key];
        }
        return $info;
    }

    private function transformLink($link, $host)
    {
        if(empty($link)) return '';
        $new_link = trim($link);
        if(empty($new_link) || strlen($new_link) < 3) return "";
        if(strtolower(substr($new_link, 0, 4)) == "http") return $new_link;
        if($new_link[0] == '/' && $new_link[1] == '/') return "http:$new_link";
        if($new_link[0] == '/') return $host . $new_link;
        return "$host/$new_link";
    }

    private function getArticleKeywords($json)
    {
        if(empty($this->crawlMapping['mapping']['tags_key'])) return null;
        $article_keywords = array();
        $keywords = $this->getRelevantInfoByKey($json, $this->crawlMapping['mapping']['tags_key']);
        if(empty($keywords)) return null;
        foreach($keywords as $k => $keyword){
            $keywordItem = $this->getRelevantInfoByKey($keyword, $this->crawlMapping['mapping']['tag_key']);
            if(empty($keywordItem)) continue;
            $actual_keyword = $this->getRelevantInfoByKey($keywordItem, $this->crawlMapping['mapping']['tag']);
            if(empty($actual_keyword)) continue;
            $article_keywords[] = $actual_keyword;
        }
        return $article_keywords;
    }

    private function getArticleId($item)
    {
        $id = '';
        foreach($this->crawlMapping['mapping']['article_id'] as $i => $token){
            if($i % 2 == 0){
                $idPart = $this->getRelevantInfoByKey($item, $token);
                if(is_null($idPart)) return null;
                $id .= $idPart;
            }
            else $id .= $token;
        }
        return $id;
    }

    private function getArticleUrl($item)
    {
        $article_url = null;
        if(!empty($this->crawlMapping['mapping']['article_url'])){
            $article_url = $this->transformLink(
                $this->getRelevantInfoByKey($item, $this->crawlMapping['mapping']['article_url']),
                $this->crawlMapping['target_host']
            );
        }else{
            $article_id = $this->getArticleId($item);
            $article_url = empty($article_id) ?
                null : $this->crawlMapping['mapping']['article_base_url'] . $article_id;
        }
        return $article_url;
    }

    private function getContentByRegex($pattern, $html)
    {
        preg_match($pattern, $html, $res);
        return empty($res) ? null : trim($res[1]);
    }

    private function sanitizeHtml($html)
    {
        $html = preg_replace("/[\t\n\r]+/", "", $html);
        $html = preg_replace("/<script.*?<\/script>/", "", $html);
        $html = preg_replace("/<iframe.*?<\/iframe>/", "", $html);
        $html = preg_replace("/<a .*?<\/a>/", "", $html);
        $html = preg_replace("/<style.*?<\/style>/", "", $html);
        //$html = preg_replace("/style=\".*?\">/", ">", $html);//
        $html = preg_replace("/<html.*?>/", "", $html);
        $html = preg_replace("/<\/html.*?>/", "", $html);
        $html = preg_replace("/<body.*?>/", "", $html);
        $html = preg_replace("/<\/body.*?>/", "", $html);
        $html = preg_replace("/<\/img>/", "", $html);

        return trim($html);
    }

    private function replaceImgs($content, $remoteHost)
    {
        $res = ['imgs'=>[]];
        $content = preg_replace_callback('/<img.*?src=\"(.*?)\".*?>/', function($matches) use ($remoteHost, &$res) {
            if(!isset($matches[1]) || empty($matches[1]))
                return '';
            $imgUrl = $matches[1];

            $newImgUrl = $this->imgHandler->download($imgUrl, $remoteHost, 'downloads/images/' . date("Ym/d", time()));
            if(empty($newImgUrl)) return '';
            $res['imgs'][] = $newImgUrl['path'];
            return '<img src="'.$newImgUrl['path'].'" />';
        }, $content);
        $res['content'] = $content;
        return $res;
    }

    private function generateDescription($content)
    {
        $description = preg_replace("/(\s|\&nbsp\;|　|\xc2\xa0)/", "", strip_tags($content));
        $description = mb_substr($description,0,50,'utf8')."...";
        return trim($description);
    }
}
