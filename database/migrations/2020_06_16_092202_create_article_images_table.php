<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\ArticleImage;
use App\Infrastructure\FilesystemHandler;
use App\Jobs\DeleteFiles;

class CreateArticleImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('article_images', function (Blueprint $table) {
            $table->increments('id');
            $table->string('article_image_path');
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
        $imgs = ArticleImage::pluck('article_image_path')->all();
        $imgs = array_filter($imgs, function($img){
            return !empty($img);
        });
        DeleteFiles::dispatchIf(count($imgs) > 0, new FilesystemHandler, $imgs, 'img');
        Schema::dropIfExists('article_images');
    }
}
