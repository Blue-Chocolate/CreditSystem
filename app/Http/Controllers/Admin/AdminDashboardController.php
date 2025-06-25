<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Product;
use App\Models\CreditPackage;
use App\Models\Order;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $totalUsers = User::count();
        $totalProducts = Product::count();
        $totalPackages = CreditPackage::count();
        $totalSalesToday = Order::whereDate('created_at', today())->sum('total');

        return view('admin.dashboard', [
            'totalUsers' => $totalUsers,
            'totalProducts' => $totalProducts,
            'totalPackages' => $totalPackages,
            'totalSalesToday' => $totalSalesToday,
        ]);
    }
}
