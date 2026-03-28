@component('shop::emails.layout')

<div style="background:#ffffff; padding:45px 35px; border-top:1px solid #e4e4e7; border-bottom:1px solid #e4e4e7;">

    <div style="text-align: center; margin-bottom: 35px;">
        <h1 style="font-family: 'Poppins', sans-serif; font-weight:700; font-size:24px; color:#111827; line-height:32px; margin:0 0 16px 0;">
            @lang('shop::app.emails.orders.shipped.title')
        </h1>
        <p style="font-family: 'Inter', sans-serif; font-size:16px; color:#4B5563; line-height:24px; margin:0 0 8px 0;">
            @lang('shop::app.emails.dear', ['customer_name' => $shipment->order->customer_full_name]),
        </p>
        <p style="font-family: 'Inter', sans-serif; font-size:16px; color:#4B5563; line-height:24px; margin:0;">
            @lang('shop::app.emails.orders.shipped.greeting', [
                'invoice_id' => $shipment->increment_id,
                'order_id'   => '<a href="' . route('shop.customers.account.orders.view', $shipment->order_id) . '" style="color: #38200F; font-weight: 600; text-decoration: underline;">#' . $shipment->order->increment_id . '</a>',
                'created_at' => core()->formatDate($shipment->order->created_at, 'Y-m-d H:i:s')
            ])
        </p>
    </div>

    <div style="margin-bottom: 40px;">
        <h2 style="font-family: 'Poppins', sans-serif; font-size: 18px; font-weight: 600; color: #111827; margin-bottom: 20px; text-align: center;">
            @lang('shop::app.emails.orders.shipped.summary')
        </h2>
        
        <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0">
            <tr>
                @if ($shipment->order->shipping_address)
                <td style="width: 50%; vertical-align: top; padding-right: 15px;">
                    <p style="font-family: 'Inter', sans-serif; font-size: 12px; font-weight: 700; color: #111827; text-transform: uppercase; margin-bottom: 10px; letter-spacing: 0.5px;">
                        @lang('shop::app.emails.orders.shipping-address')
                    </p>
                    <div style="font-family: 'Inter', sans-serif; font-size: 14px; color: #4B5563; line-height: 22px;">
                        {{ $shipment->order->shipping_address->company_name ?? '' }}<br/>
                        {{ $shipment->order->shipping_address->name }}<br/>
                        {{ $shipment->order->shipping_address->address }}<br/>
                        {{ $shipment->order->shipping_address->postcode . " " . $shipment->order->shipping_address->city }}<br/>
                        {{ $shipment->order->shipping_address->state }}<br/>
                        <span style="color: #9CA3AF; font-size: 13px;">@lang('shop::app.emails.orders.contact'): {{ $shipment->order->billing_address->phone }}</span>
                    </div>
                </td>
                @endif

                <td style="width: 50%; vertical-align: top; padding-left: 15px;">
                    <p style="font-family: 'Inter', sans-serif; font-size: 12px; font-weight: 700; color: #111827; text-transform: uppercase; margin-bottom: 10px; letter-spacing: 0.5px;">
                        @lang('shop::app.emails.orders.shipping')
                    </p>
                    <p style="font-family: 'Inter', sans-serif; font-size: 14px; color: #4B5563; margin: 0 0 15px 0;">
                        {{ $shipment->order->shipping_title }}
                    </p>

                    <div style="background-color: #F9FAFB; padding: 15px; border-radius: 6px; border: 1px dashed #CBD5E1;">
                        <p style="font-family: 'Inter', sans-serif; font-size: 13px; color: #111827; margin: 0 0 5px 0;">
                            <strong>@lang('shop::app.emails.orders.carrier'):</strong> {{ $shipment->carrier_title }}
                        </p>
                        <p style="font-family: 'Inter', sans-serif; font-size: 13px; color: #111827; margin: 0;">
                            <strong>@lang('shop::app.emails.orders.tracking-number', ['tracking_number' => '']):</strong> 
                            <span style="color: #38200F; font-weight: 700;">{{ $shipment->track_number }}</span>
                        </p>
                    </div>
                </td>
            </tr>
        </table>
    </div>

    <div style="border: 1px solid #e4e4e7; border-radius: 8px; overflow: hidden;">
        <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="border-collapse: collapse;">
            <thead>
                <tr style="background-color: #F9FAFB;">
                    <th style="font-family: 'Inter', sans-serif; text-align: left; padding: 12px 15px; font-size: 11px; font-weight: 700; color: #6B7280; text-transform: uppercase; letter-spacing: 0.5px;">@lang('shop::app.emails.orders.sku')</th>
                    <th style="font-family: 'Inter', sans-serif; text-align: left; padding: 12px 15px; font-size: 11px; font-weight: 700; color: #6B7280; text-transform: uppercase; letter-spacing: 0.5px;">@lang('shop::app.emails.orders.name')</th>
                    <th style="font-family: 'Inter', sans-serif; text-align: left; padding: 12px 15px; font-size: 11px; font-weight: 700; color: #6B7280; text-transform: uppercase; letter-spacing: 0.5px;">@lang('shop::app.emails.orders.qty')</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($shipment->items as $item)
                <tr style="border-top: 1px solid #e4e4e7;">
                    <td style="padding: 15px; font-family: 'Inter', sans-serif; font-size: 14px; color: #111827; vertical-align: top;">
                        {{ $item->sku }}
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
                        {{ $item->qty }}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

</div>

@endcomponent