<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateArticleCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('article_categories', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('category_parent_id')->nullable();
            $table->string('category_name', 30);
            $table->string('category_thumb')->nullable();
            $table->boolean('category_enabled')->default(true);
            $table->unsignedInteger('category_priority')->default(0);
            $table->string('category_seo_title')->nullable();
            $table->string('category_seo_key')->nullable();
            $table->string('category_seo_des')->nullable();
            $table->timestamps();

            $table->foreign('category_parent_id')->references('id')->on('article_categories')
                ->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('article_categories');
    }
}
