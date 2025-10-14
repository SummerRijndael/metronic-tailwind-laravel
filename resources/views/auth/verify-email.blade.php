<x-guest-layout>
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

    <div class="page-bg flex min-h-screen grow items-center justify-center bg-center bg-no-repeat">
        <div class="kt-card w-full max-w-[440px] rounded-3xl bg-white shadow-xl dark:bg-gray-800">
            <div class="kt-card-content p-10" id="check_email_form">

                <!-- Illustration -->
                <div class="flex justify-center py-10">
                    <img alt="image" class="max-h-[130px] dark:hidden" src="assets/media/illustrations/30.svg" />
                    <img alt="image" class="light:hidden max-h-[130px]" src="assets/media/illustrations/30-dark.svg" />
                </div>

                <!-- Heading -->
                <h3 class="mb-3 text-center text-lg font-medium text-mono">
                    Check your email
                </h3>

                <!-- Core message -->
                <div class="mb-7.5 text-center text-sm text-secondary-foreground">
                    Please click the link sent to your email
                    <span class="text-sm font-medium text-mono">
                        {{ Auth::user()->email ?? 'your-email@example.com' }}
                    </span>
                    <br />
                    to verify your account. Thank you.
                </div>

                <!-- Success message (if resend) -->
                @if (session('status') == 'verification-link-sent')
                    <div
                        class="mb-6 rounded-xl border-l-4 border-green-500 bg-green-100 p-4 text-sm font-medium text-green-700 shadow-md dark:bg-green-800 dark:text-green-300">
                        <div class="flex items-center">
                            <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span>{{ __('A new verification link has been sent to your email address.') }}</span>
                        </div>
                    </div>
                @endif

                <!-- Action Buttons -->
                <div class="mb-5 flex justify-center gap-3">
                    <!-- Back to Home -->
                    <a href="{{ url('/') }}" class="kt-btn kt-btn-primary flex justify-center">
                        Back to Home
                    </a>
                </div>

                <!-- Resend Email -->
                <div class="text-2sm flex items-center justify-center gap-1">
                    <span class="text-secondary-foreground">
                        Didn’t receive an email?
                    </span>
                    <form method="POST" action="{{ route('verification.send') }}">
                        @csrf
                        <button type="submit" class="kt-link hover:text-primary font-medium">
                            Resend
                        </button>
                    </form>
                </div>

                <!-- Logout -->
                <div class="mt-4 flex justify-center">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="kt-btn kt-btn-outline text-sm text-gray-500">
                            Log Out
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
