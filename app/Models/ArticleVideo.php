<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ArticleVideo extends Model
{
    protected $table = 'article_videos';

    public $timestamps = false;

    protected $fillable = ['article_video_path', 'article_id'];

    public function article()
    {
        return $this->belongsTo(Article::class, 'article_id', 'id');
    }
}
