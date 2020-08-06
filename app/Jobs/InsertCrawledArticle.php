<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Infrastructure\CrawlHandler;

class InsertCrawledArticle implements ShouldQueue
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
        $this->crawlHandler->insertArticle($this->articleInfo);
    }
}
