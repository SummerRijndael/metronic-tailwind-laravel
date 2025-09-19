@extends('layouts.main.base') <!-- or your main layout -->

@section('content')
<div class="flex justify-center items-center min-h-screen">
    <x-modal 
        id="welcome_modal" 
        title="Welcome to Metronic" 
        :autoShow="true"
        :image="'assets/media/illustrations/21.svg'"
    >
        We're thrilled to have you on board and excited for the journey ahead together.

        <x-slot name="actions">
            <a href="{{ url('/dashboard') }}" class="kt-btn kt-btn-primary flex justify-center">
                Show me around
            </a>
            <a href="#" class="kt-btn kt-btn-outline flex justify-center ms-2">
                Skip
            </a>
        </x-slot>
    </x-modal>
</div>
@endsection
