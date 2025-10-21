<?php

namespace App\Http\Controllers\Customer;

use App\Exceptions\CustomerNotFoundException;
use App\Http\Controllers\Controller;
use App\Http\Resources\CustomerResource;
use App\Services\CustomerGetByIdService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class GetCustomerController extends Controller
{
    public function __construct(
        private CustomerGetByIdService $getByIdService
    ) {}

    public function __invoke(int $id): JsonResponse
    {
        try {
            $customer = $this->getByIdService->execute($id);

            return response()->json(
                new CustomerResource($customer)
            );

        } catch (CustomerNotFoundException $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], $e->getCode());

        } catch (Exception $e) {
            Log::error(self::class, [
                'customer_id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'message' => 'An error occurred while retrieving customer. Please try again later.'
            ], 500);
        }
    }
}
