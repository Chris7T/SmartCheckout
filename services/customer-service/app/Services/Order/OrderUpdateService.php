<?php

namespace App\Services\Order;

use App\Models\Order;
use App\Repositories\OrderRepository;

class OrderUpdateService
{
    public function __construct(
        private OrderRepository $orderRepository,
        private OrderGetDetailService $getDetailService,
        private OrderCleanCacheService $orderCleanCacheService
    ) {}

    public function execute(int $id, array $data): Order
    {
        $this->getDetailService->execute($id);

        $this->orderRepository->update($id, $data);

        $this->orderCleanCacheService->execute($id);

        return $this->getDetailService->execute($id);
    }
}
