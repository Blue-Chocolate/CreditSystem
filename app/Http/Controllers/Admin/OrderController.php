<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with('user')->latest()->paginate(10);
        return view('admin.orders.index', compact('orders'));
    }

    public function show($id)
    {
        $order = Order::with('items.product', 'user')->findOrFail($id);
        return view('admin.orders.show', compact('order'));
    }

    public function create()
    {
        $users = User::all();
        return view('admin.orders.create', compact('users'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'user_id' => 'required|exists:users,id',
            'total' => 'required|numeric',
            'status' => 'required|string',
            'payment_method' => 'required|string|in:balance,points,reward',
        ]);
        $user = \App\Models\User::findOrFail($data['user_id']);
        // Deduct from correct balance
        switch ($data['payment_method']) {
            case 'balance':
                if ($user->credit_balance < $data['total']) {
                    return back()->with('error', 'Insufficient balance.');
                }
                $user->decrement('credit_balance', $data['total']);
                break;
            case 'points':
                if ($user->credit_points < $data['total']) {
                    return back()->with('error', 'Insufficient credit points.');
                }
                $user->decrement('credit_points', $data['total']);
                break;
            case 'reward':
                if ($user->reward_points < $data['total']) {
                    return back()->with('error', 'Insufficient reward points.');
                }
                $user->decrement('reward_points', $data['total']);
                break;
        }
        $order = Order::create($data);
        return redirect()->route('admin.orders.index')->with('success', 'Order created!');
    }

    public function edit($id)
    {
        $order = Order::findOrFail($id);
        $users = User::all();
        return view('admin.orders.edit', compact('order', 'users'));
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'user_id' => 'required|exists:users,id',
            'total' => 'required|numeric',
            'status' => 'required|string',
        ]);
        $order = Order::findOrFail($id);
        $order->update($data);
        return redirect()->route('admin.orders.index')->with('success', 'Order updated!');
    }

    public function destroy($id)
    {
        Order::destroy($id);
        return redirect()->route('admin.orders.index')->with('success', 'Order deleted!');
    }
}
