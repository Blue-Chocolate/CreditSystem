<?php 


namespace App\Repositories\CreditPackages;

use App\Models\CreditPackage;

class CreditPackageRepository
{
    public function all()
    {
        return CreditPackage::latest()->get();
    }

    public function create(array $data)
    {
        return CreditPackage::create($data);
    }

    public function update(CreditPackage $package, array $data)
    {
        return $package->update($data);
    }

    public function delete(CreditPackage $package)
    {
        return $package->delete();
    }
}
