<?php 

namespace App\Services\CreditPackage;

use App\Repositories\CreditPackage\CreditPackageRepositoryInterface;

class CreditPackageService
{
    protected $repo;

    public function __construct(CreditPackageRepositoryInterface $repo)
    {
        $this->repo = $repo;
    }

    public function getAll()
    {
        return $this->repo->all();
    }

    public function create(array $data)
    {
        return $this->repo->create($data);
    }

    public function update($id, array $data)
    {
        return $this->repo->update($id, $data);
    }

    public function delete($id)
    {
        return $this->repo->delete($id);
    }

    public function find($id)
    {
        return $this->repo->find($id);
    }
}
