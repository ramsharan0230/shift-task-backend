<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Repositories\LoginEloquentInterface;
use Repositories\LoginRepository;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(LoginEloquentInterface::class, LoginRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
