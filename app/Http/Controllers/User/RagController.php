<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;

class RagController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $products = Product::all();
        return view('users.rag', compact('user', 'products'));
    }
}
