<?php

namespace Database\Factories;

use App\Models\Cart;
use App\Models\Order;
use Illuminate\Database\Eloquent\Factories\Factory;

class CartFactory extends Factory
{
    protected $model = Cart::class;

    public function definition(): array
    {
        return [
            'order_id' => Order::factory(),
            'product_id' => fake()->numberBetween(1, 100),
            'value' => fake()->randomFloat(2, 5, 500),
        ];
    }
}
