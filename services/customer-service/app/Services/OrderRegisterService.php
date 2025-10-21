<?php

namespace App\Services;

use App\Enums\OrderStatusEnum;
use App\Enums\PaymentStatusEnum;
use App\Models\Order;
use App\Repositories\CartRepository;
use App\Repositories\OrderRepository;

class OrderRegisterService
{
    public function __construct(
        private OrderRepository $orderRepository,
        private CartRepository $cartRepository,
        private CustomerGetByIdService $getCustomerService
    ) {}

    public function execute(array $data): Order
    {
        $this->getCustomerService->execute($data['customer_id']);

        $order = $this->orderRepository->create([
            'customer_id' => $data['customer_id'],
            'value' => $data['value'],
            'liquid_value' => $data['liquid_value'],
            'status_id' => $data['status_id'] ?? OrderStatusEnum::PENDING->value,
            'payment_status_id' => $data['payment_status_id'] ?? PaymentStatusEnum::PENDING->value,
        ]);

        if (isset($data['carts']) && is_array($data['carts'])) {
            foreach ($data['carts'] as $cart) {
                $this->cartRepository->create([
                    'order_id' => $order->id,
                    'product_id' => $cart['product_id'],
                    'value' => $cart['value'],
                ]);
            }
        }

        return $this->orderRepository->findById($order->id);
    }
}
