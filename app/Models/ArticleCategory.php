<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\NestedTree;

class ArticleCategory extends Model
{
    use NestedTree;

    protected $table = 'article_categories';

    protected $fillable = [
        'category_parent_id', 'category_name', 'category_thumb', 'category_enabled', 'category_priority',
        'category_seo_title', 'category_seo_key', 'category_seo_des',
    ];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
        'category_enabled' => 'boolean',
    ];

    protected $attributes = [
        'category_enabled' => true,
    ];

    public function articles()
    {
        return $this->hasMany(Article::class, 'article_category_id', 'id');
    }

    public function parentCategory()
    {
        return $this->belongsTo(ArticleCategory::class, 'category_parent_id', 'id');
    }

    public function subCategories()
    {
        return $this->hasMany(ArticleCategory::class, 'category_parent_id', 'id');
    }

    public function crawlMapping()
    {
        return $this->hasOne(ArticleCategoryCrawlMapping::class, 'id', 'id');
    }

    public function scopeExcludeDisabled($query, $exclude=true)
    {
        return $exclude ? $query->where('category_enabled', true) : $query;
    }

    public function scopeWithOrder($query, $order=null)
    {
        if($order == 1) return $query->orderBy('category_priority', 'desc');
        if($order == 2) return $query->orderBy('category_priority');
        return $query;
    }

    public function scopeGetSiblings($query)
    {
        return $query->where('category_parent_id', $this->category_parent_id);
    }

    public function scopeGetChildren($query)
    {
        return $query->where('category_parent_id', $this->id);
    }

    public function scopeGetParent($query)
    {
        return $query->where('id', $this->category_parent_id);
    }

    public function getArticles()
    {
        if(empty($this->category_parent_id)){
            $category_parent_id = $this->id;
            //return Article::join('article_categories', 'articles.article_category_id', '=', 'article_categories.id')
            //    ->select('articles.*', 'article_categories.category_name')
            //    ->whereIn('article_category_id', function($query) use ($category_parent_id){
            return Article::whereIn('article_category_id', function($query) use ($category_parent_id){
                    $query->select('id')->from('article_categories')
                    ->where('category_parent_id',$category_parent_id);
                })
                ->orderBy('created_at', 'desc')
                ->paginate(10);
        }
        return $this->articles()->orderBy('created_at', 'desc')->paginate(10);
    }

    public function isTop()
    {
        return empty($this->category_parent_id);
    }

    public static function buildCategoriesTree($categories)
    {
        return self::buildTree($categories, 'category_parent_id');
    }
}
