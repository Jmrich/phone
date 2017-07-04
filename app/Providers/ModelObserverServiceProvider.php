<?php

namespace App\Providers;

use App\Models\Endpoint;
use App\Models\Extension;
use App\Models\Observers\ExtensionObserver;
use App\Models\Observers\EndpointObserver;
use App\Models\User;
use Illuminate\Support\ServiceProvider;

class ModelObserverServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        Endpoint::observe(EndpointObserver::class);
        Extension::observe(ExtensionObserver::class);
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
