<?php
namespace App\Actions\CreditPackage;

use App\Services\CreditPackage\CreditPackageService;

class UpdateCreditPackageAction
{
    public function handle(CreditPackageService $service, $id, array $data)
    {
        return $service->update($id, $data);
    }
}
