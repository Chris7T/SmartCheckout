<?php

namespace App\Http\Controllers\Auth;

use App\Exceptions\TokenNotProvidedException;
use App\Http\Controllers\Controller;
use App\Services\CustomerLogoutService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LogoutController extends Controller
{
    public function __construct(
        private CustomerLogoutService $logoutService
    ) {}

    public function __invoke(Request $request): JsonResponse
    {
        try {
            $this->logoutService->execute($request->bearerToken());
            
            return response()->json(null, 204);

        } catch (TokenNotProvidedException $e) {
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
                'message' => 'An error occurred during logout. Please try again later.'
            ], 500);
        }
    }
}
