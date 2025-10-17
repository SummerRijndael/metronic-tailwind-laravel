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
                <form action="{{ route('register') }}" class="kt-card-content flex flex-col gap-5 p-10" id="sign_up_form"
                    method="POST">
                    @csrf

                    <div class="mb-2.5 text-center">
                        <h3 class="mb-2.5 text-lg font-medium leading-none text-mono">Sign up</h3>
                        <div class="flex items-center justify-center">
                            <span class="me-1.5 text-sm text-secondary-foreground">Already have an Account ?</span>
                            <a class="link text-sm" href="{{ route('login') }}">Sign In</a>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-2.5">
                        <a class="kt-btn kt-btn-outline justify-center" href="#">
                            <img alt="" class="size-3.5 shrink-0"
                                src="{{ asset('assets/media/brand-logos/google.svg') }}"> Use Google
                        </a>
                        <a class="kt-btn kt-btn-outline justify-center" href="#">
                            <img alt="" class="size-3.5 shrink-0 dark:hidden"
                                src="{{ asset('assets/media/brand-logos/apple-black.svg') }}">
                            <img alt="" class="light:hidden size-3.5 shrink-0"
                                src="{{ asset('assets/media/brand-logos/apple-white.svg') }}"> Use Apple
                        </a>
                    </div>

                    <div class="flex items-center gap-2">
                        <span class="w-full border-t border-border"></span>
                        <span class="text-xs uppercase text-secondary-foreground">or</span>
                        <span class="w-full border-t border-border"></span>
                    </div>

                    <div class="flex flex-col gap-1">
                        <label class="kt-form-label text-mono">Name</label>
                        <input class="kt-input" name="name" type="text" value="{{ old('name') }}"
                            placeholder="Your Name">
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>

                    <div class="flex flex-col gap-1">
                        <label class="kt-form-label text-mono">Email</label>
                        <input class="kt-input" name="email" type="email" value="{{ old('email') }}"
                            placeholder="email@email.com">
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <div class="flex flex-col gap-1">
                        <label class="kt-form-label font-normal text-mono">Password</label>
                        <div class="kt-input" data-kt-toggle-password="true">
                            <input name="password" type="password" placeholder="Enter Password">
                            <button class="kt-btn kt-btn-sm kt-btn-ghost kt-btn-icon bg-transparent! -me-1.5"
                                data-kt-toggle-password-trigger="true" type="button">
                                <span class="kt-toggle-password-active:hidden"><i
                                        class="ki-filled ki-eye text-muted-foreground"></i></span>
                                <span class="kt-toggle-password-active:block hidden"><i
                                        class="ki-filled ki-eye-slash text-muted-foreground"></i></span>
                            </button>
                        </div>
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <div class="flex flex-col gap-1">
                        <label class="kt-form-label font-normal text-mono">Confirm Password</label>
                        <div class="kt-input" data-kt-toggle-password="true">
                            <input name="password_confirmation" type="password" placeholder="Re-enter Password">
                            <button class="kt-btn kt-btn-sm kt-btn-ghost kt-btn-icon bg-transparent! -me-1.5"
                                data-kt-toggle-password-trigger="true" type="button">
                                <span class="kt-toggle-password-active:hidden"><i
                                        class="ki-filled ki-eye text-muted-foreground"></i></span>
                                <span class="kt-toggle-password-active:block hidden"><i
                                        class="ki-filled ki-eye-slash text-muted-foreground"></i></span>
                            </button>
                        </div>
                    </div>



                    <button class="kt-btn kt-btn-primary flex grow justify-center">Sign up</button>
                </form>
            </div>
        </div>
        <!-- End of Wrapper -->
    </div>
    <!-- End of Page -->

</x-guest-layout>
