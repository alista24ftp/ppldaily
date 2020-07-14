<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateArticleVisitorViewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('article_visitor_views', function (Blueprint $table) {
            $table->unsignedInteger('article_id');
            $table->ipAddress('visitor_ip');
            $table->timestamps();
            $table->primary(['article_id', 'visitor_ip']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('article_visitor_views');
    }
}
