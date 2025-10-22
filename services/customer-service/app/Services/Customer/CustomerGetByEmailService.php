<?php

namespace App\Services\Customer;

use App\Enums\CacheTimeEnum;
use App\Models\Customer;
use App\Repositories\CustomerRepository;
use Illuminate\Support\Facades\Cache;

class CustomerGetByEmailService
{
    public function __construct(
        private CustomerRepository $customerRepository
    ) {}

    public function execute(string $email): ?Customer
    {
        $customerData = Cache::remember(
            "customer:email:{$email}",
            CacheTimeEnum::ONE_HOUR->value,
            fn() => $this->customerRepository->findByEmail($email)?->toArray()
        );

        if (!$customerData) {
            return null;
        }

        return Customer::hydrate([$customerData])->first();
    }
}
