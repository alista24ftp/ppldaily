<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UsersTableSeeder::class);
        $this->call(RolesAndPermissionsSeeder::class);
        $this->call(ArticleAttributesSeeder::class);
        $this->call(ArticleTypesSeeder::class);
        $this->call(ArticleCategoriesSeeder::class);
        $this->call(ArticlesSeeder::class);
    }
}
