<?php

use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\RolesController;
use App\Http\Controllers\Admin\UnionController;
use App\Http\Controllers\Admin\BannerController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DocumentController;
use App\Http\Controllers\Admin\PermissionsController;

// All backend routes will be defined here.

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::delete('documents/{document}', [DocumentController::class, 'destroy'])->name('documents.destroy');

    Route::get('dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');

    Route::group(['prefix' => 'product', 'as' => 'product.'], function () {
        Route::get('/products', [ProductController::class, 'index'])->name('products.index')->middleware('permission:product.view');
        Route::get('/products/create', [ProductController::class, 'create'])->name('products.create')->middleware('permission:product.create');
        Route::post('/products', [ProductController::class, 'store'])->name('products.store')->middleware('permission:product.create');
        Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show')->middleware('permission:product.view');
        Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit')->middleware('permission:product.update');
        Route::put('/products/{product}', [ProductController::class, 'update'])->name('products.update')->middleware('permission:product.update');
        Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy')->middleware('permission:product.delete');

        Route::get('/categories/{id}/sub-categories', [ProductController::class, 'getSubCategories'])->name('categories.subcategories');

        Route::get('categories', [CategoryController::class, 'index'])->name('categories.index')->middleware('permission:category.view');
        Route::get('categories/create', [CategoryController::class, 'create'])->name('categories.create')->middleware('permission:category.create');
        Route::post('categories', [CategoryController::class, 'store'])->name('categories.store')->middleware('permission:category.create');
        Route::get('categories/{category}', [CategoryController::class, 'show'])->name('categories.show')->middleware('permission:category.view');
        Route::get('categories/{category}/edit', [CategoryController::class, 'edit'])->name('categories.edit')->middleware('permission:category.update');
        Route::put('categories/{category}', [CategoryController::class, 'update'])->name('categories.update')->middleware('permission:category.update');
        Route::delete('categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy')->middleware('permission:category.delete');

        Route::get('brands', [BrandController::class, 'index'])->name('brands.index')->middleware('permission:brand.view');
        Route::get('brands/create', [BrandController::class, 'create'])->name('brands.create')->middleware('permission:brand.create');
        Route::post('brands', [BrandController::class, 'store'])->name('brands.store')->middleware('permission:brand.create');
        Route::get('brands/{brand}', [BrandController::class, 'show'])->name('brands.show')->middleware('permission:brand.view');
        Route::get('brands/{brand}/edit', [BrandController::class, 'edit'])->name('brands.edit')->middleware('permission:brand.update');
        Route::put('brands/{brand}', [BrandController::class, 'update'])->name('brands.update')->middleware('permission:brand.update');
        Route::delete('brands/{brand}', [BrandController::class, 'destroy'])->name('brands.destroy')->middleware('permission:brand.delete');

        Route::get('locations', [UnionController::class, 'index'])->name('locations.index')->middleware('permission:location.view');
        Route::get('locations/create', [UnionController::class, 'create'])->name('locations.create')->middleware('permission:location.create');
        Route::post('locations', [UnionController::class, 'store'])->name('locations.store')->middleware('permission:location.create');
        Route::get('locations/{location}', [UnionController::class, 'show'])->name('locations.show')->middleware('permission:location.view');
        Route::get('locations/{location}/edit', [UnionController::class, 'edit'])->name('locations.edit')->middleware('permission:location.update');
        Route::put('locations/{location}', [UnionController::class, 'update'])->name('locations.update')->middleware('permission:location.update');
        Route::delete('locations/{location}', [UnionController::class, 'destroy'])->name('locations.destroy')->middleware('permission:location.delete');
    });

    Route::get('permissions', [PermissionsController::class, 'index'])->name('admin.permissions.index')->middleware('permission:permission.view');
    Route::get('permissions/create', [PermissionsController::class, 'create'])->name('admin.permissions.create')->middleware('permission:permission.create');
    Route::post('permissions', [PermissionsController::class, 'store'])->name('admin.permissions.store')->middleware('permission:permission.create');
    Route::get('permissions/{permission}', [PermissionsController::class, 'show'])->name('admin.permissions.show')->middleware('permission:permission.view');
    Route::get('permissions/{permission}/edit', [PermissionsController::class, 'edit'])->name('admin.permissions.edit')->middleware('permission:permission.update');
    Route::put('permissions/{permission}', [PermissionsController::class, 'update'])->name('admin.permissions.update')->middleware('permission:permission.update');
    Route::delete('permissions/{permission}', [PermissionsController::class, 'destroy'])->name('admin.permissions.destroy')->middleware('permission:permission.delete');

    Route::get('roles', [RolesController::class, 'index'])->name('admin.roles.index')->middleware('permission:role.view');
    Route::get('roles/create', [RolesController::class, 'create'])->name('admin.roles.create')->middleware('permission:role.create');
    Route::post('roles', [RolesController::class, 'store'])->name('admin.roles.store')->middleware('permission:role.create');
    Route::get('roles/{role}', [RolesController::class, 'show'])->name('admin.roles.show')->middleware('permission:role.view');
    Route::get('roles/{role}/edit', [RolesController::class, 'edit'])->name('admin.roles.edit')->middleware('permission:role.update');
    Route::put('roles/{role}', [RolesController::class, 'update'])->name('admin.roles.update')->middleware('permission:role.update');
    Route::delete('roles/{role}', [RolesController::class, 'destroy'])->name('admin.roles.destroy')->middleware('permission:role.delete');
    Route::get('roles/assign/create', [RolesController::class, 'assignRoleForm'])->name('admin.roles.assign.create')->middleware('permission:role.update');
    Route::post('roles/assign', [RolesController::class, 'assignRole'])->name('admin.roles.assign.store')->middleware('permission:role.update');

    Route::get('banners', [BannerController::class, 'index'])->name('admin.banners.index')->middleware('permission:banner.view');
    Route::get('banners/create', [BannerController::class, 'create'])->name('admin.banners.create')->middleware('permission:banner.create');
    Route::post('banners', [BannerController::class, 'store'])->name('admin.banners.store')->middleware('permission:banner.create');
    Route::get('banners/{banner}', [BannerController::class, 'show'])->name('admin.banners.show')->middleware('permission:banner.view');
    Route::get('banners/{banner}/edit', [BannerController::class, 'edit'])->name('admin.banners.edit')->middleware('permission:banner.update');
    Route::put('banners/{banner}', [BannerController::class, 'update'])->name('admin.banners.update')->middleware('permission:banner.update');
    Route::delete('banners/{banner}', [BannerController::class, 'destroy'])->name('admin.banners.destroy')->middleware('permission:banner.delete');
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
