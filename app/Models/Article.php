<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Article extends Model
{
    use SoftDeletes;

    protected $table = 'articles';

    protected $fillable = [
        'article_title', 'article_category_id', 'article_type_id', 'author_id',
        'article_source', 'article_source_link',
        'article_description', 'article_content', 'article_thumb',
        'article_enabled',
        'likes_enabled', 'dislikes_enabled', 'comments_enabled', 'article_priority',
        'article_seo_title', 'article_seo_key', 'article_seo_des',
    ];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
        'deleted_at' => 'datetime:Y-m-d H:i:s',
        'article_enabled' => 'boolean',
        'likes_enabled' => 'boolean',
        'dislikes_enabled' => 'boolean',
        'comments_enabled' => 'boolean',
    ];

    protected $attributes = [
        'article_enabled' => true,
        'likes_enabled' => true,
        'dislikes_enabled' => true,
        'comments_enabled' => true,
    ];

    public function category()
    {
        return $this->belongsTo(ArticleCategory::class, 'article_category_id', 'id');
    }

    public function articleType()
    {
        return $this->belongsTo(ArticleType::class, 'article_type_id', 'id');
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id', 'id');
    }

    public function likedUsers()
    {
        return $this->belongsToMany(User::class, 'article_likes', 'article_id', 'user_id')->withTimestamps();
    }

    public function dislikedUsers()
    {
        return $this->belongsToMany(User::class, 'article_dislikes', 'article_id', 'user_id')->withTimestamps();
    }

    public function viewedUsers()
    {
        return $this->belongsToMany(User::class, 'article_user_views', 'article_id', 'user_id')->withTimestamps();
    }

    public function viewedVisitors()
    {
        return $this->belongsToMany(Visitor::class, 'article_visitor_views', 'article_id', 'visitor_ip')->withTimestamps();
    }

    public function comments()
    {
        return $this->hasMany(Comment::class, 'comment_id', 'id');
    }
}
