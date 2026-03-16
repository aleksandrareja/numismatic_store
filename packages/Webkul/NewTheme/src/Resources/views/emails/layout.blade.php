<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

        <link
            href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Poppins:wght@400;500;600;700;800&family=DM+Serif+Display&display=swap"
            rel="stylesheet"
        />
    </head>

    <body>
        <div style="max-width: 640px; margin-left: auto; margin-right: auto;">
            <div style="padding: 30px;">
                <!-- Email Header -->
                <div style="margin-bottom: 30px; margin-left: auto; margin-right: auto; text-align: center;">
                    <a href="{{ route('shop.home.index') }}">
                        <img
                            src="{{ asset('storage/'. core()->getCurrentChannel()->logo) }}"
                            alt="{{ config('app.name') }}"
                            style="width: 170px; height: auto;"
                        />
                    </a>
                </div>

                <!-- Email Content -->
                {{ $slot }}

                <!-- Email Footer -->
                <p style="font-size: 14px;color: #202B3C;line-height: 24px; text-align: center;">
                    @lang('shop::app.emails.thanks', [
                        'link' => 'mailto:' . core()->getContactEmailDetails()['email'],
                        'email' => core()->getContactEmailDetails()['email'],
                        'style' => 'color: #38200F;'
                    ])
                </p>
            </div>
        </div>
    </body>
</html>
