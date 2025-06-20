<?php
namespace App\Actions\Product;

use App\Services\Product\ProductService;

class IndexProductAction
{
    public function handle(ProductService $service)
    {
        return $service->list();
    }
}
