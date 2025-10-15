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
        <div class="kt-card w-full max-w-[380px]" id="two-factor-setup">
            <form action="{{ route('two-factor.login') }}" method="POST" class="kt-card-content flex flex-col gap-5 p-10">
                @csrf

                <img alt="image" class="mb-2 h-20 dark:hidden" src="{{ asset('assets/media/illustrations/5.svg') }}">
                <img alt="image" class="light:hidden mb-2 h-20"
                    src="{{ asset('assets/media/illustrations/5-dark.svg') }}">

                <div class="mb-2 text-center">
                    <h3 class="mb-5 text-lg font-medium text-mono" id="challenge-title">Verify your
                        account</h3>
                    <div class="flex flex-col">
                        <span class="mb-1.5 text-sm text-secondary-foreground" id="challenge-instructions">
                            Enter the verification code from your authenticator app.
                        </span>
                    </div>
                </div>

                {{-- ERROR HANDLING: Critical for showing error messages --}}
                @error('code')
                    <div class="text-center text-sm font-medium text-red-600">{{ $message }}</div>
                @enderror
                @error('recovery_code')
                    <div class="text-center text-sm font-medium text-red-600">{{ $message }}</div>
                @enderror

                {{-- HIDDEN INPUT: Used by JS to submit either 'code' or 'recovery_code' --}}
                <input type="hidden" name="code" id="auth_code_input">


                {{-- 1. TOTP CODE INPUT FIELDS (DEFAULT) --}}
                <div id="totp-fields" class="flex flex-wrap justify-center gap-2.5">
                    @for ($i = 0; $i < 6; $i++)
                        <input
                            class="kt-input focus:border-primary/10 focus:ring-3 focus:ring-primary/10 size-10 shrink-0 px-0 text-center"
                            maxlength="1" data-index="{{ $i }}" placeholder="" type="text">
                    @endfor
                </div>

                {{-- 2. RECOVERY CODE INPUT FIELD (HIDDEN BY DEFAULT) --}}
                <div id="recovery-field" style="display: none;" class="flex flex-col gap-1">
                    <label class="kt-form-label font-normal text-mono" for="recovery_input_text">Recovery Code</label>
                    <input class="kt-input" id="recovery_input_text" type="text"
                        placeholder="Enter one of your backup codes">
                </div>

                <button type="submit" class="kt-btn kt-btn-primary flex grow justify-center" id="submit-button">
                    Continue
                </button>

                {{-- TOGGLE LINKS SECTION --}}
                <div class="mt-1 flex flex-col gap-1 border-t border-border pt-4 text-center">

                    {{-- NEW: EMAIL OTP OPTION --}}
                    <a href="{{ URL::temporarySignedRoute('two-factor.email.show', now()->addMinutes(5)) }}"
                        class="kt-link shrink-0 text-sm">
                        Need a code? Use Email Verification
                    </a>

                    {{-- EXISTING: RECOVERY CODE OPTION (Toggles the form) --}}
                    <a href="#" id="toggle-auth-mode" class="kt-link shrink-0 text-sm">
                        Lost your device? Use a recovery code
                    </a>
                </div>

            </form>
        </div>
    </div>

    @push('scripts')
        <script>
            // --- Existing JavaScript Logic (Unchanged) ---
            const setupDiv = document.getElementById('two-factor-setup');
            const totpFields = document.getElementById('totp-fields');
            const recoveryField = document.getElementById('recovery-field');
            const toggleLink = document.getElementById('toggle-auth-mode');
            const hiddenInput = document.getElementById('auth_code_input');
            const recoveryTextInput = document.getElementById('recovery_input_text');
            const challengeTitle = document.getElementById('challenge-title');
            const challengeInstructions = document.getElementById('challenge-instructions');

            let isRecoveryMode = false;

            // --- TOTP Code Input Logic ---
            const totpInputs = totpFields.querySelectorAll('input[type="text"]');

            function updateHiddenInput() {
                if (!isRecoveryMode) {
                    // Combine 6 digits for TOTP mode
                    hiddenInput.value = Array.from(totpInputs).map(i => i.value).join('');
                } else {
                    // Use single text input value for Recovery mode
                    hiddenInput.value = recoveryTextInput.value;
                }
            }

            totpInputs.forEach((input, index) => {
                input.addEventListener('input', () => {
                    if (input.value.length > 0 && index < totpInputs.length - 1) {
                        totpInputs[index + 1].focus();
                    }
                    updateHiddenInput();
                });

                input.addEventListener('keydown', (e) => {
                    if (e.key === 'Backspace' && input.value === '' && index > 0) {
                        totpInputs[index - 1].focus();
                    }
                });
            });

            // --- Recovery Code Input Logic ---
            recoveryTextInput.addEventListener('input', updateHiddenInput);

            // --- Mode Toggle Logic ---
            toggleLink.addEventListener('click', function(e) {
                e.preventDefault();
                // Ensure we only toggle between TOTP and Recovery, not touching Email OTP
                isRecoveryMode = !isRecoveryMode;

                if (isRecoveryMode) {
                    // Switch to Recovery Mode
                    totpFields.style.display = 'none';
                    recoveryField.style.display = 'flex';
                    hiddenInput.name = 'recovery_code'; // CRITICAL: Change input name for Fortify
                    toggleLink.textContent = 'Use authenticator code';
                    challengeTitle.textContent = 'Enter Recovery Code';
                    challengeInstructions.textContent = 'Enter one of your single-use recovery codes.';
                    // Clear TOTP fields and focus recovery field
                    totpInputs.forEach(i => i.value = '');
                    recoveryTextInput.focus();
                } else {
                    // Switch back to TOTP Mode
                    recoveryField.style.display = 'none';
                    totpFields.style.display = 'flex';
                    hiddenInput.name = 'code'; // CRITICAL: Change input name back to 'code'
                    toggleLink.textContent = 'Lost your device? Use a recovery code';
                    challengeTitle.textContent = 'Verify your account';
                    challengeInstructions.textContent = 'Enter the verification code from your authenticator app.';
                    // Clear recovery field and focus first TOTP input
                    recoveryTextInput.value = '';
                    totpInputs[0].focus();
                }
                updateHiddenInput(); // Update the hidden field's value
            });

            // Initialize the hidden input's name (it starts as 'code')
            hiddenInput.name = 'code';
        </script>
    @endpush

</x-guest-layout>
