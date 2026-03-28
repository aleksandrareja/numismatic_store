@component('shop::emails.layout')

<div style="background:#ffffff; padding:45px 35px; border-top:1px solid #e4e4e7; border-bottom:1px solid #e4e4e7;">

    <div style="text-align: center; margin-bottom: 35px;">
        <h1 style="font-family: 'Poppins', sans-serif; font-weight:700; font-size:24px; color:#111827; line-height:32px; margin:0 0 16px 0;">
            @lang('shop::app.emails.orders.created.title')
        </h1>
        <p style="font-family: 'Inter', sans-serif; font-size:16px; color:#4B5563; line-height:24px; margin:0 0 8px 0;">
            @lang('shop::app.emails.dear', ['customer_name' => $order->customer_full_name]),
        </p>
        <p style="font-family: 'Inter', sans-serif; font-size:16px; color:#4B5563; line-height:24px; margin:0;">
            {!! __('shop::app.emails.orders.created.greeting', [
                'order_id' => '<a href="' . route('shop.customers.account.orders.view', $order->id) . '" style="color: #38200F; font-weight: 600; text-decoration: underline;">#' . $order->increment_id . '</a>',
                'created_at' => core()->formatDate($order->created_at, 'Y-m-d H:i:s')
            ]) !!}
        </p>
    </div>

    <div style="margin-bottom: 40px;">
        <h2 style="font-family: 'Poppins', sans-serif; font-size: 18px; font-weight: 600; color: #111827; margin-bottom: 20px; text-align: center;">
            @lang('shop::app.emails.orders.created.summary')
        </h2>
        
        <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0">
            <tr>
                @if ($order->shipping_address)
                <td style="width: 50%; vertical-align: top; padding-right: 15px;">
                    <p style="font-family: 'Inter', sans-serif; font-size: 14px; font-weight: 700; color: #111827; text-transform: uppercase; margin-bottom: 10px;">
                        @lang('shop::app.emails.orders.shipping-address')
                    </p>
                    <div style="font-family: 'Inter', sans-serif; font-size: 14px; color: #4B5563; line-height: 22px;">
                        {{ $order->shipping_address->company_name ?? '' }}<br/>
                        {{ $order->shipping_address->name }}<br/>
                        {{ $order->shipping_address->address }}<br/>
                        {{ $order->shipping_address->postcode . " " . $order->shipping_address->city }}<br/>
                        {{ $order->shipping_address->state }}<br/>
                        <span style="color: #9CA3AF;">@lang('shop::app.emails.orders.contact'): {{ $order->billing_address->phone }}</span>
                    </div>

                    <p style="font-family: 'Inter', sans-serif; font-size: 14px; font-weight: 700; color: #111827; text-transform: uppercase; margin: 20px 0 5px 0;">
                        @lang('shop::app.emails.orders.shipping')
                    </p>
                    <p style="font-family: 'Inter', sans-serif; font-size: 14px; color: #4B5563; margin: 0;">{{ $order->shipping_title }}</p>
                </td>
                @endif

                @if ($order->billing_address)
                <td style="width: 50%; vertical-align: top; padding-left: 15px;">
                    <p style="font-family: 'Inter', sans-serif; font-size: 14px; font-weight: 700; color: #111827; text-transform: uppercase; margin-bottom: 10px;">
                        @lang('shop::app.emails.orders.billing-address')
                    </p>
                    <div style="font-family: 'Inter', sans-serif; font-size: 14px; color: #4B5563; line-height: 22px;">
                        {{ $order->billing_address->company_name ?? '' }}<br/>
                        {{ $order->billing_address->name }}<br/>
                        {{ $order->billing_address->address }}<br/>
                        {{ $order->billing_address->postcode . " " . $order->billing_address->city }}<br/>
                        {{ $order->billing_address->state }}<br/>
                        <span style="color: #9CA3AF;">@lang('shop::app.emails.orders.contact'): {{ $order->billing_address->phone }}</span>
                    </div>

                    <p style="font-family: 'Inter', sans-serif; font-size: 14px; font-weight: 700; color: #111827; text-transform: uppercase; margin: 20px 0 5px 0;">
                        @lang('shop::app.emails.orders.payment')
                    </p>
                    <p style="font-family: 'Inter', sans-serif; font-size: 14px; color: #4B5563; margin: 0;">
                        {{ core()->getConfigData('sales.payment_methods.' . $order->payment->method . '.title') }}
                    </p>
                </td>
                @endif
            </tr>
        </table>
    </div>

    <div style="border: 1px solid #e4e4e7; border-radius: 8px; overflow: hidden;">
        <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="border-collapse: collapse;">
            <thead>
                <tr style="background-color: #F9FAFB;">
                    @foreach (['sku', 'name', 'price', 'qty'] as $item)
                        <th style="font-family: 'Inter', sans-serif; text-align: left; padding: 12px 15px; font-size: 13px; font-weight: 600; color: #6B7280; text-transform: uppercase;">
                            @lang('shop::app.emails.orders.' . $item)
                        </th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach ($order->items as $item)
                <tr style="border-top: 1px solid #e4e4e7;">
                    <td style="padding: 15px; font-family: 'Inter', sans-serif; font-size: 14px; color: #111827; vertical-align: top;">
                        {{ $item->getTypeInstance()->getOrderedItem($item)->sku }}
                    </td>
                    <td style="padding: 15px; font-family: 'Inter', sans-serif; font-size: 14px; color: #111827; vertical-align: top;">
                        <div style="font-weight: 600; margin-bottom: 4px;">{{ $item->name }}</div>
                        @if (isset($item->additional['attributes']))
                            <div style="font-size: 12px; color: #6B7280;">
                                @foreach ($item->additional['attributes'] as $attribute)
                                    <strong>{{ $attribute['attribute_name'] }}:</strong> {{ $attribute['option_label'] }}<br>
                                @endforeach
                            </div>
                        @endif
                    </td>
                    <td style="padding: 15px; font-family: 'Inter', sans-serif; font-size: 14px; color: #111827; vertical-align: top;">
                        @if (core()->getConfigData('sales.taxes.sales.display_prices') == 'including_tax')
                            {{ core()->formatPrice($item->price_incl_tax, $order->order_currency_code) }}
                        @else
                            {{ core()->formatPrice($item->price, $order->order_currency_code) }}
                        @endif
                    </td>
                    <td style="padding: 15px; font-family: 'Inter', sans-serif; font-size: 14px; color: #111827; vertical-align: top;">
                        {{ $item->qty_ordered }}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div style="margin-top: 30px; text-align: right;">
        <table role="presentation" width="250px" cellspacing="0" cellpadding="0" border="0" style="margin-left: auto;">
            <tr>
                <td style="padding: 5px 0; font-family: 'Inter', sans-serif; font-size: 14px; color: #4B5563;">@lang('shop::app.emails.orders.subtotal')</td>
                <td style="padding: 5px 0; font-family: 'Inter', sans-serif; font-size: 14px; color: #111827; text-align: right;">{{ core()->formatPrice($order->sub_total, $order->order_currency_code) }}</td>
            </tr>
            <tr>
                <td style="padding: 5px 0; font-family: 'Inter', sans-serif; font-size: 14px; color: #4B5563;">@lang('shop::app.emails.orders.tax')</td>
                <td style="padding: 5px 0; font-family: 'Inter', sans-serif; font-size: 14px; color: #111827; text-align: right;">{{ core()->formatPrice($order->tax_amount, $order->order_currency_code) }}</td>
            </tr>
            @if ($order->discount_amount > 0)
            <tr>
                <td style="padding: 5px 0; font-family: 'Inter', sans-serif; font-size: 14px; color: #4B5563;">@lang('shop::app.emails.orders.discount')</td>
                <td style="padding: 5px 0; font-family: 'Inter', sans-serif; font-size: 14px; color: #EF4444; text-align: right;">-{{ core()->formatPrice($order->discount_amount, $order->order_currency_code) }}</td>
            </tr>
            @endif
            <tr style="border-top: 1px solid #e4e4e7;">
                <td style="padding: 15px 0 0 0; font-family: 'Inter', sans-serif; font-size: 18px; font-weight: 700; color: #111827;">@lang('shop::app.emails.orders.grand-total')</td>
                <td style="padding: 15px 0 0 0; font-family: 'Inter', sans-serif; font-size: 18px; font-weight: 700; color: #38200F; text-align: right;">{{ core()->formatPrice($order->grand_total, $order->order_currency_code) }}</td>
            </tr>
        </table>
    </div>

</div>

@endcomponent