<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ArticleKeyword extends Model
{
    protected $table = 'article_keywords';

    public $timestamps = false;

    protected $fillable = ['article_keyword', 'article_id'];

    public function article()
    {
        return $this->belongsTo(Article::class, 'article_id', 'id');
    }
}
