<?php

namespace Tests\Feature\Order;

use App\Models\Cart;
use App\Models\Customer;
use App\Models\Order;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DeleteOrderTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_delete_order(): void
    {
        $customer = Customer::factory()->create();
        
        $order = Order::factory()->create(['customer_id' => $customer->id]);

        $response = $this->actingAsCustomer($customer)
            ->deleteJson("/api/orders/{$order->id}");

        $response->assertStatus(204)
            ->assertNoContent();

        $this->assertSoftDeleted('orders', [
            'id' => $order->id,
        ]);
    }

    public function test_deleting_order_soft_deletes_cart_items(): void
    {
        $customer = Customer::factory()->create();
        
        $order = Order::factory()->create(['customer_id' => $customer->id]);
        $cart = Cart::factory()->create(['order_id' => $order->id]);

        $response = $this->actingAsCustomer($customer)
            ->deleteJson("/api/orders/{$order->id}");

        $response->assertStatus(204);

        $this->assertSoftDeleted('orders', ['id' => $order->id]);
        $this->assertSoftDeleted('carts', ['id' => $cart->id]);
    }

    public function test_returns_404_for_nonexistent_order(): void
    {
        $customer = Customer::factory()->create();
        
        $response = $this->actingAsCustomer($customer)
            ->deleteJson('/api/orders/999');

        $response->assertStatus(404)
            ->assertJson([
                'message' => 'Order not found.',
            ]);
    }

    public function test_cannot_delete_already_deleted_order(): void
    {
        $customer = Customer::factory()->create();
        
        $order = Order::factory()->create(['customer_id' => $customer->id]);
        $order->delete();

        $response = $this->actingAsCustomer($customer)
            ->deleteJson("/api/orders/{$order->id}");

        $response->assertStatus(404);
    }

    public function test_can_delete_order_with_different_statuses(): void
    {
        $customer = Customer::factory()->create();
        
        $pendingOrder = Order::factory()->pending()->create(['customer_id' => $customer->id]);
        $completedOrder = Order::factory()->completed()->create(['customer_id' => $customer->id]);

        $response1 = $this->actingAsCustomer($customer)
            ->deleteJson("/api/orders/{$pendingOrder->id}");
        $response2 = $this->actingAsCustomer($customer)
            ->deleteJson("/api/orders/{$completedOrder->id}");

        $response1->assertStatus(204);
        $response2->assertStatus(204);

        $this->assertSoftDeleted('orders', ['id' => $pendingOrder->id]);
        $this->assertSoftDeleted('orders', ['id' => $completedOrder->id]);
    }
}

