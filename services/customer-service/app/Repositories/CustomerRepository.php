<?php

namespace App\Repositories;

use App\Models\Customer;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class CustomerRepository
{
    public function create(array $data): Customer
    {
        return Customer::create($data);
    }

    public function findById(int $id): ?Customer
    {
        return Customer::find($id);
    }

    public function findByEmail(string $email): ?Customer
    {
        return Customer::where('email', $email)->first();
    }

    public function update(int $id, array $data): bool
    {
        return Customer::find($id)?->update($data) ?? false;
    }

    public function delete(int $id): bool
    {
        return Customer::find($id)?->delete() ?? false;
    }

    public function getAll(int $perPage = 15): LengthAwarePaginator
    {
        return Customer::paginate($perPage);
    }
}
