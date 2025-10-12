@extends('layouts.main.base') <!-- or your main layout -->

@section('content')
    <x-modal id="welcome_modal" title="Welcome to Metronic" :autoShow="false" :image="'assets/media/illustrations/21.svg'">
        Hello {{ auth()->user()->name }}! we're thrilled to have you on board and excited for the journey ahead
        together.

        <x-slot name="actions">
            <a href="{{ url('/dashboard') }}" class="kt-btn kt-btn-primary flex justify-center">
                Show me around
            </a>
            <a href="#" class="kt-btn kt-btn-outline ms-2 flex justify-center">
                Skip
            </a>

        </x-slot>
    </x-modal>


    <livewire:two-factor-setup />
@endsection
