<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Admin\Auth\RegisteredUserController;
use App\Http\Controllers\Admin\CategoryController;

Route::middleware('guest:admin')->group(function () {

});

Route::prefix('admin')->middleware(['auth', 'verified'])->group(function () {
    Route::resource('categories', CategoryController::class);
});
