<!-- Sidebar -->

<div class="flex-col fixed top-0 bottom-0 z-20 hidden lg:flex items-stretch shrink-0 w-(--sidebar-width) dark [--kt-drawer-enable:true] lg:[--kt-drawer-enable:false]" data-kt-drawer="true" data-kt-drawer-class="kt-drawer kt-drawer-start flex top-0 bottom-0" id="sidebar">
	<!-- Sidebar Header -->
	<div class="flex flex-col gap-2.5" id="sidebar_header">
		<div class="flex items-center gap-2.5 px-3.5 h-[70px]">
			<a href="#">
				<img class="size-[34px]" src="{{ asset('assets/media/app/mini-logo-circle-success.svg') }}" />
			</a>
			<div class="kt-menu kt-menu-default grow" data-kt-menu="true">
				<div class="kt-menu-item grow" data-kt-menu-item-offset="0, 15px" data-kt-menu-item-placement="bottom-start" >
					<div class="kt-menu-label cursor-pointer text-mono font-medium grow justify-between">
						<span class="text-lg font-medium text-inverse grow">
							Metronic
						</span>

					</div>
					
				</div>
			</div>
		</div>
		
	</div>
	<!-- End of Sidebar Header -->
	<!-- Sidebar menu -->
	<div class="flex items-stretch grow shrink-0 justify-center my-5" id="sidebar_menu">
		<x-sidebar-menu :primaryMenu="prepare_menu(config('sidebar.primary'))" :secondaryMenu="prepare_menu(config('sidebar.secondary'))" />
		
	</div>
	<!-- End of Sidebar kt-menu-->
	<!-- Footer -->
	<div class="flex flex-center justify-between shrink-0 ps-4 pe-3.5 mb-3.5" id="sidebar_footer">
		<!-- User -->
		@include('layouts.main.partials.user-dropdown')
		<!-- End of User -->
		 @include('layouts.main.partials.notification')
	</div>
	<!-- End of Footer -->
</div>
<!-- End of Sidebar -->