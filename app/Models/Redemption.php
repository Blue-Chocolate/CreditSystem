<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory; 

class Redemption extends Model
{
   use HasFactory; 
    protected $table = 'redemptions';

      protected $fillable = [
        'user_id',      // Foreign key to users table
        'product_id',   // Foreign key to products table
        'points_used',  // Points used for redemption
    ];
  public function user() {
    return $this->belongsTo(User::class);
}

public function product() {
    return $this->belongsTo(Product::class);
}
}
