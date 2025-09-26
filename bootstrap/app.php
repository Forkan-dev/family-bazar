<?php

use App\Http\Middleware\HandleAppearance;
use App\Http\Middleware\HandleInertiaRequests;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Middleware\AddLinkHeadersForPreloadedAssets;
use Illuminate\Support\Facades\Route;



return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        channels: __DIR__.'/../routes/channels.php',
        health: '/up',
        // Method 1: Using 'using' parameter (Laravel 11+ preferred way)
        using: function () {
            Route::middleware('web')
                ->group(base_path('routes/backend.php'));
        },
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->encryptCookies(except: [
            'appearance',
            'sidebar_state'
        ]);

        $middleware->web(append: [
            HandleAppearance::class,
            HandleInertiaRequests::class,
            AddLinkHeadersForPreloadedAssets::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // Exception handling configuration
    })
    ->create();

// Alternative Method 2: Register in RouteServiceProvider or AppServiceProvider
// In app/Providers/AppServiceProvider.php boot() method:
/*
public function boot(): void
{
    Route::middleware('web')
        ->group(base_path('routes/backend.php'));
}
*/

// Alternative Method 3: Multiple route files in 'using'
/*
using: function () {
    Route::middleware('web')->group(base_path('routes/backend.php'));
    Route::middleware('web')->group(base_path('routes/admin.php'));
    Route::middleware(['web', 'auth'])->group(base_path('routes/user.php'));
},
*/

