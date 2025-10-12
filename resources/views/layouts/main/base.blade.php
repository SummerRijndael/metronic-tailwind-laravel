<!DOCTYPE html>
<html class="h-full" data-kt-theme="false" data-kt-theme-mode="dark" dir="ltr" lang="en">

<head>
    @include('layouts.partials.head')
    @stack('styles')
    <script>
        window.userThemeSetting = '{{ Auth::user()->settings['appearance']['theme'] ?? 'mama' }}';
    </script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>

<body
    class="flex h-full bg-background bg-mono text-base text-foreground antialiased [--header-height:60px] [--sidebar-width:270px] dark:bg-background lg:overflow-hidden">
    @include('partials.theme-toggle')

    <!-- Page -->
    <div class="flex grow">
        <!-- Header -->
        @include('layouts.main.header')
        <!-- End of Header -->
        <!-- Wrapper -->
        <div class="pt-(--header-height) flex grow flex-col lg:flex-row lg:pt-0">
            <!-- Sidebar -->
            @include('layouts.main.sidebar')
            <!-- End of Sidebar -->
            <!-- Main -->
            <div class="border-input lg:ms-(--sidebar-width) flex grow flex-col border bg-background lg:rounded-l-xl">
                <div class="kt-scrollable-y-auto flex grow flex-col pt-5 lg:[--kt-scrollbar-width:auto]"
                    id="scrollable_content">
                    <main class="grow" role="content">
                        <!-- Toolbar -->
                        @include('layouts.main.toolbar')
                        <!-- End of Toolbar -->
                        <!-- Container -->
                        <div class="kt-container-fixed">
                            @yield('content')
                        </div>
                        <!-- End of Container -->
                    </main>
                    <!-- Footer -->
                    @include('layouts.main.footer')
                    <!-- End of Footer -->
                </div>
            </div>
            <!-- End of Main -->
        </div>
        <!-- End of Wrapper -->
    </div>
    <!-- End of Page -->

    @include('layouts.partials.scripts')
    @livewireScripts

    @stack('vendors')
    @stack('scripts')


</body>

</html>
