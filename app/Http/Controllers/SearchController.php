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
        $isOfferPool = $request->input('is_offer_pool');
        $sort = $request->input('sort', 'relevance');
        $minPrice = $request->input('min_price');
        $maxPrice = $request->input('max_price');

        $products = collect();
        if ($query || $category || $isOfferPool !== null) {
            $builder = Product::search($query ?? '');
            if ($category) $builder->where('category', $category);
            if ($isOfferPool !== null && $isOfferPool !== '') $builder->where('is_offer_pool', (bool)$isOfferPool);
            if ($sort === 'price_asc') $builder->orderBy('price');
            if ($sort === 'price_desc') $builder->orderByDesc('price');
            $products = $builder->paginate(10);
            // Price range filtering (after pagination)
            $filtered = $products->getCollection();
            if ($minPrice !== null && $minPrice !== '') {
                $filtered = $filtered->filter(fn($p) => $p->price >= $minPrice);
            }
            if ($maxPrice !== null && $maxPrice !== '') {
                $filtered = $filtered->filter(fn($p) => $p->price <= $maxPrice);
            }
            $products->setCollection($filtered->values());
        }
        $categories = Product::query()->distinct()->pluck('category');
        return view('search.index', compact('products', 'query', 'categories', 'category', 'isOfferPool', 'minPrice', 'maxPrice', 'sort'));
    }

    public function autocomplete(Request $request)
    {
        $query = $request->input('q');
        $results = [];
        if ($query) {
            $results = Product::search($query)->take(5)->get()->pluck('name');
        }
        return response()->json($results);
    }
}
