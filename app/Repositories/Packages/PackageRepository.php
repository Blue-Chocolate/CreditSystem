<?php

namespace App\Repositories\Packages;

use App\Models\CreditPackage;

class PackageRepository
{
    public function all()
    {
        return CreditPackage::paginate(10);
    }

    public function find($id)
    {
        return CreditPackage::findOrFail($id);
    }
}
