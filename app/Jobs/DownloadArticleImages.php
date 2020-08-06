<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Infrastructure\CrawlHandler;
use App\Jobs\InsertCrawledArticle;

class DownloadArticleImages implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $crawlHandler;
    protected $articleInfo;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(CrawlHandler $crawlHandler, $articleInfo)
    {
        $this->crawlHandler = $crawlHandler;
        $this->articleInfo = $articleInfo;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $after_imgs_replaced = $this->crawlHandler->downloadAndReplaceImgs($this->articleInfo['article_content']);
        $this->articleInfo['article_content'] = $after_imgs_replaced['content'];
        $this->articleInfo['article_imgs'] = $after_imgs_replaced['imgs'];
        $thumbnail = $this->crawlHandler->retrieveArticleThumbnail($this->articleInfo['item']);
        if(!empty($thumbnail)){
            $this->articleInfo['article_thumb'] = $thumbnail['path'];
            $this->articleInfo['article_imgs'][] = $thumbnail['path'];
        }else{
            $this->articleInfo['article_thumb'] = null;
        }
        InsertCrawledArticle::dispatch($this->crawlHandler, $this->articleInfo);
    }
}
