@component('shop::emails.layout')

<div style="background:#ffffff; padding:45px 35px; border-top:1px solid #e4e4e7; border-bottom:1px solid #e4e4e7;">

    <div style="text-align: center; margin-bottom: 35px;">
        <h1 style="font-family: 'Poppins', sans-serif; font-weight:700; font-size:24px; color:#111827; line-height:32px; margin:0 0 16px 0;">
            @lang('shop::app.emails.orders.refunded.title')
        </h1>
        <p style="font-family: 'Inter', sans-serif; font-size:16px; color:#4B5563; line-height:24px; margin:0 0 8px 0;">
            @lang('shop::app.emails.dear', ['customer_name' => $refund->order->customer_full_name]),
        </p>
        <p style="font-family: 'Inter', sans-serif; font-size:16px; color:#4B5563; line-height:24px; margin:0;">
            @lang('shop::app.emails.orders.refunded.greeting', [
                'invoice_id' => $refund->increment_id,
                'order_id'   => '<a href="' . route('shop.customers.account.orders.view', $refund->order_id) . '" style="color: #38200F; font-weight: 600; text-decoration: underline;">#' . $refund->order->increment_id . '</a>',
                'created_at' => core()->formatDate($refund->order->created_at, 'Y-m-d H:i:s')
            ])
        </p>
    </div>

    <div style="margin-bottom: 40px;">
        <h2 style="font-family: 'Poppins', sans-serif; font-size: 18px; font-weight: 600; color: #111827; margin-bottom: 20px; text-align: center;">
            @lang('shop::app.emails.orders.refunded.summary')
        </h2>
        
        <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0">
            <tr>
                @if ($refund->order->shipping_address)
                <td style="width: 50%; vertical-align: top; padding-right: 15px;">
                    <p style="font-family: 'Inter', sans-serif; font-size: 12px; font-weight: 700; color: #111827; text-transform: uppercase; margin-bottom: 10px; letter-spacing: 0.5px;">
                        @lang('shop::app.emails.orders.shipping-address')
                    </p>
                    <div style="font-family: 'Inter', sans-serif; font-size: 14px; color: #4B5563; line-height: 22px;">
                        {{ $refund->order->shipping_address->company_name ?? '' }}<br/>
                        {{ $refund->order->shipping_address->name }}<br/>
                        {{ $refund->order->shipping_address->address }}<br/>
                        {{ $refund->order->shipping_address->postcode . " " . $refund->order->shipping_address->city }}<br/>
                        {{ $refund->order->shipping_address->state }}<br/>
                        <span style="color: #9CA3AF; font-size: 13px;">@lang('shop::app.emails.orders.contact'): {{ $refund->order->billing_address->phone }}</span>
                    </div>
                </td>
                @endif

                @if ($refund->order->billing_address)
                <td style="width: 50%; vertical-align: top; padding-left: 15px;">
                    <p style="font-family: 'Inter', sans-serif; font-size: 12px; font-weight: 700; color: #111827; text-transform: uppercase; margin-bottom: 10px; letter-spacing: 0.5px;">
                        @lang('shop::app.emails.orders.payment')
                    </p>
                    <p style="font-family: 'Inter', sans-serif; font-size: 14px; color: #4B5563; margin: 0 0 15px 0;">
                        {{ core()->getConfigData('sales.payment_methods.' . $refund->order->payment->method . '.title') }}
                    </p>

                    @php $additionalDetails = \Webkul\Payment\Payment::getAdditionalDetails($refund->order->payment->method); @endphp
                    @if (! empty($additionalDetails))
                        <div style="font-family: 'Inter', sans-serif; font-size: 13px; color: #6B7280; line-height: 18px;">
                            <strong>{{ $additionalDetails['title'] }}:</strong> {{ $additionalDetails['value'] }}
                        </div>
                    @endif
                </td>
                @endif
            </tr>
        </table>
    </div>

    <div style="border: 1px solid #e4e4e7; border-radius: 8px; overflow: hidden;">
        <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="border-collapse: collapse;">
            <thead>
                <tr style="background-color: #F9FAFB;">
                    <th style="font-family: 'Inter', sans-serif; text-align: left; padding: 12px 15px; font-size: 11px; font-weight: 700; color: #6B7280; text-transform: uppercase; letter-spacing: 0.5px;">@lang('shop::app.emails.orders.name')</th>
                    <th style="font-family: 'Inter', sans-serif; text-align: left; padding: 12px 15px; font-size: 11px; font-weight: 700; color: #6B7280; text-transform: uppercase; letter-spacing: 0.5px;">@lang('shop::app.emails.orders.price')</th>
                    <th style="font-family: 'Inter', sans-serif; text-align: left; padding: 12px 15px; font-size: 11px; font-weight: 700; color: #6B7280; text-transform: uppercase; letter-spacing: 0.5px;">@lang('shop::app.emails.orders.qty')</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($refund->items as $item)
                <tr style="border-top: 1px solid #e4e4e7;">
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
                            {{ core()->formatPrice($item->price_incl_tax, $refund->order_currency_code) }}
                        @else
                            {{ core()->formatPrice($item->price, $refund->order_currency_code) }}
                        @endif
                    </td>
                    <td style="padding: 15px; font-family: 'Inter', sans-serif; font-size: 14px; color: #111827; vertical-align: top;">
                        {{ $item->qty }}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div style="margin-top: 30px; text-align: right;">
        <table role="presentation" width="280px" cellspacing="0" cellpadding="0" border="0" style="margin-left: auto; border-collapse: collapse;">
            <tr>
                <td style="padding: 8px 0; font-family: 'Inter', sans-serif; font-size: 14px; color: #4B5563;">@lang('shop::app.emails.orders.subtotal')</td>
                <td style="padding: 8px 0; font-family: 'Inter', sans-serif; font-size: 14px; color: #111827; text-align: right;">{{ core()->formatPrice($refund->sub_total, $refund->order_currency_code) }}</td>
            </tr>
            @if ($refund->shipping_amount > 0)
            <tr>
                <td style="padding: 8px 0; font-family: 'Inter', sans-serif; font-size: 14px; color: #4B5563;">@lang('shop::app.emails.orders.shipping-handling')</td>
                <td style="padding: 8px 0; font-family: 'Inter', sans-serif; font-size: 14px; color: #111827; text-align: right;">{{ core()->formatPrice($refund->shipping_amount, $refund->order_currency_code) }}</td>
            </tr>
            @endif
            <tr>
                <td style="padding: 8px 0; font-family: 'Inter', sans-serif; font-size: 14px; color: #4B5563;">@lang('shop::app.emails.orders.tax')</td>
                <td style="padding: 8px 0; font-family: 'Inter', sans-serif; font-size: 14px; color: #111827; text-align: right;">{{ core()->formatPrice($refund->tax_amount, $refund->order_currency_code) }}</td>
            </tr>
            @if ($refund->discount_amount > 0)
            <tr>
                <td style="padding: 8px 0; font-family: 'Inter', sans-serif; font-size: 14px; color: #4B5563;">@lang('shop::app.emails.orders.discount')</td>
                <td style="padding: 8px 0; font-family: 'Inter', sans-serif; font-size: 14px; color: #EF4444; text-align: right;">-{{ core()->formatPrice($refund->discount_amount, $refund->order_currency_code) }}</td>
            </tr>
            @endif
            <tr>
                <td style="padding: 20px 0 0 0; font-family: 'Inter', sans-serif; font-size: 18px; font-weight: 700; color: #111827;">@lang('shop::app.emails.orders.grand-total')</td>
                <td style="padding: 20px 0 0 0; font-family: 'Inter', sans-serif; font-size: 20px; font-weight: 700; color: #38200F; text-align: right;">{{ core()->formatPrice($refund->grand_total, $refund->order_currency_code) }}</td>
            </tr>
        </table>
    </div>

</div>

@endcomponent