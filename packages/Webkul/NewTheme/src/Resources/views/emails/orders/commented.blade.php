@component('shop::emails.layout')

<div style="background:#ffffff; padding:45px 35px; border-top:1px solid #e4e4e7; border-bottom:1px solid #e4e4e7; text-align:center;">

    <p style="font-family: 'Poppins', 'Helvetica Neue', Helvetica, Arial, sans-serif; font-weight:700; font-size:24px; color:#111827; line-height:32px; margin:0 0 24px 0;">
        @lang('shop::app.emails.dear', ['customer_name' => $comment->order->customer_full_name]),
    </p>

    <p style="font-family: 'Inter', 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size:16px; color:#4B5563; line-height:26px; margin:0 0 30px 0;">
        {!! __('shop::app.emails.orders.commented.title', [
            'order_id'   => '<a href="' . route('shop.customers.account.orders.view', $comment->order_id) . '" style="color: #38200F; font-weight: 600; text-decoration: underline;">#' . $comment->order->increment_id . '</a>',
            'created_at' => core()->formatDate($comment->order->created_at, 'Y-m-d H:i:s')
        ]) !!}
    </p>

    <div style="background-color: #F9FAFB; border-left: 4px solid #38200F; padding: 24px; text-align: left; border-radius: 4px;">
        <p style="font-family: 'Inter', sans-serif; font-size: 15px; line-height: 24px; color: #1F2937; margin: 0; font-style: italic;">
            "{{ $comment->comment }}"
        </p>
    </div>

</div>

@endcomponent