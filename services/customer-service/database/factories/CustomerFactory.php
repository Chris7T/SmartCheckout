<?php

namespace Database\Factories;

use App\Enums\CustomerTypeEnum;
use App\Models\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

class CustomerFactory extends Factory
{
    protected $model = Customer::class;

    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'password' => Hash::make('password'),
            'type_id' => fake()->randomElement([CustomerTypeEnum::CLIENT->value, CustomerTypeEnum::EMPLOYEE->value]),
            'email_verified_at' => now(),
        ];
    }

    public function client(): static
    {
        return $this->state(fn (array $attributes) => [
            'type_id' => CustomerTypeEnum::CLIENT->value,
        ]);
    }

    public function employee(): static
    {
        return $this->state(fn (array $attributes) => [
            'type_id' => CustomerTypeEnum::EMPLOYEE->value,
        ]);
    }

    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
