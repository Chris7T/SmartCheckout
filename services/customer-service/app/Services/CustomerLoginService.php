<?php

namespace App\Services;

use App\Exceptions\InvalidCredentialsException;
use App\Repositories\CustomerRepository;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;

class CustomerLoginService
{
    public function __construct(
        private CustomerRepository $customerRepository
    ) {}

    public function execute(string $email, string $password): array
    {
        $customer = $this->customerRepository->findByEmail($email);

        if (!$customer || !Hash::check($password, $customer->password)) {
            throw new InvalidCredentialsException();
        }

        $token = JWTAuth::fromUser($customer);

        return [
            'customer' => $customer,
            'token' => $token,
        ];
    }
}
