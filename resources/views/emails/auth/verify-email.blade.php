@component('mail::message')
    Verify Your Email Address
    Hello {{ $user->name }},

    Thank you for registering with our service! Before you can start using your account, we need you to verify your email
    address.

    Please click the button below to complete your registration. This ensures your account is secure and ready to go.

    @component('mail::button', ['url' => $verificationUrl])
        Verify Email Address
    @endcomponent

    If you did not create an account, no further action is required.

    Thanks,
    {{ config('app.name') }}
@endcomponent
