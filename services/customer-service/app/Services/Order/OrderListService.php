<?php

namespace App\Services\Order;

use App\Repositories\OrderRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class OrderListService
{
    public function __construct(
        private OrderRepository $orderRepository
    ) {}

    public function execute(int $customerId, int $perPage = 15): LengthAwarePaginator
    {
        return $this->orderRepository->getByCustomerId($customerId, $perPage);
    }
}
