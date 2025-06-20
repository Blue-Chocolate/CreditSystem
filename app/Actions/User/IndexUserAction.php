<?php
namespace App\Actions\User;

use App\Services\User\UserService;

class IndexUserAction
{
    public function handle(UserService $service, $perPage = 15)
    {
        return $service->list($perPage);
    }
}
