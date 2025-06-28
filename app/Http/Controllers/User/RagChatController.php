<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;
use App\Models\CreditPackage;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class RagChatController extends Controller
{
    public function chat(Request $request)
    {
        $user = Auth::user();
        $msg = $request->input('message');

        $products = Product::all()->map(function ($product) use ($user) {
            return [
                'name' => $product->name,
                'price' => $product->price,
                'is_offer_pool' => $product->is_offer_pool,
                'reward_points' => $product->reward_points,
                'category' => $product->category,
                'can_buy_with_credits' => $user->credit_points >= $product->price,
                'can_buy_with_rewards' => $product->is_offer_pool && $user->reward_points >= $product->reward_points,
            ];
        });

        $packages = CreditPackage::all()->map(function ($package) {
            return [
                'name' => $package->name,
                'price' => $package->price,
                'credits' => $package->credits,
                'reward_points' => $package->reward_points,
            ];
        });

        $systemPrompt = "You are a friendly assistant for an online shop. The user has:
- Credit Balance: {$user->credit_balance} EGP
- Credit Points: {$user->credit_points}
- Reward Points: {$user->reward_points}

Available Products (with user ability to purchase):
" . json_encode($products) . "

Available Credit Packages:
" . json_encode($packages) . "

Answer user questions clearly and only based on this data. Don't invent products or prices.";

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . env('GROQ_API_KEY'),
            'Content-Type' => 'application/json',
        ])->post('https://api.groq.com/openai/v1/chat/completions', [
            'model' => "meta-llama/llama-4-scout-17b-16e-instruct",
            'messages' => [
                ['role' => 'system', 'content' => $systemPrompt],
                ['role' => 'user', 'content' => $msg],
            ],
            'max_tokens' => 400,
            'temperature' => 0.7,
        ]);

        if ($response->failed()) {
            Log::error('Groq API request failed', [
                'user_id' => $user->id,
                'request' => [
                    'systemPrompt' => $systemPrompt,
                    'userMessage' => $msg
                ],
                'response_status' => $response->status(),
                'response_body' => $response->body()
            ]);
            return response()->json(['reply' => 'Sorry, technical issue occurred. Please try again.'], 500);
        }

        $reply = $response->json('choices.0.message.content') ?? 'Sorry, I could not understand that.';
        return response()->json(['reply' => $reply]);
    }
}
