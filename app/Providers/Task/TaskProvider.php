<?php

namespace App\Providers\Task;

use Illuminate\Support\ServiceProvider;

class TaskProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(TaskCreateProvider::class, function ($app) {
            return new TaskCreateProvider($app);
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
