<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\ProductController;
use Inertia\Inertia;

// All backend routes will be defined here.

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');

    Route::group(['prefix' => 'products', 'as' => 'products.'], function () {
        Route::resource('', ProductController::class);
        Route::get('/categories/{id}/sub-categories', [ProductController::class, 'getSubCategories'])->name('categories.subcategories');
    });
});