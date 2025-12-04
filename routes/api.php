<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CartController;


Route::post('cart/add', [CartController::class, 'addToCart'])->name('cart.add');


