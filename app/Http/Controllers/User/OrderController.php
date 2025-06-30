<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Cart;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::where('user_id', Auth::id())->latest()->paginate(10);
        return view('users.orders.index', compact('orders'));
    }

    public function show($id)
    {
        $order = Order::where('id', $id)
            ->where('user_id', Auth::id())
            ->with('items.product')
            ->firstOrFail();

        return view('users.orders.show', compact('order'));
    }
    public function create()
{
    $user = Auth::user();
    $cart = Cart::with('items.product')->where('user_id', $user->id)->first();

    if (!$cart || $cart->items->isEmpty()) {
        return redirect()->route('user.cart.show')->with('error', 'Your cart is empty.');
    }

    $total = 0;
    $missingProduct = false;
    $insufficientStock = null;

   foreach ($cart->items as $item) {
    $product = $item->product;
    if (!$product) {
        $item->delete(); 
        $missingProduct = true;
        continue;
    }
    if ($product->stock < $item->quantity) {
        $insufficientStock = $product->name;
    }
    $total += $product->price * $item->quantity;
}

if ($missingProduct) {
    return redirect()->route('user.cart.show')->with('error', 'A product in your cart was removed and has been automatically cleared from your cart.');
}
    if ($insufficientStock) {
        return redirect()->route('user.cart.show')->with('error', "Not enough stock for product: {$insufficientStock}.");
    }

    if ($user->credit_balance < $total) {
        return redirect()->route('user.cart.show')->with('error', 'Insufficient balance to place the order.');
    }

    return view('users.orders.create', compact('cart', 'total'));
}

    public function store(Request $request)
{
    $request->validate([
        'total' => 'required|numeric',
        'payment_method' => 'required|string|in:balance,points,reward', // Allowed methods
    ]);

    $user = Auth::user();
    $cart = Cart::with('items.product')->where('user_id', $user->id)->first();

    if (!$cart || $cart->items->isEmpty()) {
        return back()->with('error', 'Your cart is empty.');
    }

    $total = 0;
    $insufficientStock = null;
    $missingProduct = false;
    $validItems = [];

    foreach ($cart->items as $item) {
        $product = $item->product;
        if (!$product) {
            $item->delete();
            $missingProduct = true;
            continue;
        }
        if ($product->stock < $item->quantity) {
            $insufficientStock = $product->name;
        }
        $total += $product->price * $item->quantity;
        $validItems[] = $item;
    }

    // Check balance based on payment method
    switch ($request->payment_method) {
        case 'balance':
            if ($user->credit_balance < $total) {
                return back()->with('error', 'Insufficient balance to complete checkout.');
            }
            break;

        case 'points':
            if ($user->credit_points < $total) {
                return back()->with('error', 'Insufficient credit points to complete checkout.');
            }
            break;

        case 'reward':
            if ($user->reward_points < $total) {
                return back()->with('error', 'Insufficient reward points to complete checkout.');
            }
            break;
    }

    if ($insufficientStock) {
        return back()->with('error', "Not enough stock for product: {$insufficientStock}.");
    }

    if ($missingProduct && count($validItems) === 0) {
        return back()->with('error', 'All products in your cart are unavailable or have been removed.');
    }

    if ($missingProduct) {
        session()->flash('warning', 'Some unavailable products were removed from your cart. The rest will be checked out.');
    }

    DB::beginTransaction();
    try {
        $order = Order::create([
            'user_id' => $user->id,
            'total' => $total,
            'status' => 'pending',
            'payment_method' => $request->payment_method,
        ]);

        foreach ($validItems as $item) {
            $product = $item->product;
            $order->items()->create([
                'product_id' => $product->id,
                'quantity' => $item->quantity,
                'price' => $product->price,
            ]);
            $product->decrement('stock', $item->quantity);
        }

        // Deduct from correct balance
        switch ($request->payment_method) {
            case 'balance':
                $user->decrement('credit_balance', $total);
                break;
            case 'points':
                $user->decrement('credit_points', $total);
                break;
            case 'reward':
                $user->decrement('reward_points', $total);
                break;
        }

        $cart->items()->delete();
        DB::commit();

        return redirect()->route('user.orders.index')->with('success', 'Order placed successfully!');
    } catch (\Throwable $e) {
        DB::rollBack();
        Log::error('Order checkout error: ' . $e->getMessage());
        return back()->with('error', 'An error occurred during checkout. Please try again.');
    }
}
    public function destroy($id)
    {
        $order = Order::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
        $order->delete();

        return redirect()->route('user.orders.index')->with('success', 'Order canceled successfully.');
    }

    public function history()
    {
        $orders = Order::where('user_id', Auth::id())->latest()->get();
        return view('users.orders.history', compact('orders'));
    }
}
