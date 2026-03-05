<?php

namespace Webkul\CustomInpostPaczkomatyShipping\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Event;

class CustomInpostPaczkomatyShippingServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(
            dirname(__DIR__) . '/Config/carriers.php',
            'carriers'
        );

        $this->mergeConfigFrom(
            dirname(__DIR__) . '/Config/system.php',
            'core'
        );

        $this->mergeConfigFrom(
            dirname(__DIR__) . '/Config/config.php',
            'inpostshipping'
        );
    }

    public function boot(): void
    {
        $this->loadRoutesFrom(dirname(__DIR__) . '/Routes/routes.php');

        $this->loadMigrationsFrom(dirname(__DIR__) . '/Database/Migrations');

        $this->loadViewsFrom(
            dirname(__DIR__) . '/Resources/views',
            'inpost'
        );

        /*
        |----------------------------------------
        | Inject widget into checkout
        |----------------------------------------
        */

        Event::listen(
            'bagisto.shop.checkout.onepage.shipping_method.after',
            function ($viewRenderEventManager) {
                $viewRenderEventManager->addTemplate(
                    'inpost::checkout.inpost-widget'
                );
            }
        );
    }
}