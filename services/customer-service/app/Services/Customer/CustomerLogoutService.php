<?php

namespace App\Services\Customer;

use App\Exceptions\TokenNotProvidedException;
use Tymon\JWTAuth\Facades\JWTAuth;

class CustomerLogoutService
{
    public function execute(?string $token): void
    {
        if (!$token) {
            throw new TokenNotProvidedException();
        }

        JWTAuth::setToken($token)->invalidate();
    }
}
