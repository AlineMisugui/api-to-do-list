<?php

namespace App\Providers\TaskGroup;

use Illuminate\Support\ServiceProvider;

class TaskGroupProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(TaskGroupCreateProvider::class, function ($app) {
            return new TaskGroupCreateProvider($app);
        });
        $this->app->bind(TaskGroupUpdateProvider::class, function ($app) {
            return new TaskGroupUpdateProvider($app);
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
