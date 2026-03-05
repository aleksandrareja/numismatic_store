<?php

namespace Webkul\CustomInpostPaczkomatyShipping\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Event;

class CustomInpostPaczkomatyShippingServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // merge carrier configuration
        $this->mergeConfigFrom(
            dirname(__DIR__) . '/Config/carriers.php',
            'carriers'
        );

        // merge system configuration  
        $this->mergeConfigFrom(
            dirname(__DIR__) . '/Config/system.php',
            'core'
        );

        $this->mergeConfigFrom(
            dirname(__DIR__) . '/Config/config.php',
            'inpostshipping'
        );
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // load routes
        $this->loadRoutesFrom(dirname(__DIR__) . '/Routes/routes.php');

        // load migrations
        $this->loadMigrationsFrom(dirname(__DIR__) . '/Database/Migrations');

        $this->loadViewsFrom(dirname(__DIR__) . '/Resources/views', 'paczkomaty');
       
        /*Event::listen('bagisto.shop.layout.body.after', function($viewRenderEventManager) {
            $viewRenderEventManager->addTemplate('paczkomaty::checkout.inpost-widget');
        });
        */
    }

}