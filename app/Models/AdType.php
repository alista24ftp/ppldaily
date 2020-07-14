<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdType extends Model
{
    protected $table = 'ad_types';

    protected $fillable = ['ad_type_description', 'ad_type_priority', 'ad_type_enabled'];

    public $timestamps = false;

    public function ads()
    {
        return $this->hasMany(Ad::class, 'ad_type_id', 'id');
    }
}
