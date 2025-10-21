<?php

namespace Tests\Feature\Order;

use App\Models\Cart;
use App\Models\Customer;
use App\Models\Order;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GetOrderDetailTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_get_order_detail_with_carts(): void
    {
        $customer = Customer::factory()->create();
        
        $order = Order::factory()->create(['customer_id' => $customer->id]);
        Cart::factory()->count(3)->create(['order_id' => $order->id]);

        $response = $this->actingAsCustomer($customer)
            ->getJson("/api/orders/{$order->id}");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'id',
                'customer_id',
                'customer',
                'value',
                'liquid_value',
                'status_id',
                'status',
                'payment_status_id',
                'payment_status',
                'carts' => [
                    '*' => [
                        'id',
                        'order_id',
                        'product_id',
                        'value',
                        'created_at',
                    ]
                ],
                'created_at',
                'updated_at',
            ])
            ->assertJson([
                'id' => $order->id,
            ]);

        $this->assertCount(3, $response->json('carts'));
    }

    public function test_returns_404_for_nonexistent_order(): void
    {
        $customer = Customer::factory()->create();
        
        $response = $this->withHeader('X-Customer-Id', $customer->id)
            ->getJson('/api/orders/999');

        $response->assertStatus(404)
            ->assertJson([
                'message' => 'Order not found.',
            ]);
    }

    public function test_order_status_is_human_readable(): void
    {
        $customer = Customer::factory()->create();
        
        $order = Order::factory()->completed()->create(['customer_id' => $customer->id]);

        $response = $this->actingAsCustomer($customer)
            ->getJson("/api/orders/{$order->id}");

        $response->assertStatus(200)
            ->assertJson([
                'status_id' => 3,
                'status' => 'completed',
            ]);
    }

    public function test_payment_status_is_human_readable(): void
    {
        $customer = Customer::factory()->create();
        
        $order = Order::factory()->create([
            'customer_id' => $customer->id,
            'payment_status_id' => 2
        ]);

        $response = $this->actingAsCustomer($customer)
            ->getJson("/api/orders/{$order->id}");

        $response->assertStatus(200)
            ->assertJson([
                'payment_status_id' => 2,
                'payment_status' => 'approved',
            ]);
    }
}

