<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Product;

class RagController extends Controller
{
    public function index(Request $request)
    {
        $users = null;
        $products = null;
        if ($request->filled('user_query')) {
            $users = User::where('name', 'like', '%'.$request->user_query.'%')
                ->orWhere('email', 'like', '%'.$request->user_query.'%')
                ->get();
        }
        if ($request->filled('product_query')) {
            $products = Product::where('name', 'like', '%'.$request->product_query.'%')->get();
        }
        return view('admin.rag', compact('users', 'products'));
    }
}
