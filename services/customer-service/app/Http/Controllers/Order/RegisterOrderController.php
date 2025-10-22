<?php

namespace App\Http\Controllers\Order;

use App\Exceptions\CustomerNotFoundException;
use App\Http\Controllers\Controller;
use App\Http\Requests\OrderStoreRequest;
use App\Http\Resources\OrderResource;
use App\Services\Order\OrderRegisterService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class RegisterOrderController extends Controller
{
    public function __construct(
        private OrderRegisterService $registerService
    ) {}

    public function __invoke(OrderStoreRequest $request): JsonResponse
    {
        try {
            $order = $this->registerService->execute($request->validated());

            return response()->json(
                new OrderResource($order),
                201
            );

        } catch (CustomerNotFoundException $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], $e->getCode());

        } catch (Exception $e) {
            Log::error(self::class, [
                'customer_id' => auth()->id(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'message' => 'An error occurred while creating order. Please try again later.'
            ], 500);
        }
    }
}
