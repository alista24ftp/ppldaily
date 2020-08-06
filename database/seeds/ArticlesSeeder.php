<?php

use Illuminate\Database\Seeder;
use App\Infrastructure\CrawlHandler;
use App\Infrastructure\ImageHandler;
use App\Models\User;
use App\Models\ArticleType;
use App\Models\ArticleCategoryCrawlMapping;
use App\Jobs\CrawlArticleCategory;

class ArticlesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $crawlMappings = ArticleCategoryCrawlMapping::all();
        foreach($crawlMappings as $crawlMapping){
            $crawlHandler = new CrawlHandler(
                $crawlMapping,
                new ImageHandler,
                User::role('Admin')->first(),
                ArticleType::where('article_type_description', '新闻')->first()
            );
            CrawlArticleCategory::dispatch($crawlHandler);
        }
    }
}
