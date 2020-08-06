<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ArticleTagsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $rand_articles = DB::table('articles')->orderByRaw("RAND()")->limit(50)->get()->toArray();
        $attributes = DB::table('article_attributes')->get();
        $headlineAttr = $attributes->where('article_attr_description', 'headline')->first();
        $sliderAttr = $attributes->where('article_attr_description', 'slider')->first();
        $imgAttr = $attributes->where('article_attr_description', 'img')->first();
        $sideAttr = $attributes->where('article_attr_description', 'side')->first();
        $tags = [];
        foreach($rand_articles as $k => $article){
            if($k == 0){
                array_push($tags, [
                    'article_id' => $article->id,
                    'article_attr_id' => $headlineAttr->id,
                ]);
            }else if($k < 12){
                array_push($tags, [
                    'article_id' => $article->id,
                    'article_attr_id' => $sliderAttr->id,
                ]);
            }else if($k < 24){
                array_push($tags, [
                    'article_id' => $article->id,
                    'article_attr_id' => $imgAttr->id,
                ]);
            }else if($k < 36){
                array_push($tags, [
                    'article_id' => $article->id,
                    'article_attr_id' => $sideAttr->id,
                ]);
            }else if($k < 43){
                array_push($tags, [
                    'article_id' => $article->id,
                    'article_attr_id' => $sideAttr->id,
                ], [
                    'article_id' => $article->id,
                    'article_attr_id' => $sliderAttr->id,
                ]);
            }else{
                array_push($tags, [
                    'article_id' => $article->id,
                    'article_attr_id' => $sideAttr->id,
                ], [
                    'article_id' => $article->id,
                    'article_attr_id' => $imgAttr->id,
                ]);
            }
        }
        DB::table('article_tags')->insert($tags);
    }
}
