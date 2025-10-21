<?php

namespace App\Services;

use App\Repositories\OrderRepository;

class OrderDeleteService
{
    public function __construct(
        private OrderRepository $orderRepository,
        private OrderGetDetailService $getDetailService
    ) {}

    public function execute(int $id): bool
    {
        $this->getDetailService->execute($id);

        return $this->orderRepository->delete($id);
    }
}
