<?php
namespace App\Actions\Product;

use App\Services\Product\ProductService;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class DestroyProductAction
{
    public function handle(ProductService $service, $id)
    {
        try {
            $service->delete($id);
            return true;
        } catch (ModelNotFoundException) {
            return false;
        }
    }
}
