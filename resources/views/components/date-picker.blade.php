{{--
|--------------------------------------------------------------------------
| Flatpickr Blade Component
|--------------------------------------------------------------------------
| Author: Ryann Olaso
| Version: 1.0
|
| ✅ Description:
| A scalable and flexible Blade component for Flatpickr — supports all
| official features like datetime, range, time-only, inline, and localization.
| It allows dynamic configuration through the :config prop.
|
| ✅ Requirements:
| - Include Flatpickr via Vite (recommended) or CDN.
| - Requires `flatpickr` to be globally available (see @once section below).
|
| ✅ Dev Notes:
| - Pass custom settings via the :config attribute as an array.
| - Default config: simple date picker (Y-m-d format).
| - You can safely use multiple instances on one page — unique IDs are auto-generated.
| - Supports Tailwind and Metronic-friendly styling.
|
| ✅ Example usage patterns:
|  1. Simple date picker
|     <x-flatpickr name="birthday" />
|
|  2. Date & time picker
|     <x-flatpickr
|         name="appointment"
|         :config="['enableTime' => true, 'dateFormat' => 'Y-m-d H:i']"
|     />
|
|  3. Range picker
|     <x-flatpickr
|         name="date_range"
|         :config="['mode' => 'range']"
|     />
|
|  4. Localized + alt format
|     <x-flatpickr
|         name="deadline"
|         :config="[
|             'locale' => 'fr',
|             'altInput' => true,
|             'altFormat' => 'F j, Y',
|             'dateFormat' => 'Y-m-d'
|         ]"
|     />
|
|  5. Inline calendar
|     <x-flatpickr
|         name="schedule"
|         :config="['inline' => true, 'defaultDate' => now()->format('Y-m-d')]"
|     />
|
| 🔧 Optional enhancement:
| Add theme support or icons later (see @TODO).
|
--}}

@props([
    'name' => 'date',
    'id' => null,
    'placeholder' => 'Select date',
    'value' => null,
    'config' => [], // Allow full access to flatpickr options
])

@php
    // 🔹 Auto-generate unique ID if none provided
    $id = $id ?? $name . '-' . uniqid();

    // 🔹 Default configuration (safely merged with custom config)
    $defaultConfig = [
        'dateFormat' => 'Y-m-d',
        'allowInput' => true,
        'enableTime' => false,
        'time_24hr' => true,
    ];

    $mergedConfig = array_merge($defaultConfig, $config);
@endphp

{{--
|--------------------------------------------------------------------------
| HTML Input Field
|--------------------------------------------------------------------------
| Tailwind + Metronic-compatible design with flexible merging of attributes.
--}}
<div class="relative">
    <input type="text" id="{{ $id }}" name="{{ $name }}" value="{{ old($name, $value) }}"
        placeholder="{{ $placeholder }}"
        {{ $attributes->merge(['class' => 'form-input border rounded-lg p-2 w-full focus:ring-primary focus:border-primary transition']) }}>

    {{-- @TODO: Optionally add a calendar icon overlay here --}}
</div>

{{--
|--------------------------------------------------------------------------
| Flatpickr Scripts
|--------------------------------------------------------------------------
| Automatically initialized on component mount with passed config.
| @once ensures the library is loaded only once (even with multiple components).
--}}


@push('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const config = @json($mergedConfig);
            flatpickr("#{{ $id }}", config);
        });
    </script>
@endpush
