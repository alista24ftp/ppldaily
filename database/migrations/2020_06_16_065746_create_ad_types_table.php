<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ad_types', function (Blueprint $table) {
            $table->increments('id');
            $table->string('ad_type_description')->unique();
            $table->unsignedInteger('ad_type_priority')->default(0);
            $table->boolean('ad_type_enabled')->default(true);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ad_types');
    }
}
