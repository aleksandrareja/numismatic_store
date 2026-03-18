<?php

namespace Webkul\InpostShipping\Listeners;

use Illuminate\Support\Facades\Log;
use Webkul\Sales\Models\Order;

class OrderSaved
{
    /**
     * Handle the checkout.order.save.after event.
     *
     * Copies the InPost locker selection from session into the saved order row.
     *
     * @param  \Webkul\Sales\Models\Order  $order
     */
    public function handle(Order $order): void
    {
        Log::info('InPost: OrderSaved handle called', [
            'order_id'        => $order->id,
            'shipping_method' => $order->shipping_method,
            'session_point'   => session('inpost_point_id'),
        ]);

        // Only act when the customer chose InPost as shipping method
        if ($order->shipping_method !== 'inpost_inpost') {
            return;
        }

        $pointId      = session('inpost_point_id');
        $pointName    = session('inpost_point_name');
        $pointAddress = session('inpost_point_address');

        if (! $pointId) {
            // Selection is missing — log a warning but do not crash the order
            Log::warning('InPost: order saved with InPost method but no locker selected.', [
                'order_id' => $order->id,
            ]);

            return;
        }

        try {
            $order->update([
                'inpost_point_id'      => $pointId,
                'inpost_point_name'    => $pointName,
                'inpost_point_address' => $pointAddress,
            ]);

            // Clear session data after successful save
            session()->forget(['inpost_point_id', 'inpost_point_name', 'inpost_point_address']);

            Log::info('InPost: locker saved to order.', [
                'order_id' => $order->id,
                'point_id' => $pointId,
            ]);
        } catch (\Exception $e) {
            Log::error('InPost: failed to save locker to order.', [
                'order_id' => $order->id,
                'error'    => $e->getMessage(),
            ]);
        }
    }
}
