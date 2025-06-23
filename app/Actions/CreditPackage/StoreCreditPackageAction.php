<?php
namespace App\Actions\CreditPackage;

use App\Repositories\CreditPackages\CreditPackageRepository;

class StoreCreditPackageAction
{
    public function __construct(protected CreditPackageRepository $repo) {}

    public function handle(array $data)
    {
        return $this->repo->create($data);
    }
}
