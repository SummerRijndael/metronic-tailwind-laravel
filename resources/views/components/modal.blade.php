<div class="kt-modal kt-modal-center" data-kt-modal="true" id="{{ $id }}" role="dialog" aria-modal="true" tabindex="-1">
    <div class="kt-modal-content max-w-[500px] w-full">
        <div class="kt-modal-header justify-end border-0 pt-5">
            <button class="kt-btn kt-btn-sm kt-btn-icon kt-btn-outline" data-kt-modal-dismiss="true">
                <i class="ki-filled ki-cross"></i>
            </button>
        </div>
        <div class="kt-modal-body flex flex-col items-center pt-0 pb-10">
            {{-- Title --}}
            <h3 class="text-lg font-medium text-mono text-center mb-3">
                {{ $title }}
            </h3>

            {{-- Content --}}
            <div class="text-sm text-center text-secondary-foreground mb-7">
                {{ $slot }}
            </div>

            {{-- Actions slot --}}
            @isset($actions)
                <div class="flex justify-center mb-2">
                    {!! $actions !!}
                </div>
            @endisset
        </div>
    </div>
</div>

@if($autoShow)
<script>
    window.onload = () => {
        const modalEl = document.getElementById('{{ $id }}');
        const modal = KTModal.getInstance(modalEl);
        modal?.show();
    };
</script>
@endif
