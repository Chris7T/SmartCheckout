<?php

namespace App\Services;

use App\Repositories\OrderRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class OrderListService
{
    public function __construct(
        private OrderRepository $orderRepository
    ) {}

    public function execute(int $perPage = 15): LengthAwarePaginator
    {
        return $this->orderRepository->getAll($perPage);
    }
}
