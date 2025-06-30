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
                'name' => 'required|string|max:255|unique:products,name,' . $product->id,
                'price' => 'required|numeric|min:0.01',
                'stock' => 'required|integer|min:0',
                'reward_points' => 'nullable|integer|required_if:is_offer_pool,1|min:1|max:10000',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
                'category' => 'required|string|in:Electronic Devices,Kitchen Devices,Home Essentials',
                'description' => 'nullable|string|max:1000',
                'image_url' => 'nullable|url',
                'is_offer_pool' => 'boolean',
            ]);

            // Prevent product with empty or duplicate name/category
            if (empty($data['name']) || empty($data['category'])) {
                throw new Exception('Product name and category are required.');
            }
            // Prevent faulty reward rule
            if (isset($data['reward_points']) && ($data['reward_points'] < 1 || $data['reward_points'] > 10000)) {
                throw new Exception('Reward points must be between 1 and 10,000.');
            }
            $data['image_url'] = $request->input('image_url', null);
            $data['is_offer_pool'] = (bool) $request->input('is_offer_pool', 0);
            if (!$data['is_offer_pool']) {
                $data['reward_points'] = null;
            }
            if ($request->hasFile('image')) {
                $data['image'] = $request->file('image')->store('products', 'public');
            }
            // Optimistic locking: check updated_at
            if ($request->has('updated_at') && $product->updated_at->toDateTimeString() !== $request->input('updated_at')) {
                throw new Exception('This product was updated by another admin. Please refresh and try again.');
            }
            return $this->repo->update($product, $data);
        } catch (Exception $e) {
            Log::error('Error updating product: ' . $e->getMessage());
            return false;
        }
    }
}
