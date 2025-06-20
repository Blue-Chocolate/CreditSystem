<?php
namespace App\Actions\User;

use App\Services\User\UserService;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ShowUserAction
{
    public function handle(UserService $service, $id)
    {
        try {
            return $service->show($id);
        } catch (ModelNotFoundException) {
            return null;
        }
    }
}
