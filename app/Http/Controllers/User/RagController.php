<?php 


namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;
use App\Models\CreditPackage;

class RagController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $products = Product::all();
        $packages = CreditPackage::all();

        return view('users.rag', compact('user', 'products', 'packages'));
    }
}

