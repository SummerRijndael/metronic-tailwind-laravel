@component('mail::message')
    Mailer Test Successful!
    Hello there,

    This is a test email confirming that your Laravel application's SMTP configuration (using your Gmail App Password) is
    working correctly.

    "Hello world, this is my first app email!"

    If you received this, your email system is ready for 2FA and Email Verification.

    Thanks,




    {{ config('app.name') }}
@endcomponent
