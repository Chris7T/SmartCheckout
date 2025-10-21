<?php

namespace App\Services;

use App\Models\Order;
use App\Repositories\OrderRepository;

class OrderUpdateService
{
    public function __construct(
        private OrderRepository $orderRepository,
        private OrderGetDetailService $getDetailService
    ) {}

    public function execute(int $id, array $data): Order
    {
        $this->getDetailService->execute($id);

        $this->orderRepository->update($id, $data);

        return $this->orderRepository->findById($id);
    }
}
