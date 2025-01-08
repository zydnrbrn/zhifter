<?php

namespace Zydnrbrn\Zhifter;

use Illuminate\Support\ServiceProvider;
use Zydnrbrn\Zhifter\Console\Commands\ZhifterCommand;
use Zydnrbrn\Zhifter\Console\Commands\ZhifterConnectionTestCommand;

class ZhifterServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                ZhifterCommand::class,
                ZhifterConnectionTestCommand::class
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
