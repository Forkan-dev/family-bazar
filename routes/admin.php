<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\UnionController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest:admin')->group(function () {});

Route::prefix('admin')->middleware(['auth', 'verified'])->group(function () {
    Route::resource('categories', CategoryController::class);
    Route::resource('locations', UnionController::class);
});
