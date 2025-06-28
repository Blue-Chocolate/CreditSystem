<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->input('q');
        $category = $request->input('category');
        $minPrice = $request->input('min_price');
        $maxPrice = $request->input('max_price');
        $isOfferPool = $request->input('is_offer_pool');
        $sort = $request->input('sort', 'relevance');

        $builder = Product::query();

        if ($query) $builder->where('name', 'like', "%$query%");
        if ($category) $builder->where('category', $category);
        if ($isOfferPool !== null && $isOfferPool !== '') $builder->where('is_offer_pool', (bool)$isOfferPool);
        if ($minPrice !== null && $minPrice !== '') $builder->where('price', '>=', $minPrice);
        if ($maxPrice !== null && $maxPrice !== '') $builder->where('price', '<=', $maxPrice);

        if ($sort === 'price_asc') $builder->orderBy('price');
        if ($sort === 'price_desc') $builder->orderByDesc('price');

        $products = $builder->paginate(9);
        $categories = Product::distinct()->pluck('category');

        return view('search.index', compact('products', 'categories'));
    }
}
