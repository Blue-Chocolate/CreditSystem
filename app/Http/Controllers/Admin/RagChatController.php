<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Product;

class RagChatController extends Controller
{
    public function chat(Request $request)
    {
        $msg = strtolower($request->input('message'));
        $reply = '';
        if (str_contains($msg, 'user')) {
            $users = User::where('name', 'like', "%$msg%")
                ->orWhere('email', 'like', "%$msg%")
                ->limit(5)->get();
            if ($users->isEmpty()) {
                $reply = "No users found.";
            } else {
                $reply = "Users: ";
                $reply .= $users->pluck('name')->implode(', ');
            }
        } elseif (str_contains($msg, 'product')) {
            $products = Product::where('name', 'like', "%$msg%")
                ->limit(5)->get();
            if ($products->isEmpty()) {
                $reply = "No products found.";
            } else {
                $reply = "Products: ";
                $reply .= $products->pluck('name')->implode(', ');
            }
        } else {
            $reply = "Ask me to search for users or products!";
        }
        return response()->json(['reply' => $reply]);
    }
}
