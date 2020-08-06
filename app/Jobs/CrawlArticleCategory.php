<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Infrastructure\CrawlHandler;
use App\Jobs\CrawlArticle;

class CrawlArticleCategory implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $crawlHandler;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(CrawlHandler $crawlHandler)
    {
        $this->crawlHandler = $crawlHandler;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $articleList = $this->crawlHandler->crawlArticleList();
        if(!empty($articleList)){
            foreach($articleList as $articleItem){
                $articleInfo = $this->crawlHandler->retrieveArticleInfo($articleItem);
                CrawlArticle::dispatchIf(!empty($articleInfo), $this->crawlHandler, $articleInfo);
            }
        }
    }
}
