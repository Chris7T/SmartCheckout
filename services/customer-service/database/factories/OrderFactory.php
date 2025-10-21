<?php

namespace Database\Factories;

use App\Enums\OrderStatusEnum;
use App\Enums\PaymentStatusEnum;
use App\Models\Customer;
use App\Models\Order;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    protected $model = Order::class;

    public function definition(): array
    {
        return [
            'customer_id' => Customer::factory(),
            'value' => fake()->randomFloat(2, 10, 1000),
            'liquid_value' => fake()->randomFloat(2, 10, 1000),
            'status_id' => OrderStatusEnum::PENDING->value,
            'payment_status_id' => PaymentStatusEnum::PENDING->value,
        ];
    }

    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'status_id' => OrderStatusEnum::PENDING->value,
        ]);
    }

    public function processing(): static
    {
        return $this->state(fn (array $attributes) => [
            'status_id' => OrderStatusEnum::PROCESSING->value,
        ]);
    }

    public function completed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status_id' => OrderStatusEnum::COMPLETED->value,
        ]);
    }

    public function cancelled(): static
    {
        return $this->state(fn (array $attributes) => [
            'status_id' => OrderStatusEnum::CANCELLED->value,
        ]);
    }

    public function paid(): static
    {
        return $this->state(fn (array $attributes) => [
            'payment_status_id' => PaymentStatusEnum::APPROVED->value,
        ]);
    }
}
