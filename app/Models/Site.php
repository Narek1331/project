<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Site extends Model
{
    protected $fillable = [
        'user_id',
        'domain',
        'region',
        'click_in_hour',
        'click_per_day',
        'clean_click_in_hour',
        'clean_click_per_day',
        'status'
    ];


    public function keywords()
    {
        return $this->hasMany(SiteKeyword::class,'site_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
