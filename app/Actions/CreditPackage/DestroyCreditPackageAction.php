<?php
namespace App\Actions\CreditPackage;

use App\Services\CreditPackage\CreditPackageService;

class DestroyCreditPackageAction
{
    public function handle(CreditPackageService $service, $id)
    {
        return $service->delete($id);
    }
}
