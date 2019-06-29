<?php

namespace Larapie\Actions;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Larapie\Actions\Commands\MakeActionCommand;
use Illuminate\Bus\Dispatcher as IlluminateBusDispatcher;
use Illuminate\Contracts\Queue\Factory as QueueFactoryContract;

class ActionServiceProvider extends ServiceProvider
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
