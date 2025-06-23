<?php

namespace App\Http\Controllers\Admin;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Actions\Product\StoreProductAction;
use App\Actions\Product\UpdateProductAction;
use App\Actions\Product\DeleteProductAction;
use App\Repositories\Products\ProductRepository;

class ProductController extends Controller
{
    public function __construct(protected ProductRepository $repo) {}

   public function index(Request $request)
{
    $name = $request->input('name');
    $isOfferPool = $request->has('is_offer_pool') ? (bool)$request->input('is_offer_pool') : null;

    $products = $this->repo->search($name, $isOfferPool);

    return view('admin.products.index', compact('products', 'name', 'isOfferPool'));
}


    public function create()
    {
        return view('admin.products.create');
    }

    public function store(Request $request, StoreProductAction $action)
    {
        $action->handle($request);
        return redirect()->route('admin.products.index')->with('success', 'Product created successfully.');
    }

    public function show(Product $product)
    {
        return view('admin.products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        return view('admin.products.edit', compact('product'));
    }

    public function update(Request $request, Product $product, UpdateProductAction $action)
    {
        $action->handle($request, $product);
        return redirect()->route('admin.products.index')->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product, DeleteProductAction $action)
    {
        $action->handle($product);
        return redirect()->route('admin.products.index')->with('success', 'Product deleted successfully.');
    }
}
