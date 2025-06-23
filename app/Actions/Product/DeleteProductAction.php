<?php

namespace App\Actions\Product;

use App\Models\Product;
use App\Repositories\Products\ProductRepository;
use Illuminate\Support\Facades\Log;
use Exception;

class DeleteProductAction
{
    public function __construct(protected ProductRepository $repo) {}

    public function handle(Product $product): bool
    {
        try {
            return $this->repo->delete($product);
        } catch (Exception $e) {
            Log::error('Error deleting product: ' . $e->getMessage());
            return false;
        }
    }
}

