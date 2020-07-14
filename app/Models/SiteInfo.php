<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteInfo extends Model
{
    protected $table = 'site_info';

    protected $fillable = [
        'site_name', 'site_logo', 'site_company_name',
        'site_seo_title', 'site_seo_key', 'site_seo_des'
    ];

    public $timestamps = false;
}
