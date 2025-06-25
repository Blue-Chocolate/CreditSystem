<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'total' => 'required|numeric',
            'payment_method' => 'required|in:cash,visa,credit_points,reward_points',
        ]);

        $user = Auth::user();
        $cart = Cart::with('items.product')->where('user_id', $user->id)->first();

        if (!$cart || $cart->items->isEmpty()) {
            return back()->with('error', 'Your cart is empty.');
        }

        $total = $request->total;

        // Check if user has enough balance for credit points or reward points
        if ($request->payment_method === 'credit_points' && $user->credit_points < $total) {
            return back()->with('error', 'Insufficient credit points.');
        }
        if ($request->payment_method === 'reward_points' && $user->reward_points < $total) {
            return back()->with('error', 'Insufficient reward points.');
        }

        DB::transaction(function () use ($user, $cart, $total, $request) {
            // Deduct balance if applicable
            if ($request->payment_method === 'credit_points') {
                $user->credit_points -= $total;
            } elseif ($request->payment_method === 'reward_points') {
                $user->reward_points -= $total;
            } elseif ($request->payment_method === 'cash' || $request->payment_method === 'visa') {
                $user->credit_balance -= $total; // Optional, only if you deduct real money too
            }
            $user->save();

            // Create the order
            $order = Order::create([
                'user_id' => $user->id,
                'total' => $total,
                'status' => 'Pending',
                'payment_method' => $request->payment_method,
            ]);

            // Create order items
            foreach ($cart->items as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'price' => $item->product->price,
                ]);
            }

            // Clear the cart
            $cart->items()->delete();
        });

        return redirect()->route('user.orders.index')->with('success', 'Order placed successfully!');
    }
}
