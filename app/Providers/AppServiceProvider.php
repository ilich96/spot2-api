<?php

namespace App\Providers;

use App\Repositories\LandUseRepository;
use App\Repositories\LandUseRepositoryInterface;
use App\Services\LandUseService;
use App\Services\LandUseServiceInterface;
use App\Services\SquareMeterService;
use App\Services\SquareMeterServiceInterface;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(
            LandUseRepositoryInterface::class,
            LandUseRepository::class
        );
        $this->app->bind(
            LandUseServiceInterface::class,
            LandUseService::class
        );
        $this->app->bind(
            SquareMeterServiceInterface::class,
            SquareMeterService::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
