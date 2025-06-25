<?php

namespace App\Repositories\Orders;

use App\Models\Order;

class OrderRepository
{
    public function allForUser($userId)
    {
        return Order::where('user_id', $userId)->latest()->get();
    }

    public function find($id)
    {
        return Order::findOrFail($id);
    }

    public function create(array $data)
    {
        return Order::create($data);
    }

    public function update($id, array $data)
    {
        $order = Order::findOrFail($id);
        $order->update($data);
        return $order;
    }

    public function delete($id)
    {
        return Order::destroy($id);
    }
}
