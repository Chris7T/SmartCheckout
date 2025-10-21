<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Customer\GetCustomerController;
use App\Http\Controllers\Order\DeleteOrderController;
use App\Http\Controllers\Order\GetOrderDetailController;
use App\Http\Controllers\Order\ListOrdersController;
use App\Http\Controllers\Order\RegisterOrderController;
use App\Http\Controllers\Order\UpdateOrderController;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group(function () {
    Route::post('/register', RegisterController::class);
    Route::post('/login', LoginController::class);
    
    Route::post('/logout', LogoutController::class)->middleware('auth.customer');
});

Route::prefix('customers')->middleware('auth.customer')->group(function () {
    Route::get('/{id}', GetCustomerController::class);
});

Route::prefix('orders')->middleware('auth.customer')->group(function () {
    Route::get('/', ListOrdersController::class);
    Route::get('/{id}', GetOrderDetailController::class);
    Route::post('/', RegisterOrderController::class);
    Route::put('/{id}', UpdateOrderController::class);
    Route::delete('/{id}', DeleteOrderController::class);
});
