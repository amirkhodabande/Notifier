<?php

namespace Amir\Notifier\Providers;

use Amir\Notifier\Console\ClearFailedNotifications;
use Illuminate\Support\ServiceProvider;

class NotifierServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__.'/../../database/migrations');

        if ($this->app->runningInConsole()) {
            $this->commands([
                ClearFailedNotifications::class,
            ]);
        }


        $this->publishes([
            __DIR__ . '/../../config/notifier.php' => config_path('notifier.php')
        ], 'notifier-config');
    }
}