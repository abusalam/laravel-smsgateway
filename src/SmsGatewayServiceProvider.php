<?php

namespace AbuSalam;

use Illuminate\Support\ServiceProvider;

/**
 * Bootstrap SMS Gateway Services
 */
class SmsGatewayServiceProvider extends ServiceProvider
{
    
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/config.php' => config_path('smsgateway.php'),
            ], 'config');
            /*
            $this->loadViewsFrom(__DIR__.'/../resources/views', 'skeleton');
            $this->publishes([
                __DIR__.'/../resources/views' => base_path('resources/views/vendor/skeleton'),
            ], 'views');
            */
        }
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/config.php', 'smsgateway');
    }
}
