<?php
namespace App\Services;

use App\Models\Document;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class RagService
{
    public function answer(string $query): string
    {
        // Retrieve top 3 relevant documents using simple LIKE search (replace with full-text for production)
        $docs = Document::where('content', 'like', "%$query%")
            ->orWhere('title', 'like', "%$query%")
            ->limit(3)
            ->get();
        $context = $docs->pluck('content')->implode("\n---\n");

        // Call Groq API (replace YOUR_GROQ_API_KEY with your actual key)
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . env('GROQ_API_KEY'),
            'Content-Type' => 'application/json',
        ])->post('https://api.groq.com/openai/v1/chat/completions', [
            'model' => 'llama3-8b-8192',
            'messages' => [
                ['role' => 'system', 'content' => 'You are a helpful assistant. Use the provided context to answer.'],
                ['role' => 'user', 'content' => "Context:\n$context\n\nQuestion: $query"],
            ],
            'max_tokens' => 512,
        ]);

        // Debug: log the response and context
        if (!$response->ok()) {
            Log::error('Groq API error', ['response' => $response->json()]);
            return 'Error: Unable to get answer from Groq API.';
        }

        // Custom logic: handle special queries about all main tables
        $lowerQuery = strtolower($query);
        // Users
        if (str_contains($lowerQuery, 'how many users')) {
            $count = \App\Models\User::count();
            return "There are $count users in the system.";
        }
        if (str_contains($lowerQuery, 'list all users')) {
            $users = \App\Models\User::all()->pluck('name')->implode(', ');
            return "Users: $users";
        }
        if (preg_match('/show details for user (\\d+)/', $lowerQuery, $m)) {
            $user = \App\Models\User::find($m[1]);
            if ($user) {
                return "User #{$user->id}: {$user->name}, {$user->email}";
            } else {
                return "User not found.";
            }
        }
        // Credit Packages
        if (str_contains($lowerQuery, 'how many packages')) {
            $count = \App\Models\CreditPackage::count();
            return "There are $count credit packages in the system.";
        }
        if (str_contains($lowerQuery, 'list all packages')) {
            $packages = \App\Models\CreditPackage::all()->pluck('name')->implode(', ');
            return "Credit Packages: $packages";
        }
        if (preg_match('/show details for package (\\d+)/', $lowerQuery, $m)) {
            $pkg = \App\Models\CreditPackage::find($m[1]);
            if ($pkg) {
                return "Package #{$pkg->id}: {$pkg->name}, Price: {$pkg->price_egp}, Credits: {$pkg->credit_amount}";
            } else {
                return "Package not found.";
            }
        }
        // Products
        if (str_contains($lowerQuery, 'how many products')) {
            $count = \App\Models\Product::count();
            return "There are $count products in the system.";
        }
        if (str_contains($lowerQuery, 'list all products')) {
            $products = \App\Models\Product::all()->pluck('name')->implode(', ');
            return "Products: $products";
        }
        if (preg_match('/show details for product (\\d+)/', $lowerQuery, $m)) {
            $product = \App\Models\Product::find($m[1]);
            if ($product) {
                return "Product #{$product->id}: {$product->name}, Points: {$product->points_required}";
            } else {
                return "Product not found.";
            }
        }
        // Purchases
        if (str_contains($lowerQuery, 'how many purchases')) {
            $count = \App\Models\Purchase::count();
            return "There are $count purchases in the system.";
        }
        // Redemptions
        if (str_contains($lowerQuery, 'how many redemptions')) {
            $count = \App\Models\Redemption::count();
            return "There are $count redemptions in the system.";
        }
        // Categories
        if (str_contains($lowerQuery, 'how many categories')) {
            $count = \App\Models\Category::count();
            return "There are $count categories in the system.";
        }
        if (str_contains($lowerQuery, 'list all categories')) {
            $categories = \App\Models\Category::all()->pluck('name')->implode(', ');
            return "Categories: $categories";
        }
        if (preg_match('/show details for category (\\d+)/', $lowerQuery, $m)) {
            $cat = \App\Models\Category::find($m[1]);
            if ($cat) {
                return "Category #{$cat->id}: {$cat->name}";
            } else {
                return "Category not found.";
            }
        }
        if (empty($context)) {
            return 'No relevant documents found in the database.';
        }
        $answer = $response->json('choices.0.message.content');
        if (!$answer) {
            Log::warning('Groq API returned no answer', ['response' => $response->json(), 'context' => $context]);
            return 'Groq API did not return an answer. Check your API key, model, and context.';
        }

        return $answer;
    }
}
