<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use App\Infrastructure\FilesystemHandler;
use App\Jobs\DeleteFiles;

class CreateAdsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ads', function (Blueprint $table) {
            $table->increments('id');
            $table->string('ad_title');
            $table->string('ad_description');
            $table->unsignedInteger('ad_type_id');
            $table->string('ad_pic');
            $table->string('ad_link')->nullable();
            $table->unsignedInteger('ad_priority')->default(0);
            $table->boolean('ad_enabled')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $ad_pics = DB::table('ads')->select('ad_pic')->get()->pluck('ad_pic')->all();
        $ad_pics = array_filter($ad_pics, function($pic){
            return !empty($pic);
        });
        DeleteFiles::dispatchIf(count($ad_pics) > 0, new FilesystemHandler, $ad_pics, 'img');
        Schema::dropIfExists('ads');
    }
}
