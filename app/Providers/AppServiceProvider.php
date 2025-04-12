<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Repositories\LoginEloquentInterface;
use Repositories\TaskEloquentInterface;
use Repositories\LoginRepository;
use Repositories\TaskRepository;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(LoginEloquentInterface::class, LoginRepository::class);
        $this->app->bind(TaskEloquentInterface::class, TaskRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
