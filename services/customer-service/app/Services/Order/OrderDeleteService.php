<?php

namespace App\Services\Order;

use App\Repositories\OrderRepository;

class OrderDeleteService
{
    public function __construct(
        private OrderRepository $orderRepository,
        private OrderGetDetailService $getDetailService,
        private OrderCleanCacheService $orderCleanCacheService
    ) {}

    public function execute(int $id): bool
    {
        $this->getDetailService->execute($id);

        $result = $this->orderRepository->delete($id);

        $this->orderCleanCacheService->execute($id);

        return $result;
    }
}
