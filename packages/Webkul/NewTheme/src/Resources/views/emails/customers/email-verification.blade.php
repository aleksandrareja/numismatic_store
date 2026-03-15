@component('shop::emails.layout')

<div style="background:#ffffff; padding:45px 35px; border-top:1px solid #e4e4e7; border-bottom:1px solid #e4e4e7; text-align:center;">

    <p style="font-weight:700; font-size:24px; color:#111827; line-height:32px; margin:0 0 24px 0;">
        @lang('shop::app.emails.dear', ['customer_name' => $customer->name]), 👋
    </p>

    <p style="font-size:16px; color:#4B5563; line-height:26px; margin:0 0 12px 0;">
        @lang('shop::app.emails.customers.verification.greeting')
    </p>

    <p style="font-size:16px; color:#4B5563; line-height:26px; margin:0 0 40px 0;">
        @lang('shop::app.emails.customers.verification.description')
    </p>

    <div style="text-align:center;">
        <a
            href="{{ route('shop.customers.verify', $customer->token) }}"
            style="background:#38200F; color:#ffffff; padding:18px 44px; 
                   text-decoration:none; font-weight:600; font-size:14px; 
                   letter-spacing:1px; border-radius:4px; display:inline-block; 
                   text-transform:uppercase; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
            @lang('shop::app.emails.customers.verification.verify-email')
        </a>
    </div>

</div>

@endcomponent