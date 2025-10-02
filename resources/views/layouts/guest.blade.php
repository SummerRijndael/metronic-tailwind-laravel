<!DOCTYPE html>
<html class="h-full" data-kt-theme="true" data-kt-theme-mode="light" dir="ltr" lang="en">

<head>
	
	@include('layouts.partials.head')
	@vite(['resources/css/app.css', 'resources/js/app.js'])
	@stack('styles')

</head>

<body class="antialiased flex h-full text-base text-foreground bg-background">
	@include('partials.theme-toggle')


			{{ $slot }}


	@include('layouts.partials.scripts')

	@yield('scripts')
	@stack('scripts')
	
</body>

</html>