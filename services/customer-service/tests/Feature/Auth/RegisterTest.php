<?php

namespace Tests\Feature\Auth;

use App\Models\Customer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    use RefreshDatabase;

    public function test_customer_can_register_successfully(): void
    {
        $response = $this->postJson('/api/auth/register', [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'password123',
            'type_id' => 1,
        ]);

        $response->assertStatus(201)
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
                'name' => 'John Doe',
                'email' => 'john@example.com',
                'type_id' => 1,
                'type' => 'client',
            ]);

        $this->assertDatabaseHas('customers', [
            'email' => 'john@example.com',
            'type_id' => 1,
        ]);
    }

    public function test_employee_can_register_successfully(): void
    {
        $response = $this->postJson('/api/auth/register', [
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => 'password123',
            'type_id' => 2,
        ]);

        $response->assertStatus(201)
            ->assertJson([
                'type' => 'employee',
            ]);
    }

    public function test_cannot_register_with_duplicate_email(): void
    {
        Customer::factory()->create([
            'email' => 'existing@example.com',
        ]);

        $response = $this->postJson('/api/auth/register', [
            'name' => 'John Doe',
            'email' => 'existing@example.com',
            'password' => 'password123',
            'type_id' => 1,
        ]);

        $response->assertStatus(422)
            ->assertJson([
                'message' => 'Email already exists.',
                'errors' => [
                    'email' => ['The email has already been taken.']
                ]
            ]);
    }

    public function test_registration_requires_valid_data(): void
    {
        $response = $this->postJson('/api/auth/register', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name', 'email', 'password', 'type_id']);
    }

    public function test_registration_requires_valid_email(): void
    {
        $response = $this->postJson('/api/auth/register', [
            'name' => 'John Doe',
            'email' => 'invalid-email',
            'password' => 'password123',
            'type_id' => 1,
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email']);
    }

    public function test_registration_requires_minimum_password_length(): void
    {
        $response = $this->postJson('/api/auth/register', [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'short',
            'type_id' => 1,
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['password']);
    }

    public function test_registration_requires_valid_type_id(): void
    {
        $response = $this->postJson('/api/auth/register', [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'password123',
            'type_id' => 99,
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['type_id']);
    }
}

