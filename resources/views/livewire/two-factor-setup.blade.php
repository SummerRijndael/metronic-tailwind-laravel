<div>
    <div class="kt-card shadow-lg">
        <!-- Stepper Header -->
        <div class="kt-card-header flex justify-center gap-4 py-5">
            @for ($i = 1; $i <= 4; $i++)
                <div class="flex flex-col items-center">
                    <div
                        class="{{ $step === $i ? 'bg-primary text-white' : 'bg-gray-300 text-gray-700' }} flex h-8 w-8 items-center justify-center rounded-full transition-all">
                        {{ $i }}
                    </div>
                    <span class="mt-1 text-xs">Step {{ $i }}</span>
                </div>
            @endfor
        </div>

        <!-- Step Content -->
        <div class="kt-card-content p-6">

            <!-- Step 1: Password -->
            @if ($step === 1)

                <h3 class="mb-4 text-lg font-semibold">Two-Factor Authentication (2FA)</h3>
                <div class="flex flex-wrap items-center gap-3.5">
                    <div class="flex items-center">
                        <div class="relative size-[50px] shrink-0">
                            <svg class="fill-muted/30 h-full w-full stroke-border" fill="none" height="48"
                                viewBox="0 0 44 48" width="44" xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M16 2.4641C19.7128 0.320509 24.2872 0.320508 28 2.4641L37.6506 8.0359C41.3634 10.1795 43.6506 14.141 43.6506
   18.4282V29.5718C43.6506 33.859 41.3634 37.8205 37.6506 39.9641L28 45.5359C24.2872 47.6795 19.7128 47.6795 16 45.5359L6.34937
   39.9641C2.63655 37.8205 0.349365 33.859 0.349365 29.5718V18.4282C0.349365 14.141 2.63655 10.1795 6.34937 8.0359L16 2.4641Z"
                                    fill="">
                                </path>
                                <path
                                    d="M16.25 2.89711C19.8081 0.842838 24.1919 0.842837 27.75 2.89711L37.4006 8.46891C40.9587 10.5232 43.1506 14.3196 43.1506
   18.4282V29.5718C43.1506 33.6804 40.9587 37.4768 37.4006 39.5311L27.75 45.1029C24.1919 47.1572 19.8081 47.1572 16.25 45.1029L6.59937
   39.5311C3.04125 37.4768 0.849365 33.6803 0.849365 29.5718V18.4282C0.849365 14.3196 3.04125 10.5232 6.59937 8.46891L16.25 2.89711Z"
                                    stroke="">
                                </path>
                            </svg>
                            <div
                                class="absolute start-2/4 top-2/4 -translate-x-2/4 -translate-y-2/4 leading-none rtl:translate-x-2/4">
                                <i class="ki-filled ki-shield-tick text-xl text-muted-foreground">
                                </i>
                            </div>
                        </div>
                    </div>
                    <div class="flex flex-col gap-px">
                        <span class="text-sm font-medium text-mono">
                            Authenticator App (TOTP)
                        </span>
                        <span class="text-sm font-medium text-secondary-foreground">
                            Elevate protection with an authenticator app for two-factor authentication.
                        </span>
                    </div>
                </div>
                <form wire:submit.prevent="verifyPassword">
                    <div class="mb-4 mt-6 flex w-full items-center justify-between">
                        <label class="kt-form-label max-w-56">
                            Password
                        </label>
                    </div>


                    <div class="flex w-full grow flex-col items-start gap-3">
                        <div class="kt-input" data-kt-toggle-password="true">
                            <input name="password" wire:model.defer="password" id="password" type="password"
                                value="password" required placeholder="Enter Password">
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
                        <span class="kt-form-description text-2sm">
                            Enter your password to setup Two-Factor authentication
                        </span>

                    </div>
                    @if ($errorMessage)
                        <p class="mt-1 text-red-600">{{ $errorMessage }}</p>
                    @endif

                    <div class="mb-1 flex justify-end">
                        <button type="submit" class="kt-btn kt-btn-primary">Proceed</button>
                    </div>


                </form>
            @elseif($step === 2 && $action === 'enable')
                <!-- Step 2: QR & Verification -->
                <h3 class="mb-2 text-lg font-semibold">Scan QR Code</h3>
                <p class="mb-4 text-sm">Scan using an authenticator app. Or use the key below.</p>
                <div class="mb-4">
                    <img src="{{ $qrCode }}" class="mx-auto h-40 w-40 rounded border bg-white p-2">
                    <p class="mt-2 cursor-pointer select-all text-center font-mono"
                        onclick="navigator.clipboard.writeText('{{ $twoFactorSecret }}')">Key: {{ $twoFactorSecret }}
                    </p>
                </div>
                <div class="flex gap-2">
                    <input type="text" maxlength="6" wire:model.live="verificationCode" placeholder="000000"
                        class="kt-input flex-1 rounded border p-2">
                    <button wire:click="confirmTwoFactor" class="kt-btn kt-btn-primary"
                        @if (strlen($verificationCode) !== 6) disabled @endif>Verify</button>
                </div>
                @if ($errorMessage)
                    <p class="mt-2 text-red-600">{{ $errorMessage }}</p>
                @endif
            @elseif($step === 2 && $action === 'manage')
                <!-- Step 2: Manage Existing -->
                <h3 class="mb-2 text-lg font-semibold">Manage 2FA</h3>
                <p class="mb-4 text-sm">Your 2FA is active. Enter a code to disable or regenerate recovery codes.</p>
                <div class="mb-4 flex gap-2">
                    <input type="text" maxlength="6" wire:model.live="verificationCode" placeholder="000000"
                        class="kt-input flex-1 rounded border p-2">
                </div>
                @if ($errorMessage)
                    <p class="mt-2 text-red-600">{{ $errorMessage }}</p>
                @endif
                <div class="flex justify-end gap-2">
                    <button wire:click="disableTwoFactor" class="kt-btn kt-btn-danger"
                        @if (strlen($verificationCode) !== 6) disabled @endif>Disable 2FA</button>
                    <button wire:click="regenerateRecoveryCodes" class="kt-btn kt-btn-secondary"
                        @if (strlen($verificationCode) !== 6) disabled @endif>Regenerate Recovery Codes</button>
                </div>
            @elseif($step === 3)
                <!-- Step 3: Recovery Codes -->
                <h3 class="mb-2 text-lg font-semibold">Recovery Codes</h3>
                <p class="mb-4 text-sm">Save these codes securely. They can be used to access your account if you lose
                    your device.</p>
                <ul class="mb-4 grid grid-cols-2 gap-2 font-mono text-sm">
                    @foreach ($recoveryCodes as $code)
                        <li class="rounded bg-yellow-100 p-2">{{ $code }}</li>
                    @endforeach
                </ul>
                <button wire:click="nextStep" class="kt-btn kt-btn-primary w-full">I Have Saved My Codes</button>
            @elseif($step === 4)
                <h3 class="text-lg font-semibold">Two factor authentication setup</h3>
                @if ($action === 'disable')
                    Two-factor authentication has been successfully disabled.
                @elseif($action === 'manage')
                    Your new recovery codes have been saved. Please make sure you have saved them securely.
                @else
                    Two-factor authentication is now active on your account. You're all set.
                @endif

                <div class="mb-1 flex justify-end">
                    <button wire:click="resetFlow" class="kt-btn kt-btn-success">Done</button>
                </div>
            @endif
        </div>
    </div>
</div>
