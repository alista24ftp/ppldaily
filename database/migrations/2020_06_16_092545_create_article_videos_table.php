<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\ArticleVideo;
use App\Infrastructure\FilesystemHandler;
use App\Jobs\DeleteFiles;

class CreateArticleVideosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('article_videos', function (Blueprint $table) {
            $table->increments('id');
            $table->string('article_video_path');
            $table->unsignedInteger('article_id');
            $table->foreign('article_id')->references('id')->on('articles')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $videos = ArticleVideo::pluck('article_video_path')->all();
        $videos = array_filter($videos, function($video){
            return !empty($video);
        });
        DeleteFiles::dispatchIf(count($videos) > 0, new FilesystemHandler, $videos, 'video');
        Schema::dropIfExists('article_videos');
    }
}
