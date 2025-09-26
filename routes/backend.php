<?php

use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\UnionController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\CategoryController;

// All backend routes will be defined here.

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');

    Route::group(['prefix' => 'product', 'as' => 'product.'], function () {
        Route::resource('/products', ProductController::class);
        Route::get('/categories/{id}/sub-categories', [ProductController::class, 'getSubCategories'])->name('categories.subcategories');
        Route::resource('categories', CategoryController::class);
        Route::resource('brands', BrandController::class);
        Route::resource('locations', UnionController::class);
    });
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
