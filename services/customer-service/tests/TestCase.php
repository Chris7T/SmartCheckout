<?php

namespace Tests;

use App\Models\Customer;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Cache;
use Tymon\JWTAuth\Facades\JWTAuth;

abstract class TestCase extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        Cache::flush();
    }

    protected function createTokenForCustomer(Customer $customer): string
    {
        return JWTAuth::fromUser($customer);
    }

    protected function actingAsCustomer(Customer $customer): static
    {
        $token = $this->createTokenForCustomer($customer);
        
        $this->withHeader('Authorization', 'Bearer ' . $token);
        $this->withHeader('X-Customer-Id', $customer->id);
        
        \Illuminate\Support\Facades\Auth::setUser($customer);
        
        return $this;
    }
}
