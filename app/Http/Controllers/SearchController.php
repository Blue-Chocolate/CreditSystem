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

        // Sanitize and validate search input
        $validated = $request->validate([
            'q' => 'nullable|string|max:255',
            'category' => 'nullable|string|max:255',
            'min_price' => 'nullable|numeric|min:0',
            'max_price' => 'nullable|numeric|min:0',
            'is_offer_pool' => 'nullable|boolean',
            'sort' => 'nullable|in:relevance,price_asc,price_desc',
        ]);
        $query = $validated['q'] ?? null;
        $category = $validated['category'] ?? null;
        $minPrice = $validated['min_price'] ?? null;
        $maxPrice = $validated['max_price'] ?? null;
        $isOfferPool = $validated['is_offer_pool'] ?? null;
        $sort = $validated['sort'] ?? 'relevance';

        $builder = Product::query();

        if ($query) {
            // Unicode-safe search, prevent SQL injection
            $builder->where(function($q) use ($query) {
                $q->where('name', 'like', "%$query%")
                  ->orWhere('description', 'like', "%$query%")
                  ->orWhere('category', 'like', "%$query%")
                  ;
            });
        }
        if ($category) $builder->where('category', $category);
        if ($isOfferPool !== null && $isOfferPool !== '') $builder->where('is_offer_pool', (bool)$isOfferPool);
        if ($minPrice !== null && $minPrice !== '') $builder->where('price', '>=', $minPrice);
        if ($maxPrice !== null && $maxPrice !== '') $builder->where('price', '<=', $maxPrice);

        if ($sort === 'price_asc') $builder->orderBy('price');
        if ($sort === 'price_desc') $builder->orderByDesc('price');
        // Default: relevance (exact match first)
        if ($sort === 'relevance' && $query) {
            $builder->orderByRaw('CASE WHEN name = ? THEN 0 WHEN name LIKE ? THEN 1 ELSE 2 END', [$query, "%$query%"]);
        }

        // Limit query length to prevent overly long queries
        if ($query && mb_strlen($query) > 255) {
            return back()->withErrors(['q' => 'Search query too long.']);
        }

        $products = $builder->paginate(12); // Enforce pagination, max 12 per page
        $categories = Product::distinct()->pluck('category');

        return view('search.index', compact('products', 'categories'));
    }
}
