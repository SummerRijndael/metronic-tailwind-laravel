<!-- Sidebar -->

<div class="w-(--sidebar-width) dark fixed bottom-0 top-0 z-20 hidden shrink-0 flex-col items-stretch [--kt-drawer-enable:true] lg:flex lg:[--kt-drawer-enable:false]"
    data-kt-drawer="true" data-kt-drawer-class="kt-drawer kt-drawer-start flex top-0 bottom-0" id="sidebar">
    <!-- Sidebar Header -->
    <div class="flex flex-col gap-2.5" id="sidebar_header">
        <div class="flex h-[70px] items-center gap-2.5 px-3.5">
            <a href="#">
                <img class="size-[34px]" src="{{ asset('assets/media/app/mini-logo-circle-success.svg') }}" />
            </a>
            <div class="kt-menu kt-menu-default grow" data-kt-menu="true">
                <div class="kt-menu-item grow" data-kt-menu-item-offset="0, 15px"
                    data-kt-menu-item-placement="bottom-start">
                    <div class="kt-menu-label grow cursor-pointer justify-between font-medium text-mono">
                        <span class="text-inverse grow text-lg font-medium">
                            Metronic
                        </span>

                    </div>

                </div>
            </div>
        </div>

    </div>
    <!-- End of Sidebar Header -->
    <!-- Sidebar menu -->
    <div class="my-5 flex shrink-0 grow items-stretch justify-center" id="sidebar_menu">
        <x-sidebar-menu :primaryMenu="prepare_menu(config('sidebar.primary'))" :secondaryMenu="prepare_menu(config('sidebar.secondary'))" />

    </div>
    <!-- End of Sidebar kt-menu-->
    <!-- Footer -->
    <div class="flex-center mb-3.5 flex shrink-0 justify-between pe-3.5 ps-4" id="sidebar_footer">
        <!-- User -->
        @include('layouts.main.partials.user-dropdown')
        <!-- End of User -->
        @include('layouts.main.partials.notification')
    </div>
    <!-- End of Footer -->
</div>
<!-- End of Sidebar -->
