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
            $this->publishes([
                __DIR__ . '/Jobs/SendWaMediaJob.php' => app_path('Jobs/SendWaMediaJob.php'),
                __DIR__ . '/Jobs/SendWaMessageJob.php' => app_path('Jobs/SendWaMessageJob.php'),
                __DIR__ . '/Jobs/SendMailMediaJob.php' => app_path('Jobs/SendMailMediaJob.php'),
                __DIR__ . '/Jobs/SendMailMessageJob.php' => app_path('Jobs/SendMailMessageJob.php'),
            ], 'jobs');
        }
    }

    public function register()
    {
        $this->registerBindings();
        $this->mergeConfigFrom(__DIR__ . '/../config/ganadevnotif.php', 'ganadevnotif');
        $this->app->booted(function () {
            $this->autoloadGanadevApiEmailReplace();
        });
    }

    protected function registerBindings()
    {
        $this->app->alias(GanadevApiService::class, 'ganadevnotif');
    }

    protected function autoloadGanadevApiEmailReplace()
    {
        $autoload = new GanadevApiEmailReplace();
        $autoload->run();
    }
}