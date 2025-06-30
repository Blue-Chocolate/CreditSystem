<?php

namespace App\Repositories\Products;

use App\Models\Product;
use Illuminate\Support\Facades\Log;
use Exception;

class ProductRepository
{
    public function all()
    {
        try {
           return Product::latest()->paginate(5);
        } catch (Exception $e) {
            Log::error('Fetching products failed: ' . $e->getMessage());
            return collect();
        }
    }

    public function create(array $data)
    {
        try {
            return Product::create($data);
        } catch (Exception $e) {
            Log::error('Product creation failed in repository: ' . $e->getMessage());
            throw $e;
        }
    }

    public function update(Product $product, array $data)
{
    try {
        return $product->update($data);
    } catch (Exception $e) {
        Log::error('Product update failed in repository: ' . $e->getMessage());
        throw $e;
    }
}

    public function delete(Product $product)
    {
        try {
            return $product->delete();
        } catch (Exception $e) {
            Log::error('Product deletion failed in repository: ' . $e->getMessage());
            throw $e;
        }
    }

    public function search($name = null, $isOfferPool = null, $category = null)
    {
        try {
            return Product::when($name, fn($q) => $q->where('name', 'like', "%$name%"))
                          ->when(!is_null($isOfferPool), fn($q) => $q->where('is_offer_pool', $isOfferPool))
                          ->when($category, fn($q) => $q->where('category', $category))
                          ->latest()
                          ->paginate(10);
        } catch (Exception $e) {
            Log::error('Product search failed: ' . $e->getMessage());
            return collect();
        }
    }
}
