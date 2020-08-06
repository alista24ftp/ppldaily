<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ArticleAttributesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('article_attributes')->insert([
            [
                'article_attr_description' => 'headline',
                'article_attr_priority' => 1000,
            ],
            [
                'article_attr_description' => 'slider',
                'article_attr_priority' => 500,
            ],
            [
                'article_attr_description' => 'img',
                'article_attr_priority' => 100,
            ],
            [
                'article_attr_description' => 'video',
                'article_attr_priority' => 99,
            ],
            [
                'article_attr_description' => 'side',
                'article_attr_priority' => 50,
            ],
        ]);
    }
}
