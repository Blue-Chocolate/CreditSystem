<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Product;
use App\Models\CreditPackage;
use Illuminate\Http\Request;

class AdminSearchController extends Controller
{
    public function __invoke(Request $request)
    {
        $q = $request->input('q');
        $users = $products = $packages = collect();
        if ($q) {
            $users = User::where('name', 'like', "%$q%")
                ->orWhere('id', $q)
                ->limit(10)->get();
            $products = Product::where('name', 'like', "%$q%")
                ->orWhere('id', $q)
                ->limit(10)->get();
            $packages = CreditPackage::where('name', 'like', "%$q%")
                ->orWhere('id', $q)
                ->limit(10)->get();
        }
        return view('admin.search', compact('q', 'users', 'products', 'packages'));
    }
}
