<?php

namespace Tests\Feature\Customer;

use App\Models\Customer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GetCustomerTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_get_customer_by_id(): void
    {
        $customer = Customer::factory()->create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'type_id' => 1,
        ]);

        $response = $this->actingAsCustomer($customer)
            ->getJson("/api/customers/{$customer->id}");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'id',
                'name',
                'email',
                'type_id',
                'type',
                'created_at',
                'updated_at',
            ])
            ->assertJson([
                'id' => $customer->id,
                'name' => 'John Doe',
                'email' => 'john@example.com',
                'type_id' => 1,
                'type' => 'Client',
            ]);
    }

    public function test_returns_404_for_nonexistent_customer(): void
    {
        $customer = Customer::factory()->create();
        
        $response = $this->actingAsCustomer($customer)
            ->getJson('/api/customers/999');

        $response->assertStatus(404)
            ->assertJson([
                'message' => 'Customer not found.',
            ]);
    }

    public function test_can_get_employee_customer(): void
    {
        $employee = Customer::factory()->employee()->create([
            'name' => 'Admin User',
        ]);

        $response = $this->actingAsCustomer($employee)
            ->getJson("/api/customers/{$employee->id}");

        $response->assertStatus(200)
            ->assertJson([
                'type_id' => 2,
                'type' => 'Employee',
            ]);
    }
}

