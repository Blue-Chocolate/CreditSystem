<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory; 

class Product extends Model
{


       use HasFactory; 

    protected $table = 'products';


protected $fillable = [
        'name',            
        'category_id',     
        'description',     
        'image',          
        'points_required', 
        'is_offer_pool',   
    ];

   public function category() {
    return $this->belongsTo(Category::class);
}

public function redemptions() {
    return $this->hasMany(Redemption::class);
}

}
