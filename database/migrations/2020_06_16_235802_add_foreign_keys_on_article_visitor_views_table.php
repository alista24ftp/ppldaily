<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysOnArticleVisitorViewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('article_visitor_views', function (Blueprint $table) {
            $table->foreign('article_id')->references('id')->on('articles')->onDelete('cascade');
            $table->foreign('visitor_ip')->references('visitor_ip')->on('visitor_ips')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('article_visitor_views', function (Blueprint $table) {
            $table->dropForeign(['article_id']);
            $table->dropForeign(['visitor_ip']);
        });
    }
}
