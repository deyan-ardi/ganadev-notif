<?php

namespace DeyanArdi\GanadevNotif;

use Illuminate\Support\ServiceProvider;

class GanadevServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/ganadevnotif.php' => config_path('ganadevnotif.php'),
            ], 'config');
        }
    }

    public function register()
    {
        $this->app->alias(GanadevApiService::class, 'ganadevnotif');
        $this->mergeConfigFrom(__DIR__ . '/../config/ganadevnotif.php', 'ganadevnotif');
        $this->app->bind(ClientInterface::class, function ($app) {
            return new GuzzleHttp\Client();
        });
    }
}
