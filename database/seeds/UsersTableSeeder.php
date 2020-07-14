<?php

use Illuminate\Database\Seeder;
use App\Models\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = factory(User::class)->times(6)->make();
        $users_array = $users->makeVisible(['password', 'remember_token'])->toArray();
        User::insert($users_array);
        $bannedUser = User::find(6);
        $bannedUser->user_blacklisted = true;
        $bannedUser->save();
    }
}
