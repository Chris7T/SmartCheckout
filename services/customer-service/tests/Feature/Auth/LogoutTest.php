<?php

namespace Tests\Feature\Auth;

use App\Models\Customer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LogoutTest extends TestCase
{
    use RefreshDatabase;

    public function test_customer_can_logout(): void
    {
        $customer = Customer::factory()->create();

        $response = $this->actingAsCustomer($customer)
            ->postJson('/api/auth/logout');

        $response->assertStatus(204)
            ->assertNoContent();
    }

    public function test_logout_requires_token(): void
    {
        $customer = Customer::factory()->create();

        $response = $this->withHeader('X-Customer-Id', $customer->id)
            ->postJson('/api/auth/logout');

        $response->assertStatus(400)
            ->assertJson([
                'message' => 'Token not provided.'
            ]);
    }
}

