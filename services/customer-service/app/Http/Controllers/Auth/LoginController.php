<?php

namespace App\Http\Controllers\Auth;

use App\Exceptions\InvalidCredentialsException;
use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Resources\CustomerResource;
use App\Services\Customer\CustomerLoginService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class LoginController extends Controller
{
    public function __construct(
        private CustomerLoginService $loginService
    ) {}

    public function __invoke(LoginRequest $request): JsonResponse
    {
        try {
            $result = $this->loginService->execute($request->email, $request->password);

            return response()->json([
                'customer' => new CustomerResource($result['customer']),
                'token' => $result['token'],
            ]);

        } catch (InvalidCredentialsException $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], $e->getCode());

        } catch (Exception $e) {
            Log::error(self::class, [
                'email' => $request->email,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'message' => 'An error occurred during login. Please try again later.'
            ], 500);
        }
    }
}
