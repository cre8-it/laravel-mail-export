<?php

namespace PodPoint\MailExport;

use Illuminate\Support\ServiceProvider;
use PodPoint\MailExport\Providers\EventServiceProvider;

class MailExportServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/mail-export.php', 'mail-export');

        $this->app->register(EventServiceProvider::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/mail-export.php' => config_path('mail-export.php'),
            ]);
        }
    }
}
