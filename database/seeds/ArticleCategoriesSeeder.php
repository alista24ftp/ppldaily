<?php

use Illuminate\Database\Seeder;
use App\Models\ArticleCategory;

class ArticleCategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = [
            [
                'category_name'=>'新闻',
                'category_priority'=>1000,
                'children'=>[
                    ['category_name'=>'时政'],
                    ['category_name'=>'国际'],
                    ['category_name'=>'军事'],
                    ['category_name'=>'专题'],
                    ['category_name'=>'公益'],
                    ['category_name'=>'警法'],
                ]
            ],
            [
                'category_name'=>'体育',
                'children'=>[
                    ['category_name'=>'电竞'],
                    ['category_name'=>'产业'],
                    ['category_name'=>'高尔夫'],
                    ['category_name'=>'乒乓球'],
                    ['category_name'=>'帆船'],
                    ['category_name'=>'排球'],
                    ['category_name'=>'棋牌'],
                    ['category_name'=>'跑步'],
                    ['category_name'=>'拳击搏击'],
                    ['category_name'=>'网球'],
                    ['category_name'=>'羽毛球'],
                    ['category_name'=>'台球'],
                    ['category_name'=>'游泳'],
                    ['category_name'=>'自行车'],
                    ['category_name'=>'国际足球'],
                ]
            ],
            [
                'category_name'=>'旅游',
                'children'=>[
                    ['category_name'=>'国内'],
                    ['category_name'=>'境外'],
                    ['category_name'=>'攻略'],
                    ['category_name'=>'签证'],
                ]
            ],
            [
                'category_name'=>'教育',
                'children'=>[
                    ['category_name'=>'高考'],
                    ['category_name'=>'中小学'],
                    ['category_name'=>'高校'],
                    ['category_name'=>'留学'],
                    ['category_name'=>'考试'],
                ]
            ],
            [
                'category_name'=>'时尚',
                'children'=>[
                    ['category_name'=>'时装'],
                    ['category_name'=>'奢品'],
                    ['category_name'=>'美容'],
                    ['category_name'=>'人物'],
                    ['category_name'=>'男士'],
                    ['category_name'=>'生活方式'],
                ]
            ],
            [
                'category_name'=>'娱乐',
                'children'=>[
                    ['category_name'=>'明星八卦'],
                    ['category_name'=>'电视剧'],
                    ['category_name'=>'电影'],
                    ['category_name'=>'综艺'],
                ]
            ],
            [
                'category_name'=>'科技',
                'children'=>[
                    ['category_name'=>'互联网'],
                    ['category_name'=>'通讯'],
                    ['category_name'=>'智能硬件'],
                    ['category_name'=>'生活服务'],
                    ['category_name'=>'创业投资'],
                    ['category_name'=>'科学'],
                    ['category_name'=>'数码'],
                ]
            ],
            [
                'category_name'=>'文化',
                'children'=>[
                    ['category_name'=>'人物'],
                    ['category_name'=>'读书'],
                    ['category_name'=>'艺术'],
                    ['category_name'=>'原创文学'],
                    ['category_name'=>'地方文化'],
                    ['category_name'=>'影视'],
                    ['category_name'=>'收藏'],
                ]
            ],
        ];

        foreach($categories as $category){
            $parentCategory = ArticleCategory::create(
                array_intersect_key($category, ['category_name'=>'', 'category_priority'=>''])
            );
            $subcategories = array_map(function($subcat){return new ArticleCategory($subcat);}, $category['children']);
            $parentCategory->subCategories()->saveMany($subcategories);
        }
    }
}
