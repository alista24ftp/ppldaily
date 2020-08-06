<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\SiteInfo;
use App\Infrastructure\FilesystemHandler;
use App\Jobs\DeleteFiles;

class CreateSiteInfoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('site_info', function (Blueprint $table) {
            $table->increments('id');
            $table->string('site_name');
            $table->string('site_host');
            $table->string('site_icp');
            $table->string('site_copyright');
            $table->string('site_addr');
            $table->string('site_logo')->nullable();
            $table->string('site_company_name');
            $table->string('site_seo_title')->nullable();
            $table->string('site_seo_key')->nullable();
            $table->string('site_seo_des')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $logos = SiteInfo::pluck('site_logo')->all();
        $logos = array_filter($logos, function($logo){
            return !empty($logo);
        });
        DeleteFiles::dispatchIf(count($logos) > 0, new FilesystemHandler, $logos, 'img');
        Schema::dropIfExists('site_info');
    }
}
