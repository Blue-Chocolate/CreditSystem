<?php

namespace App\Actions\Order;

use App\Models\Order;

class UpdateOrderAction
{
    public function execute($id, $data)
    {
        $order = Order::findOrFail($id);
        $order->update($data);
    }
}
