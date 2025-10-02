<x-guest-layout>
@push('styles')
<style>
   .page-bg {
       background-image: url('{{ asset("assets/media/images/2600x1200/bg-10.png") }}');
   }
   .dark .page-bg {
       background-image: url('{{ asset("assets/media/images/2600x1200/bg-10-dark.png") }}');
   }
</style>
@endpush

<div class="flex items-center justify-center grow bg-center bg-no-repeat page-bg">
    <div class="kt-card max-w-[380px] w-full" id="two-factor-setup">
        {{-- Form starts here --}}
        <form action="{{ route('two-factor.login') }}" method="POST" class="kt-card-content flex flex-col gap-5 p-10">
            @csrf

            <img alt="image" class="dark:hidden h-20 mb-2" src="{{ asset('assets/media/illustrations/5.svg') }}">
            <img alt="image" class="light:hidden h-20 mb-2" src="{{ asset('assets/media/illustrations/5-dark.svg') }}">

            <div class="text-center mb-2">
                <h3 class="text-lg font-medium text-mono mb-5">Verify your account</h3>
                <div class="flex flex-col">
                    <span class="text-sm text-secondary-foreground mb-1.5">
                        Enter the verification code from your authenticator app.
                    </span>
                </div>
            </div>

            {{-- Single hidden input for Fortify --}}
            <input type="hidden" name="code" id="2fa_code">

            {{-- Six-digit input fields --}}
            <div class="flex flex-wrap justify-center gap-2.5">
                @for ($i = 0; $i < 6; $i++)
                <input class="kt-input focus:border-primary/10 focus:ring-3 focus:ring-primary/10 size-10 shrink-0 px-0 text-center"
                       maxlength="1"
                       data-index="{{ $i }}"
                       placeholder=""
                       type="text">
                @endfor
            </div>

            <button type="submit" class="kt-btn kt-btn-primary flex justify-center grow">
                Continue
            </button>
        </form>
    </div>
</div>

@push('scripts')
<script>
    const inputs = document.querySelectorAll('#two-factor-setup input[type="text"]');
    const hiddenInput = document.getElementById('2fa_code');

    inputs.forEach((input, index) => {
        input.addEventListener('input', () => {
            // Move to next input
            if (input.value.length > 0 && index < inputs.length - 1) {
                inputs[index + 1].focus();
            }

            // Update hidden input with full code
            hiddenInput.value = Array.from(inputs).map(i => i.value).join('');
        });

        input.addEventListener('keydown', (e) => {
            // Handle backspace
            if (e.key === 'Backspace' && input.value === '' && index > 0) {
                inputs[index - 1].focus();
            }
        });
    });
</script>
@endpush

</x-guest-layout>
