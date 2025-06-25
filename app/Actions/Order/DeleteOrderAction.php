<?php

namespace App\Actions\Order;

use App\Repositories\Orders\OrderRepository;

class DeleteOrderAction
{
    protected $repo;
    public function __construct(OrderRepository $repo)
    {
        $this->repo = $repo;
    }
    public function execute($id)
    {
        return $this->repo->delete($id);
    }
}
