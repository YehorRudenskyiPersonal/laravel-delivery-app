<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Contracts\CourierServiceInterface;
use App\Services\CourierService;
use App\Factories\CourierFactory;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(CourierFactory::class);

        $this->app->bind(CourierServiceInterface::class, function ($app, $params) {
            return new CourierService($params['endpoint'], $params['adapter']);
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
