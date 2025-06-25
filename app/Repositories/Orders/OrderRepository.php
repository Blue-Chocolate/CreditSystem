<?php

namespace App\Repositories\Orders;

use App\Models\Order;

class OrderRepository
{
    public function paginateForUser($userId, $perPage = 10)
    {
        return Order::with('items.product')
                    ->where('user_id', $userId)
                    ->latest()
                    ->paginate($perPage);
    }

    public function find($id)
    {
        return Order::with('items.product')->findOrFail($id);
    }
}
