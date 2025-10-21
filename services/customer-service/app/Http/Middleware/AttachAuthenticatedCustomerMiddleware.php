<?php

namespace App\Http\Middleware;

use App\Repositories\CustomerRepository;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AttachAuthenticatedCustomerMiddleware
{
    public function __construct(
        private CustomerRepository $customerRepository
    ) {}

    public function handle(Request $request, Closure $next): Response
    {
        $customerId = $request->header('X-Customer-Id');
        
        if ($customerId) {
            $customer = $this->customerRepository->findById((int) $customerId);
            
            if ($customer) {
                Auth::setUser($customer);
            }
        }
        
        return $next($request);
    }
}
