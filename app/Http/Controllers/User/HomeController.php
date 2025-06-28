<?php 

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Product;

class HomeController extends Controller
{
  public function index()
{
    $products = Product::latest()->paginate(12); // Show 12 products per page
    return view('users.products.home', compact('products'));
}
    public function show(Product $product)
    {
        return view('users.products.show', compact('product'));
    }
}
  