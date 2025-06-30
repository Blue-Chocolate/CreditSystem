<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class RagController extends Controller
{
    // POST /api/rag/chat
    public function chat(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
        ]);
        try {
            $user = Auth::user();
            $message = $request->input('message');
            // Try to call AI/RAG service logic
            try {
                $response = app('ai.rag')->chat($user, $message); // Example service binding
            } catch (\Throwable $inner) {
                Log::error('AI service not available: ' . $inner->getMessage(), ['exception' => $inner]);
                $response = null;
            }
            if (!$response) {
                // Fallback dummy response for debugging
                if (stripos($message, 'credit points') !== false) {
                    // Example: recommend products that can be bought with credit points
                    $products = \App\Models\Product::where('price', '<=', $user->credit_points)->limit(5)->pluck('name')->toArray();
                    if (count($products)) {
                        $response = 'You can buy these products with your credit points: ' . implode(', ', $products);
                    } else {
                        $response = 'You do not have enough credit points to buy any products right now.';
                    }
                } else {
                    $response = 'AI service is not configured. Echo: ' . $message;
                }
            }
            return response()->json([
                'success' => true,
                'message' => $message,
                'response' => $response,
            ]);
        } catch (\Throwable $e) {
            Log::error('API RAG chat error: ' . $e->getMessage(), ['exception' => $e]);
            return response()->json([
                'success' => false,
                'error' => 'An error occurred while processing your chat request.'
            ], 500);
        }
    }
}
