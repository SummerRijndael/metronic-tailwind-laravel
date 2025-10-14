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
        <div class="kt-card w-full max-w-[370px] rounded-xl bg-white shadow-lg dark:bg-gray-900">
            <form method="POST" action="{{ route('password.email') }}" id="reset_password_enter_email_form"
                class="kt-card-content flex flex-col gap-6 p-10">
                @csrf

                <!-- Header -->
                <div class="text-center">
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-gray-100">
                        {{ __('Forgot Password') }}
                    </h3>
                    <span class="text-sm text-gray-500 dark:text-gray-400">
                        {{ __('Enter your email to receive a password reset link') }}
                    </span>
                </div>

                <!-- Session Status -->
                @if (session('status'))
                    <div
                        class="rounded-lg bg-green-50 py-2 text-center text-sm font-medium text-green-600 dark:bg-green-800/30">
                        {{ session('status') }}
                    </div>
                @endif

                <!-- Email Field -->
                <div class="flex flex-col gap-1">
                    <label for="email" class="kt-form-label font-medium text-gray-700 dark:text-gray-300">
                        {{ __('Email Address') }}
                    </label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                        placeholder="you@example.com"
                        class="kt-input rounded-lg border-gray-300 px-3 py-2 text-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100" />
                    @error('email')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Submit -->
                <div class="flex justify-center">
                    <button type="submit"
                        class="kt-btn kt-btn-primary flex w-full items-center justify-center gap-2 rounded-lg bg-indigo-600 py-2.5 font-semibold text-white transition duration-150 ease-in-out hover:bg-indigo-700">
                        {{ __('Send Reset Link') }}
                        <i class="ki-filled ki-black-right text-base"></i>
                    </button>
                </div>

                <!-- Back to login -->
                <div class="mt-4 text-center">
                    <a href="{{ route('login') }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-700">
                        ← Back to login
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-guest-layout>
