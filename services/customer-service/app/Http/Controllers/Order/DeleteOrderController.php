<?php

namespace App\Http\Controllers\Order;

use App\Exceptions\OrderNotFoundException;
use App\Http\Controllers\Controller;
use App\Services\OrderDeleteService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class DeleteOrderController extends Controller
{
    public function __construct(
        private OrderDeleteService $deleteService
    ) {}

    public function __invoke(int $id): JsonResponse
    {
        try {
            $this->deleteService->execute($id);

            return response()->json(null, 204);

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
                'message' => 'An error occurred while deleting order. Please try again later.'
            ], 500);
        }
    }
}
