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

    <div class="page-bg flex grow items-center justify-center bg-center bg-no-repeat">
        <div class="kt-card w-full max-w-[370px]">
            <form method="POST" action="{{ route('password.update') }}" class="kt-card-content flex flex-col gap-5 p-10"
                id="reset_password_change_password_form">
                @csrf

                <!-- Reset Token -->
                <input type="hidden" name="token" value="{{ $request->route('token') }}">

                <!-- Header -->
                <div class="text-center">
                    <h3 class="text-lg font-medium text-mono">Reset Password</h3>
                    <span class="text-sm text-secondary-foreground">
                        Enter your new password
                    </span>
                </div>

                <!-- Email -->
                <input type="hidden" name="email" value="{{ old('email', $request->email) }}">

                <!-- New Password -->
                <div class="flex flex-col gap-1">
                    <label for="password" class="kt-form-label text-mono">New Password</label>
                    <label class="kt-input" data-kt-toggle-password="true">
                        <input id="password" name="password" type="password" placeholder="Enter a new password"
                            required autocomplete="new-password">
                        <div class="kt-btn kt-btn-sm kt-btn-ghost kt-btn-icon bg-transparent! -me-1.5"
                            data-kt-toggle-password-trigger="true">
                            <span class="kt-toggle-password-active:hidden">
                                <i class="ki-filled ki-eye text-muted-foreground"></i>
                            </span>
                            <span class="kt-toggle-password-active:block hidden">
                                <i class="ki-filled ki-eye-slash text-muted-foreground"></i>
                            </span>
                        </div>
                    </label>
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <!-- Confirm Password -->
                <div class="flex flex-col gap-1">
                    <label for="password_confirmation" class="kt-form-label font-normal text-mono">Confirm New
                        Password</label>
                    <label class="kt-input" data-kt-toggle-password="true">
                        <input id="password_confirmation" name="password_confirmation" type="password"
                            placeholder="Re-enter a new password" required autocomplete="new-password">
                        <div class="kt-btn kt-btn-sm kt-btn-ghost kt-btn-icon bg-transparent! -me-1.5"
                            data-kt-toggle-password-trigger="true">
                            <span class="kt-toggle-password-active:hidden">
                                <i class="ki-filled ki-eye text-muted-foreground"></i>
                            </span>
                            <span class="kt-toggle-password-active:block hidden">
                                <i class="ki-filled ki-eye-slash text-muted-foreground"></i>
                            </span>
                        </div>
                    </label>
                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                </div>

                <!-- Submit -->
                <button type="submit" class="kt-btn kt-btn-primary flex grow justify-center">
                    Submit
                </button>
            </form>
        </div>
    </div>
</x-guest-layout>
