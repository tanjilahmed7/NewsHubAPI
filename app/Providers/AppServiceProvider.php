<?php

namespace App\Providers;

use App\Services\AuthService;
use App\Services\News\NewsService;
use App\Services\AuthServiceInterface;
use Illuminate\Support\ServiceProvider;
use App\Services\News\Providers\NewsApiProvider;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(AuthServiceInterface::class, AuthService::class);

        $this->app->singleton(NewsService::class, function ($app) {
            return new NewsService([
                new NewsApiProvider(),
            ]);
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
