<?php

namespace Tests\Feature\Order;

use App\Models\Customer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegisterOrderTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_order_with_carts(): void
    {
        $customer = Customer::factory()->create();

        $response = $this->actingAsCustomer($customer)
            ->postJson('/api/orders', [
                'customer_id' => $customer->id,
                'value' => 100.50,
                'liquid_value' => 95.00,
                'carts' => [
                    [
                        'product_id' => 1,
                        'value' => 50.25,
                    ],
                    [
                        'product_id' => 2,
                        'value' => 50.25,
                    ],
                ],
            ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'id',
                'customer_id',
                'value',
                'liquid_value',
                'status_id',
                'status',
                'payment_status_id',
                'payment_status',
                'carts'
            ])
            ->assertJson([
                'customer_id' => $customer->id,
                'value' => '100.50',
                'liquid_value' => '95.00',
                'status_id' => 1,
                'status' => 'Pending',
            ]);

        $this->assertDatabaseHas('orders', [
            'customer_id' => $customer->id,
            'value' => 100.50,
            'liquid_value' => 95.00,
        ]);

        $this->assertDatabaseCount('carts', 2);
    }

    public function test_order_defaults_to_pending_status(): void
    {
        $customer = Customer::factory()->create();

        $response = $this->actingAsCustomer($customer)
            ->postJson('/api/orders', [
                'customer_id' => $customer->id,
                'value' => 100.00,
                'liquid_value' => 100.00,
                'carts' => [
                    ['product_id' => 1, 'value' => 100.00],
                ],
            ]);

        $response->assertStatus(201)
            ->assertJson([
                'status_id' => 1,
                'payment_status_id' => 1,
            ]);
    }

    public function test_cannot_create_order_without_carts(): void
    {
        $customer = Customer::factory()->create();

        $response = $this->actingAsCustomer($customer)
            ->postJson('/api/orders', [
                'customer_id' => $customer->id,
                'value' => 100.00,
                'liquid_value' => 100.00,
                'carts' => [],
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['carts']);
    }

    public function test_cannot_create_order_for_nonexistent_customer(): void
    {
        $customer = Customer::factory()->create();

        $response = $this->actingAsCustomer($customer)
            ->postJson('/api/orders', [
                'customer_id' => 999,
                'value' => 100.00,
                'liquid_value' => 100.00,
                'carts' => [
                    ['product_id' => 1, 'value' => 100.00],
                ],
            ]);

        $response->assertStatus(404)
            ->assertJson([
                'message' => 'Customer not found.'
            ]);
    }

    public function test_order_creation_requires_valid_data(): void
    {
        $customer = Customer::factory()->create();

        $response = $this->actingAsCustomer($customer)
            ->postJson('/api/orders', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['customer_id', 'value', 'liquid_value', 'carts']);
    }

    public function test_cart_items_require_product_id_and_value(): void
    {
        $customer = Customer::factory()->create();

        $response = $this->actingAsCustomer($customer)
            ->postJson('/api/orders', [
                'customer_id' => $customer->id,
                'value' => 100.00,
                'liquid_value' => 100.00,
                'carts' => [
                    ['product_id' => 1],
                ],
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['carts.0.value']);
    }

    public function test_values_must_be_numeric_and_positive(): void
    {
        $customer = Customer::factory()->create();

        $response = $this->actingAsCustomer($customer)
            ->postJson('/api/orders', [
                'customer_id' => $customer->id,
                'value' => -10,
                'liquid_value' => 'invalid',
                'carts' => [
                    ['product_id' => 1, 'value' => -5],
                ],
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['value', 'liquid_value', 'carts.0.value']);
    }
}

