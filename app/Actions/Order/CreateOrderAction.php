<?php

namespace App\Actions\Order;

use App\Repositories\Orders\OrderRepository;

class CreateOrderAction
{
    protected $repo;
    public function __construct(OrderRepository $repo)
    {
        $this->repo = $repo;
    }
    public function execute(array $data)
    {
        return $this->repo->create($data);
    }
}
