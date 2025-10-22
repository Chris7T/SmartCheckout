<?php

namespace App\Http\Controllers\Order;

use App\Http\Controllers\Controller;
use App\Http\Resources\OrderResource;
use App\Services\Order\OrderListService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ListOrdersController extends Controller
{
    public function __construct(
        private OrderListService $listService
    ) {}

    public function __invoke(Request $request): JsonResponse
    {
        try {
            $perPage = $request->get('per_page', 15);
            $orders = $this->listService->execute(auth()->id(), $perPage);

            return response()->json([
                'data' => OrderResource::collection($orders->items()),
                'meta' => [
                    'current_page' => $orders->currentPage(),
                    'last_page' => $orders->lastPage(),
                    'per_page' => $orders->perPage(),
                    'total' => $orders->total(),
                ]
            ]);

        } catch (Exception $e) {
            Log::error(self::class, [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'message' => 'An error occurred while listing orders. Please try again later.'
            ], 500);
        }
    }
}
