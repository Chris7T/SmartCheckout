<?php

namespace App\Services;

use App\Exceptions\OrderNotFoundException;
use App\Models\Order;
use App\Repositories\OrderRepository;

class OrderGetDetailService
{
    public function __construct(
        private OrderRepository $orderRepository
    ) {}

    public function execute(int $id): Order
    {
        $order = $this->orderRepository->findById($id);

        if (!$order) {
            throw new OrderNotFoundException();
        }

        return $order;
    }
}
