@extends('layouts.main.base')


@section('content')

<div class="flex items-center justify-center grow bg-center bg-no-repeat page-bg">
    <div class="kt-card max-w-[400px] w-full" id="two-factor-setup">
        <form action="{{ route('2fa.setup.verify') }}" method="POST" class="kt-card-content flex flex-col gap-5 p-10">
            @csrf

            <div class="text-center mb-4">
                <h3 class="text-lg font-medium text-mono mb-2">Two-Factor Authentication Setup</h3>
                <p class="text-sm text-secondary-foreground">
                    Scan this QR code in your authenticator app, then enter the 6-digit code below.
                </p>
            </div>

            {{-- QR Code --}}
            <div class="flex justify-center mb-4">
                {!! $qrCode ? $qrCode : null !!}
            </div>

            {{-- Recovery Codes --}}
            <div class="mb-4">
                <h4 class="font-medium mb-1">Recovery Codes (store safely):</h4>
                <ul class="list-disc list-inside text-sm text-gray-700">
                    @foreach($recoveryCodes as $code)
                        <li>{{ $code }}</li>
                    @endforeach
                </ul>
            </div>

            {{-- Hidden input to combine 6-digit code --}}
            <input type="hidden" name="code" id="two_factor_code">

            {{-- Six individual inputs --}}
            <div class="flex justify-center gap-2 mb-4">
                @for ($i = 0; $i < 6; $i++)
                    <input type="text" maxlength="1" placeholder=""
                           class="kt-input text-center w-12 h-12 text-lg focus:ring-2 focus:ring-primary"
                           data-index="{{ $i }}">
                @endfor
            </div>

            <button type="submit" class="kt-btn kt-btn-primary flex justify-center grow">
                Verify & Enable 2FA
            </button>

            @error('code')
                <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
            @enderror
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const inputs = document.querySelectorAll('#two-factor-setup input[type="text"]');
    const hiddenInput = document.getElementById('two_factor_code');

    inputs.forEach((input, index) => {
        input.addEventListener('input', () => {
            // Move to next input automatically
            if (input.value.length > 0 && index < inputs.length - 1) {
                inputs[index + 1].focus();
            }

            // Combine values into hidden input
            hiddenInput.value = Array.from(inputs).map(i => i.value).join('');
        });

        input.addEventListener('keydown', (e) => {
            if (e.key === 'Backspace' && input.value === '' && index > 0) {
                inputs[index - 1].focus();
            }
        });
    });
</script>
@endpush


