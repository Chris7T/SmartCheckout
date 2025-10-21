<?php

namespace App\Services;

use App\Exceptions\CustomerNotFoundException;
use App\Models\Customer;
use App\Repositories\CustomerRepository;

class CustomerGetByIdService
{
    public function __construct(
        private CustomerRepository $customerRepository
    ) {}

    public function execute(int $id): Customer
    {
        $customer = $this->customerRepository->findById($id);

        if (!$customer) {
            throw new CustomerNotFoundException();
        }

        return $customer;
    }
}
