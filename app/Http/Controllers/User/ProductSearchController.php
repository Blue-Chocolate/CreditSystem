<?php
namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;

class ProductSearchController extends Controller
{
    public function search(Request $request)
    {
        $query = $request->input('query', '');
        $perPage = (int) $request->input('per_page', 10);
        $products = Product::query()
            ->when($query, function ($q) use ($query) {
                $q->where('name', 'like', "%$query%")
                  ->orWhere('category', 'like', "%$query%")
                  ->orWhere('description', 'like', "%$query%")
                  ;
            })
            ->paginate($perPage);
        return response()->json($products);
    }
}
