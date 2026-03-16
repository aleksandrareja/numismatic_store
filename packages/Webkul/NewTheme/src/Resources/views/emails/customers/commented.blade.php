@component('shop::emails.layout')

<div style="background:#ffffff; padding:45px 35px; border-top:1px solid #e4e4e7; border-bottom:1px solid #e4e4e7; text-align:center;">

    <p style="font-family: 'Poppins', 'Helvetica Neue', Helvetica, Arial, sans-serif; font-weight:700; font-size:24px; color:#111827; line-height:32px; margin:0 0 24px 0;">
        @lang('shop::app.emails.dear', ['customer_name' => $customerNote->customer->name]), 
    </p>

    <div style="font-family: 'Inter', 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size:16px; color:#4B5563; line-height:26px;">
        <p style="margin: 0 0 20px 0;">
            @lang('shop::app.emails.customers.commented.description', ['note' => ''])
        </p>
        
        <div style="background-color: #F9FAFB; border-left: 4px solid #38200F; padding: 20px; margin: 10px 0; text-align: left; border-radius: 4px;">
            <p style="margin: 0; font-style: italic; color: #1F2937;">
                "{{ $customerNote->note }}"
            </p>
        </div>
    </div>

</div>

@endcomponent