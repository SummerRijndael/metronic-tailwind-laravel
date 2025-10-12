@props([
    'name',
    'id',
    'size' => 'md', // Default size: xsm, sm, md, lg, xl, xxl
    'placeholder' => 'Click to upload image',
    'preview' => null, // Optional initial image URL
    'align' => 'left',
    'static' => false, // If true, disables upload and remove functionality
])

@if (!$static)
    <!-- Error Message -->
    <div data-element="error-message"
        class="error-message mb-3 h-0 overflow-hidden text-center text-sm font-medium text-red-600 opacity-0">
    </div>
@endif

<!-- Image Uploader Component Root -->
<div data-image-uploader="{{ $static ? 'false' : 'true' }}"
    class="avatar-upload avatar-size-{{ $size }} {{ $align === 'center' ? 'avatar-align-center' : 'avatar-align-left' }}">

    @unless ($static)
        <!-- Hidden File Input -->
        <input type="file" name="{{ $name }}" id="{{ $id }}" accept=".png, .jpg, .jpeg" class="hidden" />

        <!-- Edit / Remove Button -->
        <div class="avatar-edit absolute bottom-0 right-0 z-10">
            <label for="{{ $id }}" data-action="edit-button"
                class="edit-button-style inline-flex cursor-pointer items-center justify-center rounded-full bg-white shadow-lg transition-all duration-200">
                {{-- Edit Icon --}}
                <svg data-icon="pencil" class="h-4 w-4 text-[var(--color-edit-icon)]" xmlns="http://www.w3.org/2000/svg"
                    width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"
                    stroke-linecap="round" stroke-linejoin="round">
                    <path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z" />
                </svg>

                {{-- Remove Icon --}}
                <svg data-icon="remove" class="h-4 w-4 text-red-500" xmlns="http://www.w3.org/2000/svg" width="24"
                    height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                    stroke-linecap="round" stroke-linejoin="round">
                    <path d="M3 6h18" />
                    <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6" />
                    <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2" />
                </svg>
            </label>
        </div>
    @endunless

    <!-- Preview Container -->
    <div data-action="{{ $static ? '' : 'preview-container' }}"
        class="avatar-preview-size relative overflow-hidden rounded-full shadow-inner ring-4 ring-blue-500/50 ring-offset-2">
        {{-- Background preview --}}
        <div class="h-full w-full rounded-full bg-cover bg-center" style="{!! $preview
            ? 'background-image: url(' . e($preview) . '); background-size: cover; background-position: center;'
            : '' !!}">
        </div>

        {{-- Hidden <img> (for live preview during upload) --}}
        <img data-element="preview-image" class="absolute inset-0 hidden h-full w-full object-cover"
            src="{{ $preview ?? '' }}" alt="Profile Avatar Preview">

        {{-- Placeholder text --}}
        <div data-element="placeholder"
            class="{{ $preview ? 'hidden' : '' }} absolute inset-0 flex h-full w-full flex-col items-center justify-center bg-gray-50/70 px-4 text-center text-xs font-semibold text-gray-600 backdrop-blur-[1px]">
            {{ $slot->isEmpty() ? $placeholder : $slot }}
        </div>
    </div>
</div>
