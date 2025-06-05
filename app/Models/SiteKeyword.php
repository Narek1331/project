<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteKeyword extends Model
{
     protected $fillable = [
        'site_id',
        'name',
        'clicks_per_day',
        'url',
        'status'
    ];

    public function site()
    {
        return $this->belongsTo(Site::class);
    }
}
