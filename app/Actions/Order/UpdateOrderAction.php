<?php

namespace App\Actions\Order;

use App\Repositories\Orders\OrderRepository;

class UpdateOrderAction
{
    protected $repo;
    public function __construct(OrderRepository $repo)
    {
        $this->repo = $repo;
    }
    public function execute($id, array $data)
    {
        return $this->repo->update($id, $data);
    }
}
