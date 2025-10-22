<?php

namespace App\Services\Customer;

use App\Enums\CacheTimeEnum;
use App\Exceptions\CustomerNotFoundException;
use App\Models\Customer;
use App\Repositories\CustomerRepository;
use Illuminate\Support\Facades\Cache;

class CustomerGetByIdService
{
    public function __construct(
        private CustomerRepository $customerRepository
    ) {}

    public function execute(int $id): Customer
    {
        $customerData = Cache::remember(
            "customer:{$id}",
            CacheTimeEnum::ONE_HOUR->value,
            fn() => $this->customerRepository->findById($id)?->toArray()
        );

        if (!$customerData) {
            throw new CustomerNotFoundException();
        }

        return Customer::hydrate([$customerData])->first();
    }
}
