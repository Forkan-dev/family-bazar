<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ProductController;


Route::group(['prefix' => 'customer'], function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('register', [AuthController::class, 'register']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('otp_verify',[AuthController::class, 'otpVerify']);
});




Route::get('cart', [CartController::class, 'index'])->name('cart.index');
Route::post('cart/add', [CartController::class, 'store'])->name('cart.store');
Route::get('cart/merge', [CartController::class, 'mergeCart']);



//product


Route::get('products/dropdown', [ProductController::class, 'productDropdown']);
Route::apiResource('products', ProductController::class);

Route::get('categories/dropdown', [CategoryController::class, 'categoryDropdown']);
