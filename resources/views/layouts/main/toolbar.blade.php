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

				@if(!empty($crumbs))
					@foreach($crumbs as $crumb)
						@if(!$loop->first) / @endif

						@if($loop->last)
							<strong>{{ $crumb->title }}</strong>
						@elseif(isset($crumb->url) && $crumb->url && $crumb->url !== '#')
							<a href="{{ $crumb->url }}">{{ $crumb->title }}</a>
						@else
							{{ $crumb->title }}
						@endif
					@endforeach
				@endif

            </div>
        </div>
    </div>
</div>
<!-- End Toolbar -->
