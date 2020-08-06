<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateArticleCategoriesCrawlMappings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('article_categories_crawl_mappings', function (Blueprint $table) {
            $table->unsignedInteger('id');
            $table->string('target_host');
            $table->string('url');
            $table->timestamp('last_crawled_at')->nullable();
            $table->json('mapping');
            $table->foreign('id')->references('id')->on('article_categories')->onDelete('cascade');
            $table->primary('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('article_categories_crawl_mappings');
    }
}
