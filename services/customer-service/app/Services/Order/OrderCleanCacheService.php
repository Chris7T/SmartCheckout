<?php

namespace App\Services\Order;

use Illuminate\Support\Facades\Cache;

class OrderCleanCacheService
{
    public function execute(int $orderId): void
    {
        Cache::forget("order:{$orderId}");
        Cache::forget("order:{$orderId}:carts");
    }
}
