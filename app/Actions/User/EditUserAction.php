<?php
namespace App\Actions\User;

use App\Services\User\UserService;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class EditUserAction
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
