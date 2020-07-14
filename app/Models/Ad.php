<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ad extends Model
{
    protected $table = 'ads';

    protected $fillable = ['ad_title', 'ad_description', 'ad_type_id', 'ad_pic', 'ad_link', 'ad_priority', 'ad_enabled'];

    public function adType()
    {
        return $this->belongsTo(AdType::class, 'ad_type_id', 'id');
    }
}
