<?php

namespace Larapie\Actions;

use Illuminate\Support\ServiceProvider;
use Larapie\Actions\Commands\MakeActionCommand;

class LarapieActionServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                MakeActionCommand::class,
            ]);
        }
    }
}
