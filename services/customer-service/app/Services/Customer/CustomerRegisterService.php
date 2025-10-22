<?php

namespace App\Services\Customer;

use App\Exceptions\CustomerEmailAlreadyExistsException;
use App\Models\Customer;
use App\Repositories\CustomerRepository;
use Illuminate\Support\Facades\Hash;

class CustomerRegisterService
{
    public function __construct(
        private CustomerRepository $customerRepository,
        private CustomerGetByEmailService $getByEmailService
    ) {}

    public function execute(array $data): Customer
    {
        if ($this->getByEmailService->execute($data['email'])) {
            throw new CustomerEmailAlreadyExistsException($data['email']);
        }

        $data['password'] = Hash::make($data['password']);
        
        return $this->customerRepository->create($data);
    }
}
