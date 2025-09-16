<div class="kt-menu-accordion gap-px ps-7">
    @foreach($children as $child)
        <div class="kt-menu-item @if(isset($child['children']))" data-kt-menu-item-toggle="accordion" data-kt-menu-item-trigger="click" @endif>
            @if(isset($child['children']))
                <div class="kt-menu-link py-2 px-2.5 rounded-md border border-transparent !menu-item-here:bg-transparent">
                    <span class="kt-menu-title text-sm text-foreground kt-menu-item-here:text-mono kt-menu-item-show:text-mono kt-menu-link-hover:text-mono">
                        {{ $child['title'] }}
                    </span>
                    <span class="kt-menu-arrow text-muted-foreground kt-menu-item-here:text-muted-foreground kt-menu-item-show:text-foreground kt-menu-link-hover:text-foreground">
                        <span class="inline-flex kt-menu-item-show:hidden">
                            <i class="ki-filled ki-down text-xs"></i>
                        </span>
                        <span class="hidden kt-menu-item-show:inline-flex">
                            <i class="ki-filled ki-up text-xs"></i>
                        </span>
                    </span>
                </div>
                @include('components.sidebar-submenu', ['children' => $child['children']])
            @else
                @if(isset($child['route']))
                    <a class="kt-menu-link py-2 px-2.5 rounded-md kt-menu-item-active:bg-secondary kt-menu-link-hover:bg-secondary" href="{{ $child['route'] !== '#' ? route($child['route']) : '#' }}">
                        <span class="kt-menu-title text-sm text-foreground kt-menu-item-active:font-medium kt-menu-item-active:text-mono kt-menu-link-hover:text-mono">
                            {{ $child['title'] }}
                        </span>
                    </a>
                @endif
            @endif
        </div>
    @endforeach
</div>