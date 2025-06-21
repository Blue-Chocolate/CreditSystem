<?php
namespace App\Actions\Product;

use App\Services\Product\ProductService;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ShowProductAction
{
    public function handle(ProductService $service, $id)
    {
        try {
            return $service->show($id);
        } catch (ModelNotFoundException) {
            return null;
        }
    }
}
