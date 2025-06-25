<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use App\Models\Purchase;

class User extends Authenticatable
{
    use Notifiable, HasRoles;

    protected $fillable = [
        'name',
        'email',
        'password',
        'credit_balance',
        'credit_points',
        'reward_points',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function creditPackages()
    {
        return $this->hasMany(\App\Models\CreditPackage::class);
    }

    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }
}
