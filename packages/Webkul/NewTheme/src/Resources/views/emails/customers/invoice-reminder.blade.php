@component('shop::emails.layout')

<div style="background:#ffffff; padding:45px 35px; border-top:1px solid #e4e4e7; border-bottom:1px solid #e4e4e7; text-align:center;">

    <p style="font-family: 'Poppins', 'Helvetica Neue', Helvetica, Arial, sans-serif; font-weight:700; font-size:24px; color:#111827; line-height:32px; margin:0 0 24px 0;">
        @lang('shop::app.emails.dear', ['customer_name' => $invoice->order->customer_full_name]),
    </p>

    <p style="font-family: 'Inter', 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size:16px; color:#4B5563; line-height:26px; margin:0 0 16px 0;">
        @lang('shop::app.emails.customers.reminder.invoice-overdue')
    </p>

    <p style="font-family: 'Inter', 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size:15px; color:#6B7280; line-height:24px; margin:24px 0 0 0; font-style: italic;">
        @lang('shop::app.emails.customers.reminder.already-paid')
    </p>

</div>

@endcomponent