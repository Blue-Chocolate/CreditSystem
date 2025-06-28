<?php

namespace App\Actions\Order;

use App\Models\Order;

class CreateOrderAction
{
    public function execute($user, $cart, $data)
    {
        $total = $data['total'];
        $paymentMethod = $data['payment_method'];

        if ($paymentMethod === 'cash' && $user->credit_balance < $total) {
            return ['success' => false, 'message' => 'Insufficient balance.'];
        }

        if ($paymentMethod === 'credit_points' && $user->credit_points < $total) {
            return ['success' => false, 'message' => 'Insufficient credit points.'];
        }

        if ($paymentMethod === 'reward_points') {
            $allInOfferPool = $cart->items->every(fn($item) => $item->product->is_offer_pool);
            if (!$allInOfferPool) {
                return ['success' => false, 'message' => 'All items must be in offer pool to use reward points.'];
            }
            if ($user->reward_points < $total) {
                return ['success' => false, 'message' => 'Insufficient reward points.'];
            }
        }

        // Check stock before proceeding
        foreach ($cart->items as $cartItem) {
            $product = $cartItem->product;
            if ($product && $product->stock !== null && $cartItem->quantity > $product->stock) {
                return ['success' => false, 'message' => "Not enough stock for {$product->name}. Available: {$product->stock}, requested: {$cartItem->quantity}"];
            }
        }

        // Deduct based on payment method
        match ($paymentMethod) {
            'cash' => $user->credit_balance -= $total,
            'credit_points' => $user->credit_points -= $total,
            'reward_points' => $user->reward_points -= $total,
        };

        // Reward logic
        $creditPointsEarned = (int) $total;
        $rewardPointsEarned = intdiv($creditPointsEarned, 10);

        if ($cart->items->contains(fn($item) => $item->product->is_offer_pool)) {
            $user->reward_points += $rewardPointsEarned;
        }

        $user->credit_points += $creditPointsEarned;
        $user->save();

        // Create Order
        $order = Order::create([
            'user_id' => $user->id,
            'total' => $total,
            'payment_method' => $paymentMethod,
            'status' => 'pending',
        ]);

        foreach ($cart->items as $cartItem) {
            // Reduce product stock
            $product = $cartItem->product;
            if ($product && $product->stock !== null) {
                $product->stock = max(0, $product->stock - $cartItem->quantity);
                $product->save();
            }
            $order->items()->create([
                'product_id' => $cartItem->product_id,
                'quantity' => $cartItem->quantity,
                'price' => $cartItem->product->price,
            ]);
        }

        $cart->items()->delete();

        return ['success' => true, 'order' => $order];
    }
}
