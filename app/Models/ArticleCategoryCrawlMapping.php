<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ArticleCategoryCrawlMapping extends Model
{
    protected $table = 'article_categories_crawl_mappings';

    public $timestamps = false;

    protected $casts = [
        'last_crawled_at' => 'datetime:Y-m-d H:i:s',
        'mapping' => 'array',
    ];

    public function articleCategory()
    {
        return $this->belongsTo(ArticleCategory::class, 'id', 'id');
    }
}
