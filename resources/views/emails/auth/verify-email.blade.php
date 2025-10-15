@component('mail::message')
    # Account Activation Required 🔑

    Hello **{{ $user->name }}**,

    Thank you for joining the {{ config('app.name') }} community! We're excited to have you on board.

    Before you can access your dashboard and start using all our features, we need you to confirm that this is your email
    address. This is a crucial step to **secure your account**.

    Please click the button below to complete the verification process immediately.

    @component('mail::button', ['url' => $verificationUrl])
        Verify My Account
    @endcomponent

    ***

    **What if the button doesn't work?**

    If you're having trouble clicking the "Verify My Account" button, you can copy and paste the URL below into your web
    browser:
    [{{ $verificationUrl }}]({{ $verificationUrl }})

    ***

    **Didn't sign up?**

    If you did not register for an account, please disregard this email. No further action is required and your email
    address will be removed from our sign-up attempts.

    Thanks,

    The Team at {{ config('app.name') }}
@endcomponent
