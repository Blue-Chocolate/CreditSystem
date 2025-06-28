<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Order;
use App\Models\Purchase;

class UserHistoryController extends Controller
{
    public function index(Request $request)
    {
        $users = User::all();
        $selectedUser = null;
        $orders = collect();
        $purchases = collect();
        if ($request->user_id) {
            $selectedUser = User::find($request->user_id);
            if ($selectedUser) {
                $orders = Order::where('user_id', $selectedUser->id)->orderByDesc('created_at')->get();
                $purchases = $selectedUser->purchases()->with('package')->orderByDesc('purchased_at')->get();
            }
        }
        return view('admin.users.history', compact('users', 'selectedUser', 'orders', 'purchases'));
    }
}