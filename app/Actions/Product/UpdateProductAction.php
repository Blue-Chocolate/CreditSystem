<?php
namespace App\Actions\Product;

use App\Services\Product\ProductService;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class UpdateProductAction
{
    public function handle(ProductService $service, $id, array $data)
    {
        try {
            $service->update($id, $data);
            return true;
        } catch (ModelNotFoundException) {
            return false;
        }
    }
}
