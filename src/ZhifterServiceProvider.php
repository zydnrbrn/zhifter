<?php

namespace Zydnrbrn\Zhifter;

use Illuminate\Support\ServiceProvider;

class ZhifterServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                // TODO: Call our command file here
            ]);

            $this->publishes([
                __DIR__ . '/stubs' => resource_path('stubs'),
            ]);

            $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');
        }
    }

    public function register()
    {
        //
    }
}
