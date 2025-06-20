<?php
namespace App\Actions\User;

use App\Services\User\UserService;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class DestroyUserAction
{
    public function handle(UserService $service, $id)
    {
        try {
            $service->delete($id);
            return true;
        } catch (ModelNotFoundException) {
            return false;
        }
    }
}
