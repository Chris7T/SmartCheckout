<?php

namespace App\Repositories;

use App\Models\Order;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class OrderRepository
{
    public function create(array $data): Order
    {
        return Order::create($data);
    }

    public function findById(int $id): ?Order
    {
        return Order::with(['customer', 'carts'])->find($id);
    }

    public function update(int $id, array $data): bool
    {
        return Order::find($id)?->update($data) ?? false;
    }

    public function delete(int $id): bool
    {
        return Order::find($id)?->delete() ?? false;
    }

    public function getAll(int $perPage = 15): LengthAwarePaginator
    {
        return Order::with(['customer', 'carts'])
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }

    public function getByCustomerId(int $customerId, int $perPage = 15): LengthAwarePaginator
    {
        return Order::with('carts')
            ->where('customer_id', $customerId)
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }
}
