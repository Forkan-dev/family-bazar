<?php

namespace App\Providers;

use App\Services\ImageService;
use Illuminate\Support\ServiceProvider;
use App\Services\Contracts\ImageServiceInterface;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(ImageServiceInterface::class, ImageService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
