<?php

namespace RasmusGodske\FlashBeep;

use Illuminate\Support\ServiceProvider;

class FlashBeepServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/flashbeep.php' => config_path('flashbeep.php'),
          ],
          'config'
        );
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/flashbeep.php', 'flashbeep'
        );
    }
}