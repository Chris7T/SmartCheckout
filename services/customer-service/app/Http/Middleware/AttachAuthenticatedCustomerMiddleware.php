<?php

namespace App\Http\Middleware;

use App\Services\Customer\CustomerGetByIdService;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AttachAuthenticatedCustomerMiddleware
{
    public function __construct(
        private CustomerGetByIdService $getByIdService
    ) {}

    public function handle(Request $request, Closure $next): Response
    {
        try {
            $customerId = $request->header('X-Customer-Id');
            $customer = $this->getByIdService->execute((int) $customerId);
            Auth::setUser($customer);
            
            return $next($request);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Unauthorized.'], 401);
        }
    }
}
