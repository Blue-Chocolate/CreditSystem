<?php

namespace App\Actions\CreditPackage;

use App\Repositories\CreditPackages\CreditPackageRepository;

class UpdateCreditPackageAction
{
    public function __construct(protected CreditPackageRepository $repo) {}

    public function handle(\App\Models\CreditPackage $package, array $data)
    {
        return $this->repo->update($package, $data);
    }
}
