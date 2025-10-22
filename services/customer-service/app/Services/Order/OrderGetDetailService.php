<?php

namespace App\Services\Order;

use App\Enums\CacheTimeEnum;
use App\Exceptions\OrderNotFoundException;
use App\Models\Cart;
use App\Models\Order;
use App\Repositories\CartRepository;
use App\Repositories\OrderRepository;
use Illuminate\Support\Facades\Cache;

class OrderGetDetailService
{
    public function __construct(
        private OrderRepository $orderRepository,
        private CartRepository $cartRepository
    ) {}

    public function execute(int $id): Order
    {
        $orderData = Cache::remember(
            "order:{$id}",
            CacheTimeEnum::ONE_HOUR->value,
            fn() => $this->orderRepository->findById($id)?->toArray()
        );

        if (!$orderData) {
            throw new OrderNotFoundException();
        }

        $cartsData = Cache::remember(
            "order:{$id}:carts",
            CacheTimeEnum::ONE_HOUR->value,
            fn() => $this->cartRepository->getByOrderId($id)->toArray()
        );

        $order = Order::hydrate([$orderData])->first();
        $carts = Cart::hydrate($cartsData);

        $order->setRelation('carts', $carts);

        return $order;
    }
}
