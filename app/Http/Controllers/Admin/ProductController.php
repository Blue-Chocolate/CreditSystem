<?php

namespace App\Http\Controllers\Admin;

use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Actions\Product\StoreProductAction;
use App\Actions\Product\UpdateProductAction;
use App\Actions\Product\DeleteProductAction;
use App\Repositories\Products\ProductRepository;
use Carbon\Carbon;

class ProductController extends Controller
{
    protected int $lockTimeoutMinutes = 10;

    public function __construct(protected ProductRepository $repo) {}

    public function index(Request $request)
    {
        $name = $request->input('name');
        $isOfferPool = $request->has('is_offer_pool') ? (bool)$request->input('is_offer_pool') : null;
        $category = $request->input('category');

        $products = $this->repo->search($name, $isOfferPool, $category);

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
        $now = Carbon::now();

        // Check if product is locked by another admin
        if ($product->locked_by && $product->locked_by !== auth()->id()) {
            if (!$product->locked_at) {
                return back()->with('error', 'This product is currently under maintenance. Please try again later.');
            }

            $lockedAt = $product->locked_at instanceof Carbon ? $product->locked_at : Carbon::parse($product->locked_at);

            if ($lockedAt->diffInMinutes($now) < $this->lockTimeoutMinutes) {
                $lockedUser = User::find($product->locked_by);
                $lockedByName = $lockedUser ? $lockedUser->name : 'another admin';
                $lockedAtFormatted = $lockedAt->format('Y-m-d H:i:s');
                return back()->with('error', "This product is currently being edited by {$lockedByName} (locked at {$lockedAtFormatted}). Please try again later.");
            }
        }

        // Lock the product for the current admin
        $product->locked_by = auth()->id();
        $product->locked_at = $now;
        $product->save();

        return view('admin.products.edit', compact('product'));
    }

    public function update(Request $request, Product $product, UpdateProductAction $action)
    {
        // Only allow update if current user owns the lock
        if ($product->locked_by !== auth()->id()) {
            return back()->with('error', 'You do not own the lock for this product. Please refresh the page.');
        }

        $action->handle($request, $product);

        // Clear the lock after successful update
        $product->locked_by = null;
        $product->locked_at = null;
        $product->save();

        return redirect()->route('admin.products.index')->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product, DeleteProductAction $action)
    {
        $action->handle($product);
        return redirect()->route('admin.products.index')->with('success', 'Product deleted successfully.');
    }
}
