<?php 


namespace App\Repositories\User;

use App\Models\User;

class UserRepository implements UserRepositoryInterface
{
    public function paginate($perPage = 10)
    {
        return User::paginate($perPage);
    }

    public function find($id)
    {
        return User::with('purchases.creditPackage')->findOrFail($id);
    }

    public function update($id, array $data)
    {
        $user = User::findOrFail($id);
        $user->update($data);
        return $user;
    }

    public function delete($id)
    {
        return User::destroy($id);
    }

    public function getUserPurchases($id)
    {
        return User::findOrFail($id)->purchases()->with('creditPackage')->get();
    }
}
