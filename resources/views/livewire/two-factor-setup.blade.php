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
                <form wire:submit.prevent="verifyPassword">
                    <div class="mb-4">
                        <label class="mb-2 block text-sm font-medium">Password</label>
                        <input type="password" wire:model.defer="password" placeholder="Enter Password"
                            class="kt-input w-full rounded border border-gray-300 p-2">
                        @if ($errorMessage)
                            <p class="mt-1 text-red-600">{{ $errorMessage }}</p>
                        @endif
                    </div>
                    <button type="submit" class="kt-btn kt-btn-primary">Proceed</button>
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
                <div class="flex gap-2">
                    <button wire:click="disableTwoFactor" class="kt-btn kt-btn-danger"
                        @if (strlen($verificationCode) !== 6) disabled @endif>Disable 2FA</button>
                    <button wire:click="regenerateRecoveryCodes" class="kt-btn kt-btn-secondary"
                        @if (strlen($verificationCode) !== 6) disabled @endif>Regenerate Recovery Codes</button>
                </div>
                @if ($errorMessage)
                    <p class="mt-2 text-red-600">{{ $errorMessage }}</p>
                @endif
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
                <h3 class="text-lg font-semibold">2FA {{ $action === 'enable' ? 'Enabled' : 'Disabled' }}</h3>
                <p class="mb-4 text-sm">Process complete.</p>
                <button wire:click="resetFlow" class="kt-btn kt-btn-success">Done</button>
            @endif
        </div>
    </div>
</div>
