@component('shop::emails.layout')

<div style="background:#ffffff; padding:45px 35px; border-top:1px solid #e4e4e7; border-bottom:1px solid #e4e4e7;">

    <div style="text-align: center; margin-bottom: 35px;">
        <h1 style="font-family: 'Poppins', sans-serif; font-weight:700; font-size:24px; color:#111827; line-height:32px; margin:0 0 16px 0;">
            @lang('shop::app.emails.orders.invoiced.title')
        </h1>
        <p style="font-family: 'Inter', sans-serif; font-size:16px; color:#4B5563; line-height:24px; margin:0 0 8px 0;">
            @lang('shop::app.emails.dear', ['customer_name' => $invoice->order->customer_full_name]),
        </p>
        <p style="font-family: 'Inter', sans-serif; font-size:16px; color:#4B5563; line-height:24px; margin:0;">
            @lang('shop::app.emails.orders.invoiced.greeting', [
                'invoice_id' => $invoice->increment_id,
                'order_id'   => '<a href="' . route('shop.customers.account.orders.view', $invoice->order_id) . '" style="color: #38200F; font-weight: 600; text-decoration: underline;">#' . $invoice->order->increment_id . '</a>',
                'created_at' => core()->formatDate($invoice->order->created_at, 'Y-m-d H:i:s')
            ])
        </p>
    </div>

    <div style="margin-bottom: 40px;">
        <h2 style="font-family: 'Poppins', sans-serif; font-size: 18px; font-weight: 600; color: #111827; margin-bottom: 20px; text-align: center;">
            @lang('shop::app.emails.orders.invoiced.summary')
        </h2>
        
        <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0">
            <tr>
                @if ($invoice->order->shipping_address)
                <td style="width: 50%; vertical-align: top; padding-right: 15px;">
                    <p style="font-family: 'Inter', sans-serif; font-size: 13px; font-weight: 700; color: #111827; text-transform: uppercase; margin-bottom: 10px; letter-spacing: 0.5px;">
                        @lang('shop::app.emails.orders.shipping-address')
                    </p>
                    <div style="font-family: 'Inter', sans-serif; font-size: 14px; color: #4B5563; line-height: 22px;">
                        {{ $invoice->order->shipping_address->company_name ?? '' }}<br/>
                        {{ $invoice->order->shipping_address->name }}<br/>
                        {{ $invoice->order->shipping_address->address }}<br/>
                        {{ $invoice->order->shipping_address->postcode . " " . $invoice->order->shipping_address->city }}<br/>
                        {{ $invoice->order->shipping_address->state }}<br/>
                        <span style="color: #9CA3AF; font-size: 13px;">@lang('shop::app.emails.orders.contact'): {{ $invoice->order->billing_address->phone }}</span>
                    </div>

                    <p style="font-family: 'Inter', sans-serif; font-size: 13px; font-weight: 700; color: #111827; text-transform: uppercase; margin: 20px 0 5px 0; letter-spacing: 0.5px;">
                        @lang('shop::app.emails.orders.shipping')
                    </p>
                    <p style="font-family: 'Inter', sans-serif; font-size: 14px; color: #4B5563; margin: 0;">{{ $invoice->order->shipping_title }}</p>
                </td>
                @endif

                @if ($invoice->order->billing_address)
                <td style="width: 50%; vertical-align: top; padding-left: 15px;">
                    <p style="font-family: 'Inter', sans-serif; font-size: 13px; font-weight: 700; color: #111827; text-transform: uppercase; margin-bottom: 10px; letter-spacing: 0.5px;">
                        @lang('shop::app.emails.orders.billing-address')
                    </p>
                    <div style="font-family: 'Inter', sans-serif; font-size: 14px; color: #4B5563; line-height: 22px;">
                        {{ $invoice->order->billing_address->company_name ?? '' }}<br/>
                        {{ $invoice->order->billing_address->name }}<br/>
                        {{ $invoice->order->billing_address->address }}<br/>
                        {{ $invoice->order->billing_address->postcode . " " . $invoice->order->billing_address->city }}<br/>
                        {{ $invoice->order->billing_address->state }}<br/>
                        <span style="color: #9CA3AF; font-size: 13px;">@lang('shop::app.emails.orders.contact'): {{ $invoice->order->billing_address->phone }}</span>
                    </div>

                    <p style="font-family: 'Inter', sans-serif; font-size: 13px; font-weight: 700; color: #111827; text-transform: uppercase; margin: 20px 0 5px 0; letter-spacing: 0.5px;">
                        @lang('shop::app.emails.orders.payment')
                    </p>
                    <p style="font-family: 'Inter', sans-serif; font-size: 14px; color: #4B5563; margin: 0;">
                        {{ core()->getConfigData('sales.payment_methods.' . $invoice->order->payment->method . '.title') }}
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
                    <th style="font-family: 'Inter', sans-serif; text-align: left; padding: 12px 15px; font-size: 12px; font-weight: 700; color: #6B7280; text-transform: uppercase; letter-spacing: 0.5px;">@lang('shop::app.emails.orders.sku')</th>
                    <th style="font-family: 'Inter', sans-serif; text-align: left; padding: 12px 15px; font-size: 12px; font-weight: 700; color: #6B7280; text-transform: uppercase; letter-spacing: 0.5px;">@lang('shop::app.emails.orders.name')</th>
                    <th style="font-family: 'Inter', sans-serif; text-align: left; padding: 12px 15px; font-size: 12px; font-weight: 700; color: #6B7280; text-transform: uppercase; letter-spacing: 0.5px;">@lang('shop::app.emails.orders.price')</th>
                    <th style="font-family: 'Inter', sans-serif; text-align: left; padding: 12px 15px; font-size: 12px; font-weight: 700; color: #6B7280; text-transform: uppercase; letter-spacing: 0.5px;">@lang('shop::app.emails.orders.qty')</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($invoice->items as $item)
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
                            {{ core()->formatPrice($item->price_incl_tax, $invoice->order_currency_code) }}
                        @else
                            {{ core()->formatPrice($item->price, $invoice->order_currency_code) }}
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
                <td style="padding: 8px 0; font-family: 'Inter', sans-serif; font-size: 14px; color: #111827; text-align: right;">{{ core()->formatPrice($invoice->sub_total, $invoice->order_currency_code) }}</td>
            </tr>
            @if ($invoice->shipping_amount > 0)
            <tr>
                <td style="padding: 8px 0; font-family: 'Inter', sans-serif; font-size: 14px; color: #4B5563;">@lang('shop::app.emails.orders.shipping-handling')</td>
                <td style="padding: 8px 0; font-family: 'Inter', sans-serif; font-size: 14px; color: #111827; text-align: right;">{{ core()->formatPrice($invoice->shipping_amount, $invoice->order_currency_code) }}</td>
            </tr>
            @endif
            <tr>
                <td style="padding: 8px 0; font-family: 'Inter', sans-serif; font-size: 14px; color: #4B5563;">@lang('shop::app.emails.orders.tax')</td>
                <td style="padding: 8px 0; font-family: 'Inter', sans-serif; font-size: 14px; color: #111827; text-align: right;">{{ core()->formatPrice($invoice->tax_amount, $invoice->order_currency_code) }}</td>
            </tr>
            @if ($invoice->discount_amount > 0)
            <tr>
                <td style="padding: 8px 0; font-family: 'Inter', sans-serif; font-size: 14px; color: #4B5563;">@lang('shop::app.emails.orders.discount')</td>
                <td style="padding: 8px 0; font-family: 'Inter', sans-serif; font-size: 14px; color: #EF4444; text-align: right;">-{{ core()->formatPrice($invoice->discount_amount, $invoice->order_currency_code) }}</td>
            </tr>
            @endif
            <tr>
                <td style="padding: 20px 0 0 0; font-family: 'Inter', sans-serif; font-size: 18px; font-weight: 700; color: #111827;">@lang('shop::app.emails.orders.grand-total')</td>
                <td style="padding: 20px 0 0 0; font-family: 'Inter', sans-serif; font-size: 20px; font-weight: 700; color: #38200F; text-align: right;">{{ core()->formatPrice($invoice->grand_total, $invoice->order_currency_code) }}</td>
            </tr>
        </table>
    </div>

</div>

@endcomponent