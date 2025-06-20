<?php 

namespace App\Services\User;

use App\Repositories\User\UserRepositoryInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class UserService
{
    protected $repo;

    public function __construct(UserRepositoryInterface $repo)
    {
        $this->repo = $repo;
    }

    public function list($perPage = 10)
    {
        return $this->repo->paginate($perPage);
    }

    public function show($id)
    {
        return $this->repo->find($id);
    }

    public function update($id, array $data)
    {
        return $this->repo->update($id, $data);
    }

    public function delete($id)
    {
        return $this->repo->delete($id);
    }

    public function getPurchases($id)
    {
        return $this->repo->getUserPurchases($id);
    }
}
