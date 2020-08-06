<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Infrastructure\FilesystemHandler;

class DeleteFiles implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $filesystemHandler;
    protected $files;
    protected $fileType;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(FileSystemHandler $filesystemHandler, $files, $fileType)
    {
        $this->filesystemHandler = $filesystemHandler;
        $this->files = $files;
        $this->fileType = $fileType;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $res = ['removed'=>[], 'failed'=>[]];
        foreach($this->files as $file){
            $this->filesystemHandler->removeFile(
                $file,
                $this->filesystemHandler->fileCriteria($this->fileType),
                $res
            );
        }
    }
}
