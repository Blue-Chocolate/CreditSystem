<?php 

namespace App\Repositories\CreditPackage;

use App\Models\CreditPackage;

class CreditPackageRepository implements CreditPackageRepositoryInterface
{
    public function all() {
        return CreditPackage::all();
    }

    public function find($id) {
        return CreditPackage::findOrFail($id);
    }

    public function create(array $data) {
        return CreditPackage::create($data);
    }

    public function update($id, array $data) {
        $package = $this->find($id);
        $package->update($data);
        return $package;
    }

    public function delete($id) {
        return CreditPackage::destroy($id);
    }
}
