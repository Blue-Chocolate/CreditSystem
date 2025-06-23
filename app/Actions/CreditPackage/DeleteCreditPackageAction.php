<?php 

namespace App\Actions\CreditPackage;

use App\Repositories\CreditPackages\CreditPackageRepository;

class DeleteCreditPackageAction
{
    public function __construct(protected CreditPackageRepository $repo) {}

    public function handle(\App\Models\CreditPackage $creditPackage)
    {
        return $this->repo->delete($creditPackage);
    }
}
