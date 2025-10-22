<?php

namespace App\Http\Controllers\Order;

use App\Exceptions\OrderNotFoundException;
use App\Http\Controllers\Controller;
use App\Http\Requests\OrderUpdateRequest;
use App\Http\Resources\OrderResource;
use App\Services\Order\OrderUpdateService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class UpdateOrderController extends Controller
{
    public function __construct(
        private OrderUpdateService $updateService
    ) {}

    public function __invoke(int $id, OrderUpdateRequest $request): JsonResponse
    {
        try {
            $order = $this->updateService->execute($id, $request->validated());

            return response()->json(
                new OrderResource($order)
            );

        } catch (OrderNotFoundException $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], $e->getCode());

        } catch (Exception $e) {
            Log::error(self::class, [
                'order_id' => $id,
                'data' => $request->validated(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'message' => 'An error occurred while updating order. Please try again later.'
            ], 500);
        }
    }
}
