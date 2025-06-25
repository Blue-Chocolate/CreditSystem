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
        ]);
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
