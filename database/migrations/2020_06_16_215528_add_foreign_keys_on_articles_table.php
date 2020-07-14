<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysOnArticlesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('articles', function (Blueprint $table) {
            $table->foreign('article_category_id')->references('id')->on('article_categories')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('article_type_id')->references('id')->on('article_types')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('author_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('articles', function (Blueprint $table) {
            $table->dropForeign(['article_category_id']);
            $table->dropForeign(['article_type_id']);
            $table->dropForeign(['author_id']);
        });
    }
}
