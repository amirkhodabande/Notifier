<?php

namespace Amir\Notifier\Providers;

use Illuminate\Support\ServiceProvider;

class NotifierServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__.'/../../database/migrations');

        $this->publishes([
            __DIR__ . '/../../config/notifier.php' => config_path('notifier.php')
        ], 'notifier-config');
    }
}