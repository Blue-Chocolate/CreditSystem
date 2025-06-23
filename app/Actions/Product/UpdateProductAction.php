<?php

namespace App\Actions\Product;

use App\Models\Product;
use App\Repositories\Products\ProductRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Exception;

class UpdateProductAction
{
    public function __construct(protected ProductRepository $repo) {}

    public function handle(Request $request, Product $product): bool
    {
        try {
            $data = $request->validate([
                'name' => 'required|string|max:255',
                'price' => 'required|numeric',
                'stock' => 'required|integer|min:0',
                'reward_points' => 'nullable|integer|required_if:is_offer_pool,1',
                'image' => 'nullable|image|max:2048',
                'category' => 'required|string|max:255',
            ]);

            // Correct handling of offer pool value
            $data['is_offer_pool'] = (bool) $request->input('is_offer_pool', 0);

            if (!$data['is_offer_pool']) {
                $data['reward_points'] = null;
            }

            if ($request->hasFile('image')) {
                $data['image'] = $request->file('image')->store('products', 'public');
            }

            return $this->repo->update($product, $data);

        } catch (Exception $e) {
            Log::error('Error updating product: ' . $e->getMessage());
            return false;
        }
    }
}
