<?php
namespace App\Actions\Product;

use App\Services\Product\ProductService;
use App\Models\Category;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class EditProductAction
{
    public function handle(ProductService $service, $id)
    {
        try {
            $product = $service->show($id);
            $categories = Category::all();
            return compact('product', 'categories');
        } catch (ModelNotFoundException) {
            return null;
        }
    }
}
