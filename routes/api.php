<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\OrderController;
use Illuminate\Support\Facades\Route;


Route::group(['prefix' => 'customer'], function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('register', [AuthController::class, 'register']);
    Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:customer');
    Route::post('otp_verify',[AuthController::class, 'otpVerify']);
});




Route::get('cart', [CartController::class, 'index'])->name('cart.index');
Route::post('cart/add', [CartController::class, 'store'])->name('cart.store');
Route::get('cart/merge', [CartController::class, 'mergeCart']);

Route::middleware('auth:customer')->prefix('customer')->group(function () {
    Route::post('order/checkout', [OrderController::class, 'checkout'])->name('cart.checkout');
});
