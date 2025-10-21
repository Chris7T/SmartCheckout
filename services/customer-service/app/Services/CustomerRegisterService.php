<?php

namespace App\Services;

use App\Exceptions\CustomerEmailAlreadyExistsException;
use App\Models\Customer;
use App\Repositories\CustomerRepository;
use Illuminate\Support\Facades\Hash;

class CustomerRegisterService
{
    public function __construct(
        private CustomerRepository $customerRepository
    ) {}

    public function execute(array $data): Customer
    {
        if ($this->customerRepository->findByEmail($data['email'])) {
            throw new CustomerEmailAlreadyExistsException($data['email']);
        }

        $data['password'] = Hash::make($data['password']);
        
        return $this->customerRepository->create($data);
    }
}
