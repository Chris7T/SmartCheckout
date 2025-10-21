<?php

namespace Database\Seeders;

use App\Models\Customer;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Customer::factory()->create([
            'name' => 'Test Customer',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
            'type_id' => 1,
        ]);
    }
}
