<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function add(Request $request)
    {
        $request->validate(['id' => 'required|exists:products,id']);

        $userId = Auth::id();
        // Get or create cart
        $cart = Cart::firstOrCreate(['user_id' => $userId]);

        // Add or update item
        $item = CartItem::firstOrNew([
            'cart_id' => $cart->id,
            'product_id' => $request->id
        ]);
        $item->quantity = $item->quantity + 1;
        $item->save();

        return response()->json(['success' => true]);
    }

    public function getCart()
    {
        try {
            $cart = Cart::with('items.product')
                        ->where('user_id', Auth::id())
                        ->first();

            $items = [];
            $total = 0;

            if ($cart) {
                foreach ($cart->items as $item) {
                    if (! $item->product) {
                        continue;
                    }
                    $items[] = [
                        'id'       => $item->product_id,
                        'name'     => $item->product->name,
                        'price'    => $item->product->price,
                        'quantity' => $item->quantity,
                    ];
                    $total += $item->product->price * $item->quantity;
                }
            }

            return response()->json(['cart' => $items, 'total' => $total]);
        } catch (\Throwable $e) {
            // Send error details back to the client for debugging
            return response()->json([
                'error'   => true,
                'message' => $e->getMessage(),
                'trace'   => collect($e->getTrace())->map(fn($t) => data_get($t, 'function'))->implode(' → '),
            ], 500);
        }
    }

    public function showCart()
    {
        $userId = Auth::id();
        $cart = Cart::with('items.product')->where('user_id', $userId)->first();
        $cartItems = $cart ? $cart->items : collect();
        return view('users.cart', compact('cartItems'));
    }

    public function updateQuantity(Request $request, $id, $action)
    {
        $item = CartItem::findOrFail($id);
        if ($action === 'increment') {
            $item->quantity += 1;
        } elseif ($action === 'decrement' && $item->quantity > 1) {
            $item->quantity -= 1;
        } elseif ($action === 'decrement' && $item->quantity == 1) {
            $item->delete();
            return back();
        }
        $item->save();
        return back();
    }
}
