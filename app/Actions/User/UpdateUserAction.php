<?php
namespace App\Actions\User;

use App\Services\User\UserService;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class UpdateUserAction
{
    public function handle(UserService $service, $id, array $data)
    {
        try {
            $service->update($id, $data);
            return true;
        } catch (ModelNotFoundException) {
            return false;
        }
    }
}
