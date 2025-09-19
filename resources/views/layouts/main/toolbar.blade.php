<!-- Toolbar -->
<div class="pb-5">
    <div class="kt-container-fixed flex items-center justify-between flex-wrap gap-3">
        <div class="flex flex-col flex-wrap gap-1">

            <h1 class="font-medium text-lg text-mono">
                {{ ($breadcrumb = Breadcrumbs::current()) ? $breadcrumb->title : null }}
            </h1>

            <div class="flex items-center gap-1 text-sm font-normal">
					@php
					use Diglactic\Breadcrumbs\Breadcrumbs;

					// Get breadcrumbs for current route
					$crumbs = Breadcrumbs::generate(request()->route()->getName());
					@endphp
			
					<ol class="kt-breadcrumb">
				@if(!empty($crumbs))
					@foreach($crumbs as $crumb)
						@if(!$loop->first)
						
						<li class="kt-breadcrumb-separator">
							<svg
							xmlns="http://www.w3.org/2000/svg"
							width="24"
							height="24"
							viewBox="0 0 24 24"
							fill="none"
							stroke="currentColor"
							stroke-width="2"
							stroke-linecap="round"
							stroke-linejoin="round"
							class="lucide lucide-chevron-right"
							aria-hidden="true"
							>
							<path d="m9 18 6-6-6-6"></path>
							</svg>
						</li> 
						
						@endif

						@if($loop->last)
							<strong>{{ $crumb->title }}</strong>
						@elseif(isset($crumb->url) && $crumb->url && $crumb->url !== '#')
							 <li class="kt-breadcrumb-item">
								<a href="{{ $crumb->url }}">{{ $crumb->title }}</a>
							</li>
							
						@else
							{{ $crumb->title }}
						@endif
					@endforeach
				@endif
            </ol>
            </div>
        </div>
    </div>
</div>
<!-- End Toolbar -->
