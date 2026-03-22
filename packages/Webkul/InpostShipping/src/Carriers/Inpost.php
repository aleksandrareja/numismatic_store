<?php

namespace Webkul\InpostShipping\Carriers;

use Webkul\Checkout\Facades\Cart;
use Webkul\Checkout\Models\CartShippingRate;
use Webkul\Shipping\Carriers\AbstractShipping;

class Inpost extends AbstractShipping
{
    /**
     * Carrier code — must match key in carriers.php.
     *
     * @var string
     */
    protected $code = 'inpost';

    /**
     * Calculate and return shipping rate for the current cart.
     * Returns false if the method is unavailable.
     */
    public function calculate(): CartShippingRate|false
    {
        if (! $this->getConfigData('active')) {
            return false;
        }

        $cart = Cart::getCart();

        if (! $cart) {
            return false;
        }

        $rate = new CartShippingRate;

        $rate->carrier             = 'inpost';
        $rate->carrier_title       = $this->getConfigData('title') ?: 'InPost Paczkomat';
        $rate->method              = 'inpost_inpost';
        $rate->method_title        = $this->getConfigData('title') ?: 'InPost Paczkomat';
        $rate->method_description  = $this->getConfigData('description') ?: 'Dostawa do paczkomatu InPost';
        $rate->price               = (float) ($this->getConfigData('default_rate') ?? 9.99);
        $rate->base_price          = $rate->price;

        return $rate;
    }
}
