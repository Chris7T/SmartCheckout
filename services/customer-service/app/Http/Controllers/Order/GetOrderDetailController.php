<?php

namespace App\Http\Controllers\Order;

use App\Exceptions\OrderNotFoundException;
use App\Http\Controllers\Controller;
use App\Http\Resources\OrderResource;
use App\Services\Order\OrderGetDetailService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class GetOrderDetailController extends Controller
{
    public function __construct(
        private OrderGetDetailService $getDetailService
    ) {}

    public function __invoke(int $id): JsonResponse
    {
        try {
            $order = $this->getDetailService->execute($id);

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
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'message' => 'An error occurred while retrieving order. Please try again later.'
            ], 500);
        }
    }
}
