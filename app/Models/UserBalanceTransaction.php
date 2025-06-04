<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserBalanceTransaction extends Model
{
     protected $fillable = [
        'user_id',
        'price',
        'description',
        'type',
        'status'
    ];
}
