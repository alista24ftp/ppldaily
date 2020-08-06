<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\User;
use App\Infrastructure\FilesystemHandler;
use App\Jobs\DeleteFiles;

class AddColumnsToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('first_name', 50)->nullable();
            $table->string('last_name', 50)->nullable();
            $table->string('profile_pic')->nullable();
            $table->text('about')->nullable();
            $table->timestamp('last_active_time')->nullable();
            $table->boolean('user_blacklisted')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $profile_pics = User::pluck('profile_pic')->all();
        $profile_pics = array_filter($profile_pics, function($pic){
            return !empty($pic);
        });
        DeleteFiles::dispatchIf(count($profile_pics) > 0, new FilesystemHandler, $profile_pics, 'img');
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('first_name');
            $table->dropColumn('last_name');
            $table->dropColumn('profile_pic');
            $table->dropColumn('about');
            $table->dropColumn('last_active_time');
            $table->dropColumn('user_blacklisted');
        });
    }
}
