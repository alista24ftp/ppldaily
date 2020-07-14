<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ArticleType extends Model
{
    protected $table = 'article_types';

    protected $fillable = ['article_type_description'];

    public $timestamps = false;

    public function articles()
    {
        return $this->hasMany(Article::class, 'article_type_id', 'id');
    }
}
