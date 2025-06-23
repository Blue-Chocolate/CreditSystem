<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = ['name', 'price', 'stock', 'is_offer_pool', 'reward_points', 'image', 'category'];
    protected $casts = [
        'is_offer_pool' => 'boolean',
        'reward_points' => 'integer',
    ];

}
