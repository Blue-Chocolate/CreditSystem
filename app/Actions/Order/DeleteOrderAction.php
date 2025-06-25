<?php

namespace App\Actions\Order;

use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class DeleteOrderAction
{
    public function execute($id)
    {
        $order = Order::where('id', $id)
                      ->where('user_id', Auth::id()) // Secure: Only delete user's own order
                      ->firstOrFail();

        $order->delete();
    }
}
