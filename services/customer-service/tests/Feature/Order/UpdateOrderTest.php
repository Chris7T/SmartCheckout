<?php

namespace Tests\Feature\Order;

use App\Models\Customer;
use App\Models\Order;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UpdateOrderTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_update_order_status(): void
    {
        $customer = Customer::factory()->create();
        
        $order = Order::factory()->create([
            'customer_id' => $customer->id,
            'status_id' => 1,
        ]);

        $response = $this->actingAsCustomer($customer)
            ->putJson("/api/orders/{$order->id}", [
                'status_id' => 2,
            ]);

        $response->assertStatus(200)
            ->assertJson([
                'id' => $order->id,
                'status_id' => 2,
                'status' => 'Processing',
            ]);

        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'status_id' => 2,
        ]);
    }

    public function test_can_update_payment_status(): void
    {
        $customer = Customer::factory()->create();
        
        $order = Order::factory()->create([
            'customer_id' => $customer->id,
            'payment_status_id' => 1,
        ]);

        $response = $this->actingAsCustomer($customer)
            ->putJson("/api/orders/{$order->id}", [
                'payment_status_id' => 2,
            ]);

        $response->assertStatus(200)
            ->assertJson([
                'payment_status_id' => 2,
                'payment_status' => 'Approved',
            ]);
    }

    public function test_can_update_order_values(): void
    {
        $customer = Customer::factory()->create();
        
        $order = Order::factory()->create([
            'customer_id' => $customer->id,
            'value' => 100.00,
            'liquid_value' => 95.00,
        ]);

        $response = $this->actingAsCustomer($customer)
            ->putJson("/api/orders/{$order->id}", [
                'value' => 150.00,
                'liquid_value' => 140.00,
            ]);

        $response->assertStatus(200)
            ->assertJson([
                'value' => '150.00',
                'liquid_value' => '140.00',
            ]);
    }

    public function test_can_update_multiple_fields_at_once(): void
    {
        $customer = Customer::factory()->create();
        
        $order = Order::factory()->create(['customer_id' => $customer->id]);

        $response = $this->actingAsCustomer($customer)
            ->putJson("/api/orders/{$order->id}", [
                'status_id' => 3,
                'payment_status_id' => 2,
                'value' => 200.00,
                'liquid_value' => 190.00,
            ]);

        $response->assertStatus(200)
            ->assertJson([
                'status_id' => 3,
                'payment_status_id' => 2,
                'value' => '200.00',
                'liquid_value' => '190.00',
            ]);
    }

    public function test_returns_404_for_nonexistent_order(): void
    {
        $customer = Customer::factory()->create();
        
        $response = $this->actingAsCustomer($customer)
            ->putJson('/api/orders/999', [
                'status_id' => 2,
            ]);

        $response->assertStatus(404)
            ->assertJson([
                'message' => 'Order not found.',
            ]);
    }

    public function test_validates_status_id_values(): void
    {
        $customer = Customer::factory()->create();
        
        $order = Order::factory()->create(['customer_id' => $customer->id]);

        $response = $this->actingAsCustomer($customer)
            ->putJson("/api/orders/{$order->id}", [
                'status_id' => 99,
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['status_id']);
    }

    public function test_validates_payment_status_id_values(): void
    {
        $customer = Customer::factory()->create();
        
        $order = Order::factory()->create(['customer_id' => $customer->id]);

        $response = $this->actingAsCustomer($customer)
            ->putJson("/api/orders/{$order->id}", [
                'payment_status_id' => 99,
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['payment_status_id']);
    }

    public function test_validates_numeric_values(): void
    {
        $customer = Customer::factory()->create();
        
        $order = Order::factory()->create(['customer_id' => $customer->id]);

        $response = $this->actingAsCustomer($customer)
            ->putJson("/api/orders/{$order->id}", [
                'value' => 'invalid',
                'liquid_value' => -10,
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['value', 'liquid_value']);
    }

    public function test_can_update_with_empty_body(): void
    {
        $customer = Customer::factory()->create();
        
        $order = Order::factory()->create(['customer_id' => $customer->id]);

        $response = $this->actingAsCustomer($customer)
            ->putJson("/api/orders/{$order->id}", []);

        $response->assertStatus(200);
    }
}

