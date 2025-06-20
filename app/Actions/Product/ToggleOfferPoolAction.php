<?php
namespace App\Actions\Product;

use App\Models\Product;

class ToggleOfferPoolAction
{
    public function handle($id, $is_offer_pool)
    {
        $product = Product::findOrFail($id);
        $product->is_offer_pool = $is_offer_pool ? 1 : 0;
        $product->save();
        return $product;
    }
}
