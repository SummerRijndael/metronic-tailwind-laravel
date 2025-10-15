<x-guest-layout>

    {{-- Page Background Styles --}}
    @push('styles')
        <style>
            .page-bg {
                background-image: url('{{ asset('assets/media/images/2600x1200/bg-10.png') }}');
            }

            .dark .page-bg {
                background-image: url('{{ asset('assets/media/images/2600x1200/bg-10-dark.png') }}');
            }
        </style>
    @endpush

    {{-- Page Container --}}
    <div class="page-bg flex grow items-center justify-center bg-center bg-no-repeat">

        {{-- Verification Card --}}
        <div class="kt-card w-full max-w-[380px] p-8 shadow-lg">

            {{-- Header --}}
            <h3 class="mb-6 text-center text-xl font-bold">Email Verification</h3>

            {{-- Success / Status Alert --}}
            @if (session('status'))
                <div class="mb-4 rounded-lg bg-green-100 p-3 text-sm text-green-700">
                    {{ session('status') }}
                </div>
            @endif

            {{-- Request Section --}}
            <div id="request-section" class="mb-6">
                <p class="mb-4 text-center text-sm text-gray-600">
                    Click the button below to send a one-time verification code to your email address.
                    The code is valid for 5 minutes.
                </p>

                <form action="{{ route('two-factor.email.request') }}" method="POST">
                    @csrf
                    <button type="submit" class="kt-btn kt-btn-primary w-full justify-center">
                        Request Verification Code
                    </button>
                </form>
            </div>

            {{-- Verify Section --}}
            <div id="verify-section">
                <h4 class="mb-4 text-center text-lg font-semibold">Verify Code</h4>

                <form action="{{ route('two-factor.email.verify') }}" method="POST">
                    @csrf

                    {{-- OTP Input --}}
                    <div class="mb-4 flex flex-col gap-1">
                        <label for="otp_code" class="kt-form-label font-normal text-mono">
                            Enter 6-Digit Code
                        </label>

                        <input id="otp_code" name="otp_code" type="text" inputmode="numeric" maxlength="6" required
                            autofocus placeholder="######" value="{{ old('otp_code') }}" class="kt-input">

                        @error('otp_code')
                            <span class="mt-1 text-sm text-red-600">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Submit --}}
                    <button type="submit" class="kt-btn kt-btn-success w-full justify-center">
                        Verify & Log In
                    </button>
                </form>
            </div>

        </div>
        {{-- End Card --}}
    </div>
    {{-- End Page Container --}}

</x-guest-layout>
