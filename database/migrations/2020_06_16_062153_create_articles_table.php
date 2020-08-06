<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateArticlesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('articles', function (Blueprint $table) {
            $table->increments('id');
            $table->string('article_title');
            $table->unsignedInteger('article_category_id');
            $table->unsignedInteger('article_type_id');
            $table->unsignedInteger('author_id');
            $table->string('article_source', 50)->default('未知');
            $table->string('article_source_link')->nullable();
            $table->string('article_description');
            $table->mediumText('article_content');
            $table->string('article_thumb')->nullable();
            $table->boolean('article_enabled')->default(true);
            $table->boolean('likes_enabled')->default(true);
            $table->boolean('dislikes_enabled')->default(true);
            $table->boolean('comments_enabled')->default(true);
            $table->unsignedInteger('article_priority')->default(0);
            $table->string('article_seo_title')->nullable();
            $table->string('article_seo_key')->nullable();
            $table->string('article_seo_des')->nullable();
            $table->timestamps();
            $table->timestamp('deleted_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('articles');
    }
}
