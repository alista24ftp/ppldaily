<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Visitor extends Model
{
    protected $primaryKey = 'visitor_ip';

    protected $table = 'visitor_ips';

    protected $fillable = ['visitor_ip', 'visitor_blacklisted'];

    public $incrementing = false;

    protected $keyType = 'string';

    public $timestamps = false;

    protected $casts = [
        'visitor_blacklisted' => 'boolean',
    ];

    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        'visitor_blacklisted' => false,
    ];

    public function articleViews()
    {
        return $this->belongsToMany(Article::class, 'article_visitor_views', 'visitor_ip', 'article_id')->withTimestamps();
    }
}
