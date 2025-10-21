<?php

namespace App\Services;

use App\Models\Customer;
use App\Repositories\CustomerRepository;

class CustomerGetByEmailService
{
    public function __construct(
        private CustomerRepository $customerRepository
    ) {}

    public function execute(string $email): ?Customer
    {
        return $this->customerRepository->findByEmail($email);
    }
}
