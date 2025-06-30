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
        $productId = $request->id;
        if (Auth::check()) {
            $userId = Auth::id();
            $cart = Cart::firstOrCreate(['user_id' => $userId]);
            $item = CartItem::firstOrNew([
                'cart_id' => $cart->id,
                'product_id' => $productId
            ]);
            $item->quantity = $item->quantity + 1;
            $item->save();
        } else {
            // Guest: use session
            $cart = session()->get('cart', []);
            if (isset($cart[$productId])) {
                $cart[$productId]['quantity'] += 1;
            } else {
                $product = Product::findOrFail($productId);
                $cart[$productId] = [
                    'id' => $product->id,
                    'name' => $product->name,
                    'price' => $product->price,
                    'quantity' => 1,
                    'image' => $product->image,
                ];
            }
            session(['cart' => $cart]);
        }
        return response()->json(['success' => true]);
    }

    public function getCart()
    {
        if (Auth::check()) {
            $cart = Cart::with('items.product')
                        ->where('user_id', Auth::id())
                        ->first();
            $items = [];
            $total = 0;
            if ($cart) {
                foreach ($cart->items as $item) {
                    if (! $item->product) continue;
                    $items[] = [
                        'id'       => $item->product_id,
                        'name'     => $item->product->name,
                        'price'    => $item->product->price,
                        'quantity' => $item->quantity,
                    ];
                    $total += $item->product->price * $item->quantity;
                }
            }
        } else {
            $cart = session('cart', []);
            $items = array_values($cart);
            $total = 0;
            foreach ($items as $item) {
                $total += $item['price'] * $item['quantity'];
            }
        }
        return response()->json(['cart' => $items, 'total' => $total]);
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

    public function redeem($id)
    {
        $user = Auth::user();
        $item = CartItem::with('product')->findOrFail($id);
        $product = $item->product ? \App\Models\Product::find($item->product->id) : null;
        // Check product existence, offer pool, and not expired
        if (!$product || !$product->is_offer_pool || (isset($product->expires_at) && $product->expires_at < now())) {
            $item->delete();
            return back()->with('error', 'This product is not eligible for reward redemption. It may have been removed from the offer pool or expired.');
        }
        if ($item->quantity > 1) {
            return back()->with('error', 'You can only redeem one of this offer pool item.');
        }
        $requiredPoints = $product->reward_points ?? $product->price;
        if ($requiredPoints <= 0) {
            return back()->with('error', 'Invalid reward point value for this product.');
        }
        // Prevent redeeming more points than owned
        if ($user->reward_points < $requiredPoints) {
            return back()->with('error', 'Not enough reward points to redeem this item.');
        }
        // Prevent simultaneous redemption conflict (lock row)
        \DB::transaction(function () use ($user, $item, $requiredPoints, $product) {
            $lockedProduct = \App\Models\Product::where('id', $product->id)->lockForUpdate()->first();
            if ($lockedProduct->stock <= 0) {
                throw new \Exception('This product is out of stock.');
            }
            $lockedProduct->stock -= 1;
            $lockedProduct->save();
            $user->reward_points = max(0, $user->reward_points - $requiredPoints);
            $user->save();
            $item->delete();
        });
        return back()->with('success', 'Product redeemed successfully using reward points!');
    }

    public function remove($id)
    {
        $item = CartItem::findOrFail($id);
        $item->delete();
        return back()->with('success', 'Item removed from cart.');
    }
}
