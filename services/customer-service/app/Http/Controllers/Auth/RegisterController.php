<?php

namespace App\Http\Controllers\Auth;

use App\Exceptions\CustomerEmailAlreadyExistsException;
use App\Http\Controllers\Controller;
use App\Http\Requests\CustomerRegisterRequest;
use App\Http\Resources\CustomerResource;
use App\Services\Customer\CustomerRegisterService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class RegisterController extends Controller
{
    public function __construct(
        private CustomerRegisterService $registerService
    ) {}

    public function __invoke(CustomerRegisterRequest $request): JsonResponse
    {
        try {
            $customer = $this->registerService->execute($request->validated());

            return response()->json(
                new CustomerResource($customer),
                201
            );

        } catch (CustomerEmailAlreadyExistsException $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'errors' => [
                    'email' => ['The email has already been taken.']
                ]
            ], $e->getCode());

        } catch (Exception $e) {
            Log::error(self::class, [
                'email' => $request->email,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'message' => 'An error occurred during registration. Please try again later.'
            ], 500);
        }
    }
}
