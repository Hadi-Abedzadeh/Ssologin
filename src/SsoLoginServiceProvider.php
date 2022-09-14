<?php

namespace Hadiabedzadeh\Ssologin;

use Illuminate\Support\ServiceProvider;

class SsoLoginServiceProvider extends ServiceProvider
{

    public function boot()
    {
        $this->loadRoutesFrom(__DIR__ . '/routes/web.php');
        $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'systems');
        $this->loadViewsFrom(__DIR__ . '/../resources/views/systems', 'systems');

        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');

        if ($this->app->runningInConsole()) {
            // Publishing the configuration file.
            $this->definePublishable();
        }

    }

    public function register()
    {
    }

    private function definePublishable()
    {
        $this->publishes([realpath(__DIR__ . '/../database/migrations') => database_path('migrations')], 'migrations');
        $this->publishes([__DIR__.'/../config/sso.php' => config_path('sso.php')], 'sso-config');
    }
}

