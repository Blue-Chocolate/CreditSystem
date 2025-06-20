<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CreditPackage extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',          
        'price_egp',     
        'credit_amount', 
        'reward_points', 
    ];

   public function purchases() {
    return $this->hasMany(Purchase::class);
}

}
