<?php

namespace Uithread\MgovSMS;

use Illuminate\Support\ServiceProvider;

class MgovSMSServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(MgovSMS::class, function () {
            return new MgovSMS();
        });
        $this->app->alias(MgovSMS::class, 'mgov-sms');
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishConfig();
        $this->publishMigrations();
    }

    private function publishConfig()
    {
        $this->publishes([__DIR__ . '/config/mgov-sms.php' => config_path('mgov-sms.php')], 'config');
    }

    private function publishMigrations()
    {
        $this->publishes([__DIR__.'/migrations' => database_path('migrations')], 'migrations');
    }
}
