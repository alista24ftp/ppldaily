<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateArticleTagsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('article_tags', function (Blueprint $table) {
            $table->unsignedInteger('article_id');
            $table->unsignedInteger('article_attr_id');
            $table->foreign('article_id')->references('id')->on('articles')->onDelete('cascade');
            $table->foreign('article_attr_id')->references('id')->on('article_attributes')->onDelete('cascade');
            $table->primary(['article_id', 'article_attr_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('article_tags');
    }
}
