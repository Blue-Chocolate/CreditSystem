<?php

namespace App\Actions\Product;

use App\Models\Product;
use App\Repositories\Products\ProductRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Exception;

class StoreProductAction
{
    public function __construct(protected ProductRepository $repo) {}

    public function handle(Request $request): Product|null
    {
        try {
           $data = $request->validate([
    'name' => 'required|string|max:255|unique:products,name',
    'price' => 'required|numeric|min:0.01',
    'stock' => 'required|integer|min:0',
    'category' => 'required|string|in:Electronic Devices,Kitchen Devices,Home Essentials',
    'is_offer_pool' => 'boolean',
    'reward_points' => 'nullable|integer|required_if:is_offer_pool,1|min:1',
    'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
    'image_url' => 'nullable|url',
    'description' => 'nullable|string|max:1000',
    
]);

            $data['is_offer_pool'] = $request->has('is_offer_pool');
            if ($request->hasFile('image')) {
                $data['image'] = $request->file('image')->store('products', 'public');
                $data['image_url'] = null; // Prefer file upload if both provided
            } elseif ($request->filled('image_url')) {
                $data['image'] = null;
                $data['image_url'] = $request->input('image_url');
            }

            return $this->repo->create($data);

        } catch (Exception $e) {
            throw $e; // This will show the real error
        }
    }
}