<?php

namespace Tests\Feature\Order;

use App\Models\Customer;
use App\Models\Order;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ListOrdersTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_list_orders(): void
    {
        $customer = Customer::factory()->create();
        
        Order::factory()->count(3)->create(['customer_id' => $customer->id]);

        $response = $this->actingAsCustomer($customer)
            ->getJson('/api/orders');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'customer_id',
                        'value',
                        'liquid_value',
                        'status_id',
                        'status',
                        'payment_status_id',
                        'payment_status',
                        'created_at',
                        'updated_at',
                    ]
                ],
                'meta' => [
                    'current_page',
                    'last_page',
                    'per_page',
                    'total',
                ]
            ]);

        $this->assertEquals(3, $response->json('meta.total'));
    }

    public function test_can_list_orders_with_pagination(): void
    {
        $customer = Customer::factory()->create();
        
        Order::factory()->count(25)->create(['customer_id' => $customer->id]);

        $response = $this->actingAsCustomer($customer)
            ->getJson('/api/orders?per_page=10');

        $response->assertStatus(200);
        $this->assertEquals(10, count($response->json('data')));
        $this->assertEquals(25, $response->json('meta.total'));
    }

    public function test_orders_are_sorted_by_created_at_desc(): void
    {
        $customer = Customer::factory()->create();
        
        $oldOrder = Order::factory()->create([
            'customer_id' => $customer->id,
            'created_at' => now()->subDays(2),
        ]);
        
        $newOrder = Order::factory()->create([
            'customer_id' => $customer->id,
            'created_at' => now(),
        ]);

        $response = $this->actingAsCustomer($customer)
            ->getJson('/api/orders');

        $response->assertStatus(200);
        $this->assertEquals($newOrder->id, $response->json('data.0.id'));
        $this->assertEquals($oldOrder->id, $response->json('data.1.id'));
    }

    public function test_returns_empty_list_when_no_orders(): void
    {
        $customer = Customer::factory()->create();
        
        $response = $this->actingAsCustomer($customer)
            ->getJson('/api/orders');

        $response->assertStatus(200)
            ->assertJson([
                'data' => [],
                'meta' => [
                    'total' => 0,
                ]
            ]);
    }

    public function test_customer_only_sees_own_orders(): void
    {
        $customerA = Customer::factory()->create(['name' => 'Customer A']);
        $customerB = Customer::factory()->create(['name' => 'Customer B']);
        
        Order::factory()->count(3)->create(['customer_id' => $customerA->id]);
        Order::factory()->count(2)->create(['customer_id' => $customerB->id]);

        $response = $this->actingAsCustomer($customerB)
            ->getJson('/api/orders');

        $response->assertStatus(200);
        $this->assertEquals(2, $response->json('meta.total'));
        
        foreach ($response->json('data') as $order) {
            $this->assertEquals($customerB->id, $order['customer_id']);
        }
    }
}
