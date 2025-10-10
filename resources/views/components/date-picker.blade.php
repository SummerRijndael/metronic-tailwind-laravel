@props([
    'name' => 'date',
    'id' => null,
    'placeholder' => 'Select date',
    'value' => null,
])

@php
    // Generate default id if none provided
    $id = $id ?? $name . '-' . uniqid();
@endphp

<input type="text"
       id="{{ $id }}"
       name="{{ $name }}"
       value="{{ old($name, $value) }}"
       placeholder="{{ $placeholder }}"
       {{ $attributes->merge(['class' => 'form-input border rounded p-2 w-full']) }}>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        flatpickr("#{{ $id }}", {
            dateFormat: "Y-m-d",
            allowInput: true,
        });
    });
</script>
