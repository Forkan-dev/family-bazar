<?php

use App\Http\Controllers\CartController;
use Illuminate\Support\Facades\Route;

Route::post('cart/add', [CartController::class, 'addToCart'])->name('cart.add');
