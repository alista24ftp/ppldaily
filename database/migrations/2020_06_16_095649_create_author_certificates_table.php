<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use App\Infrastructure\FilesystemHandler;
use App\Jobs\DeleteFiles;

class CreateAuthorCertificatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('author_certificates', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->string('certificate_path');
            $table->string('certificate_description')->nullable();
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $certificates = DB::table('author_certificates')
            ->select('certificate_path')->get()->pluck('certificate_path')->all();
        $certificates = array_filter($certificates, function($cert){
            return !empty($cert);
        });
        DeleteFiles::dispatchIf(count($certificates) > 0, new FilesystemHandler, $certificates, 'img');
        Schema::dropIfExists('author_certificates');
    }
}
