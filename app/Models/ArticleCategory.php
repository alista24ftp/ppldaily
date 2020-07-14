<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ArticleCategory extends Model
{
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
}
