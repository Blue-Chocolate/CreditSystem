<?php
namespace App\Actions\CreditPackage;

use App\Services\CreditPackage\CreditPackageService;

class IndexCreditPackageAction
{
    public function handle(CreditPackageService $service)
    {
        return $service->getAll();
    }
}
