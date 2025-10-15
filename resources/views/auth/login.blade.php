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


    <!-- Page -->
    <div class="flex grow">
        <!-- Wrapper -->
        <div class="page-bg flex grow items-center justify-center bg-center bg-no-repeat">

            <div class="kt-card w-full max-w-[370px]">
                <form action="{{ route('login') }}" method="POST" class="kt-card-content flex flex-col gap-5 p-10"
                    id="sign_in_form">
                    @csrf

                    <div class="mb-2.5 text-center">
                        <h3 class="mb-2.5 text-lg font-medium leading-none text-mono">
                            Sign in
                        </h3>
                        <div class="flex items-center justify-center font-medium">
                            <span class="me-1.5 text-sm text-secondary-foreground">
                                Need an account?
                            </span>
                            <a class="link text-sm" href="{{ route('register') }}">
                                Sign up
                            </a>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-2.5">
                        <a class="kt-btn kt-btn-outline justify-center" href="#">
                            <img alt="" class="size-3.5 shrink-0" src="assets/media/brand-logos/google.svg">
                            Use Google
                        </a>
                        <a class="kt-btn kt-btn-outline justify-center" href="#">
                            <img alt="" class="size-3.5 shrink-0 dark:hidden"
                                src="assets/media/brand-logos/apple-black.svg">
                            <img alt="" class="light:hidden size-3.5 shrink-0"
                                src="assets/media/brand-logos/apple-white.svg">
                            Use Apple
                        </a>
                    </div>

                    <div class="flex items-center gap-2">
                        <span class="w-full border-t border-border"></span>
                        <span class="text-xs font-medium uppercase text-muted-foreground">Or</span>
                        <span class="w-full border-t border-border"></span>
                    </div>

                    <div class="flex flex-col gap-1">
                        <label class="kt-form-label font-normal text-mono" for="email">Email</label>
                        <input class="kt-input" id="email" name="email" type="email" value="{{ old('email') }}"
                            required autofocus placeholder="email@email.com">
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <div class="flex flex-col gap-1">
                        <div class="flex items-center justify-between gap-1">
                            <label class="kt-form-label font-normal text-mono" for="password">Password</label>
                            <a class="kt-link shrink-0 text-sm" href="{{ route('password.request') }}">
                                Forgot Password?
                            </a>
                        </div>
                        <div class="kt-input" data-kt-toggle-password="true">
                            <input name="password" id="password" type="password" value="password" required
                                placeholder="Enter Password">
                            <button class="kt-btn kt-btn-sm kt-btn-ghost kt-btn-icon bg-transparent! -me-1.5"
                                data-kt-toggle-password-trigger="true" type="button">
                                <span class="kt-toggle-password-active:hidden">
                                    <i class="ki-filled ki-eye text-muted-foreground"></i>
                                </span>
                                <span class="kt-toggle-password-active:block hidden">
                                    <i class="ki-filled ki-eye-slash text-muted-foreground"></i>
                                </span>
                            </button>
                        </div>
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />

                    </div>

                    <label class="kt-label">
                        <input class="kt-checkbox kt-checkbox-sm" name="remember" type="checkbox" value="1">
                        <span class="kt-checkbox-label">Remember me</span>
                    </label>

                    <button class="kt-btn kt-btn-primary flex grow justify-center" type="submit">Sign In</button>
                </form>
            </div>


        </div>
        <!-- End of Wrapper -->
    </div>
    <!-- End of Page -->

</x-guest-layout>
