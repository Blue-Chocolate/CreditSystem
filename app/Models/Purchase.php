<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Purchase extends Model
{
        use HasFactory;

   public function user() {
    return $this->belongsTo(User::class);
}

public function creditPackage() {
    return $this->belongsTo(CreditPackage::class);
}

}
