<?php
namespace App\Actions\Product;

use App\Services\Product\ProductService;

class StoreProductAction
{
    public function handle(ProductService $service, array $data)
    {
        return $service->create($data);
    }
}
