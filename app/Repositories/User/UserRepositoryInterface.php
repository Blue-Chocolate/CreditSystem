<?php 


namespace App\Repositories\User;

interface UserRepositoryInterface
{
    public function paginate($perPage = 10);
    public function find($id);
    public function update($id, array $data);
    public function delete($id);
    public function getUserPurchases($id);
}
