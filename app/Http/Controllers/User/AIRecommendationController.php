<?php
namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class AIRecommendationController extends Controller
{
    public function recommend(Request $request)
    {
        $user = Auth::user();
        $products = Product::all();
        // Simple mock: recommend the most expensive product user can afford with points
        $recommended = $products->where('is_offer_pool', true)
            ->filter(fn($p) => $user->reward_points >= $p->reward_points)
            ->sortByDesc('reward_points')
            ->first();
        if (!$recommended) {
            return response()->json(['recommendation' => null, 'message' => 'No redeemable products found.']);
        }
        return response()->json(['recommendation' => $recommended]);
    }
}
