<?php

use Illuminate\Database\Seeder;
use App\Models\ArticleType;

class ArticleTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ArticleType::insert([
            ['article_type_description'=>'新闻'],
            ['article_type_description'=>'图片'],
            ['article_type_description'=>'视频'],
        ]);
    }
}
