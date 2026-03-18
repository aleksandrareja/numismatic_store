<?php

namespace Webkul\InpostShipping\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider;
use Illuminate\Support\Facades\Event;
use Webkul\InpostShipping\Listeners\OrderSaved;

class InpostShippingServiceProvider extends EventServiceProvider
{
    /**
     * Bagisto event → listener mappings.
     *
     * Using the modern [ClassName, 'method'] tuple syntax as recommended
     * in the Bagisto event listeners documentation.
     *
     * @var array<string, array<int, array<int, string>>>
     */
    protected $listen = [
        /**
         * Fired after every order is persisted.
         * We use it to copy the selected InPost locker from session to the order row.
         */
        'checkout.order.save.after' => [
            [OrderSaved::class, 'handle'],
        ],
    ];

    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Merge carrier definition — picked up by Bagisto's shipping manager
        $this->mergeConfigFrom(
            dirname(__DIR__) . '/Config/carriers.php',
            'carriers'
        );

        // Merge admin system config fields
        $this->mergeConfigFrom(
            dirname(__DIR__) . '/Config/system.php',
            'core'
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Run parent boot so $listen mappings are registered
        parent::boot();

        // ── Routes ────────────────────────────────────────────────────────────
        $this->loadRoutesFrom(dirname(__DIR__) . '/Http/routes.php');

        // ── Migrations ────────────────────────────────────────────────────────
        $this->loadMigrationsFrom(dirname(__DIR__) . '/Database/Migrations');

        // ── Views ─────────────────────────────────────────────────────────────
        $this->loadViewsFrom(
            dirname(__DIR__) . '/Resources/views',
            'inpost'
        );

        // ── Translations ──────────────────────────────────────────────────────
        $this->loadTranslationsFrom(
            dirname(__DIR__) . '/Resources/lang',
            'inpost'
        );

        // ── View Render Events ────────────────────────────────────────────────
        // Inject the GeoWidget below the shipping method list on the checkout page.
   
        /*Event::listen(
            'bagisto.shop.checkout.onepage.shipping_methods.after',
            function ($viewRenderEventManager) {
                static $rendered = false;
                if ($rendered) return;
                $rendered = true;
                $viewRenderEventManager->addTemplate('inpost::shop.checkout.geowidget');
            }
        );*/


        // Inject InPost locker info into the admin order detail view
        Event::listen(
            'bagisto.admin.sales.orders.view.after',
            function ($viewRenderEventManager) {
                $viewRenderEventManager->addTemplate('inpost::admin.orders.inpost-info');
            }
        );
    }
}
