<?php

namespace App\Repositories;

use App\Models\Cart;
use Illuminate\Database\Eloquent\Collection;

class CartRepository
{
    public function create(array $data): Cart
    {
        return Cart::create($data);
    }

    public function findById(int $id): ?Cart
    {
        return Cart::find($id);
    }

    public function update(int $id, array $data): bool
    {
        return Cart::find($id)?->update($data) ?? false;
    }

    public function delete(int $id): bool
    {
        return Cart::find($id)?->delete() ?? false;
    }

    public function getByOrderId(int $orderId): Collection
    {
        return Cart::where('order_id', $orderId)->get();
    }

    public function deleteByOrderId(int $orderId): int
    {
        return Cart::where('order_id', $orderId)->delete();
    }
}
