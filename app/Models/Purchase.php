<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    protected $fillable = [
        'user_id',
        'package_id',
        'price',
        'credits',
        'reward_points',
        'purchased_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function package()
    {
        return $this->belongsTo(CreditPackage::class, 'package_id');
    }
}
