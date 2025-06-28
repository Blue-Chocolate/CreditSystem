<?php
namespace App\Listeners;

use Illuminate\Auth\Events\Registered;

class AssignUserRoleOnRegister
{
    public function handle(Registered $event)
    {
        $user = $event->user;
        if (!$user->hasRole('user')) {
            $user->assignRole('user');
        }
    }
}
