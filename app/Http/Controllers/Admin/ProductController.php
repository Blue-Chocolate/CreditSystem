<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Product\ProductService;
use App\Models\Category;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Actions\Product\IndexProductAction;
use App\Actions\Product\CreateProductAction;
use App\Actions\Product\StoreProductAction;
use App\Actions\Product\EditProductAction;
use App\Actions\Product\UpdateProductAction;
use App\Actions\Product\DestroyProductAction;
use App\Actions\Product\ToggleOfferPoolAction;
use App\Actions\Product\ShowProductAction;

class ProductController extends Controller
{
    protected $service;

    public function __construct(ProductService $service)
    {
        $this->service = $service;
    }

    public function index(IndexProductAction $action)
    {
        $products = $action->handle($this->service);
        return view('admin.products.index', compact('products'));
    }

    public function create(CreateProductAction $action)
    {
        $categories = $action->handle();
        return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request, StoreProductAction $action)
    {
        $data = $request->validate([
            'name' => 'required|string',
            'description' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|max:2048',
            'points_required' => 'required|integer|min:1',
            'is_offer_pool' => 'required|boolean',
        ]);
        $action->handle($this->service, $data);
        return redirect()->route('admin.products.index')->with('success', 'Product added');
    }

    public function edit($id, EditProductAction $action)
    {
        $result = $action->handle($this->service, $id);
        if ($result) {
            return view('admin.products.edit', $result);
        }
        return back()->with('error', 'Product not found');
    }

    public function update(Request $request, $id, UpdateProductAction $action)
    {
        $data = $request->validate([
            'name' => 'required|string',
            'description' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|max:2048',
            'points_required' => 'required|integer|min:1',
            'is_offer_pool' => 'required|boolean',
        ]);
        if ($action->handle($this->service, $id, $data)) {
            return redirect()->route('admin.products.index')->with('success', 'Product updated');
        }
        return back()->with('error', 'Product not found');
    }

    public function destroy($id, DestroyProductAction $action)
    {
        if ($action->handle($this->service, $id)) {
            return redirect()->route('admin.products.index')->with('success', 'Product deleted');
        }
        return back()->with('error', 'Product not found');
    }

    public function toggleOfferPool(Request $request, $id, ToggleOfferPoolAction $action)
    {
        $product = $action->handle($id, $request->input('is_offer_pool'));
        return response()->json(['success' => true, 'is_offer_pool' => $product->is_offer_pool]);
    }

    public function show($id, ShowProductAction $action)
    {
        $product = $action->handle($this->service, $id);
        if ($product) {
            return view('admin.products.show', compact('product'));
        }
        return back()->with('error', 'Product not found');
    }
}
