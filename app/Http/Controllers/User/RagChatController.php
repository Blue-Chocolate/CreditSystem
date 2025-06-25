<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;
use Illuminate\Support\Facades\Http;

class RagChatController extends Controller
{
    public function chat(Request $request)
    {
        $user = Auth::user();
        $msg = $request->input('message');

        // Fetch relevant product data
        $products = Product::all()->map(function ($product) {
            return [
                'name' => $product->name,
                'price' => $product->price,
                'is_offer_pool' => $product->is_offer_pool,
                'reward_points' => $product->reward_points,
                'category' => $product->category,
            ];
        });

        // Prepare system prompt for Groq
        $systemPrompt = "You are a smart assistant for an online shop. The user has credit balance: {$user->credit_balance} EGP, credit points: {$user->credit_points}, reward points: {$user->reward_points}. Products available: " . json_encode($products) . ". Answer user questions in a friendly, clear, human-like way. Be helpful, don't make up prices, only use provided data.";

        // Call Groq API
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . env('GROQ_API_KEY'),
            'Content-Type' => 'application/json',
        ])->post('https://api.groq.com/openai/v1/chat/completions', [
            'model' => "meta-llama/llama-4-scout-17b-16e-instruct",
            'messages' => [
                ['role' => 'system', 'content' => $systemPrompt],
                ['role' => 'user', 'content' => $msg],
            ],
            'max_tokens' => 300,
            'temperature' => 0.7,
        ]);

        if ($response->failed()) {
            return response()->json(['reply' => 'Sorry, there was a technical issue. Please try again later.'], 500);
        }

        $reply = $response->json('choices.0.message.content') ?? 'Sorry, I could not understand that.';

        return response()->json(['reply' => $reply]);
    }
}
