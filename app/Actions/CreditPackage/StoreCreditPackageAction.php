<?php
namespace App\Actions\CreditPackage;

use App\Services\CreditPackage\CreditPackageService;

class StoreCreditPackageAction
{
    public function handle(CreditPackageService $service, array $data)
    {
        return $service->create($data);
    }
}
