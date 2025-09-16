<?php
/**
 * Sidebar Menu Component (with Active State)
 *
 * Props:
 * - primaryMenu (array): list of primary menu items
 * - secondaryMenu (array): list of secondary menu items
 *
 * Each item structure:
 * - type: string ('route' | 'url' | 'label')
 * - title: string (menu label)
 * - icon: string|null (optional icon class)
 * - route: string|null (for 'route' type)
 * - url: string|null (for 'url' type or external links)
 * - external: bool (true if external link)
 * - children: array (sub-items for 'label' type)
 * - active: bool (true if current route/url matches)
 */
?>

<div class="kt-scrollable-y-auto grow bg-background text-foreground" data-kt-scrollable="true" data-kt-scrollable-dependencies="#sidebar_header, #sidebar_footer" data-kt-scrollable-height="auto" data-kt-scrollable-offset="0px" data-kt-scrollable-wrappers="#sidebar_menu">

    <!-- Primary Menu -->
    <div class="mb-5">
        <h3 class="text-sm text-muted-foreground uppercase ps-5 inline-block mb-3">Pages</h3>

        <div class="kt-menu flex flex-col w-full gap-1.5 px-3.5" data-kt-menu="true" data-kt-menu-accordion-expand-all="false" id="sidebar_primary_menu">
            @props(['primaryMenu' => []])

            @foreach($primaryMenu as $item)
                @if($item['type'] === 'route')
                    <!-- Route Link Item -->
                    <div class="kt-menu-item {{ $item['active'] ? 'active here show' : '' }}">
                        <a class="kt-menu-link gap-2.5 py-2 px-2.5 rounded-md kt-menu-item-active:bg-secondary kt-menu-link-hover:bg-accent/60" href="{{ route($item['route']) }}">
                            @if($item['icon'])
                                <span class="kt-menu-icon text-lg">
                                    <i class="{{ $item['icon'] }}"></i>
                                </span>
                            @endif
                            <span class="kt-menu-title text-sm font-medium kt-menu-item-active:font-medium kt-menu-item-active:text-mono">
                                {{ $item['title'] }}
                            </span>
                        </a>
                    </div>

                @elseif($item['type'] === 'url')
                    <!-- URL Link Item -->
                    <div class="kt-menu-item {{ $item['active'] ? 'active here show' : '' }}">
                        <a class="kt-menu-link gap-2.5 py-2 px-2.5 rounded-md kt-menu-item-active:bg-secondary kt-menu-link-hover:bg-accent/60" href="{{ $item['url'] }}" target="{{ $item['external'] ? '_blank' : '_self' }}">
                            @if($item['icon'])
                                <span class="kt-menu-icon text-lg">
                                    <i class="{{ $item['icon'] }}"></i>
                                </span>
                            @endif
                            <span class="kt-menu-title text-sm font-medium kt-menu-item-active:font-medium kt-menu-item-active:text-mono">
                                {{ $item['title'] }}
                            </span>
                        </a>
                    </div>

                @elseif($item['type'] === 'label')
                    <!-- Label with Children (Collapsible Group) -->
                    <div class="kt-menu-item {{ $item['active'] ? 'show' : '' }}" data-kt-menu-item-toggle="accordion" data-kt-menu-item-trigger="click">

                        <!-- Group Header -->
                        <div class="kt-menu-link gap-2.5 py-2 px-2.5 rounded-md">
                            @if($item['icon'])
                                <span class="kt-menu-icon text-lg">
                                    <i class="{{ $item['icon'] }}"></i>
                                </span>
                            @endif
                            <span class="kt-menu-title text-sm font-medium">
                                {{ $item['title'] }}
                            </span>
                            <span class="kt-menu-arrow">
                                <i class="ki-filled ki-down text-xs"></i>
                            </span>
                        </div>

                        <!-- Group Children -->
                        <div class="kt-menu-accordion gap-px ps-7">
                            @foreach($item['children'] as $child)
                                @if($child['type'] === 'route')
                                    <!-- Route Child -->
                                    <div class="kt-menu-item {{ $child['active'] ? 'active here show' : '' }}">
                                        <a class="kt-menu-link py-2 px-2.5 rounded-md kt-menu-item-active:bg-secondary kt-menu-link-hover:bg-secondary" href="{{ route($child['route']) }}">
                                            <span class="kt-menu-title text-sm">{{ $child['title'] }}</span>
                                        </a>
                                    </div>
                                @elseif($child['type'] === 'url')
                                    <!-- URL Child -->
                                    <div class="kt-menu-item {{ $child['active'] ? 'active here show' : '' }}">
                                        <a class="kt-menu-link py-2 px-2.5 rounded-md kt-menu-item-active:bg-secondary kt-menu-link-hover:bg-secondary" href="{{ $child['url'] }}" target="{{ $child['external'] ? '_blank' : '_self' }}">
                                            <span class="kt-menu-title text-sm">{{ $child['title'] }}</span>
                                        </a>
                                    </div>
                                @elseif($child['type'] === 'label')
                                    <!-- Nested Label (recursive group if needed) -->
                                    <div class="kt-menu-item {{ $child['active'] ? 'active here show' : '' }}" data-kt-menu-item-toggle="accordion" data-kt-menu-item-trigger="click">
                                        <div class="kt-menu-link py-2 px-2.5 rounded-md border">
                                            <span class="kt-menu-title text-sm">{{ $child['title'] }}</span>
                                            <span class="kt-menu-arrow">
                                                <i class="ki-filled ki-down text-xs"></i>
                                            </span>
                                        </div>
                                        <div class="kt-menu-accordion gap-px ps-2.5">
                                            @foreach($child['children'] as $sub)
                                                <div class="kt-menu-item {{ $sub['active'] ? 'active here show' : '' }}">
                                                    <a class="kt-menu-link py-2 px-2.5 rounded-md kt-menu-item-active:bg-secondary kt-menu-link-hover:bg-secondary" href="{{ $sub['url'] ?? ($sub['route'] ? route($sub['route']) : '#') }}">
                                                        <span class="kt-menu-title text-sm">{{ $sub['title'] }}</span>
                                                    </a>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>

                    </div>
                @endif
            @endforeach

        </div>
    </div>
    <!-- End Primary Menu -->


    <!-- Secondary Menu -->
    <div>
        <h3 class="text-sm text-muted-foreground uppercase ps-5 inline-block mb-3">Outline</h3>
        <div class="kt-menu flex flex-col w-full gap-1.5 px-3.5" data-kt-menu="true" data-kt-menu-accordion-expand-all="true" id="sidebar_secondary_menu">
            @props(['secondaryMenu' => []])

            @foreach($secondaryMenu as $item)
                <div class="kt-menu-item {{ $item['active'] ? 'active here show' : '' }}">
                    <a class="kt-menu-link py-1 px-2.5" href="{{ $item['url'] }}" target="{{ $item['external'] ? '_blank' : '_self' }}">
                        @if($item['icon'])
                            <span class="rounded-md flex items-center justify-center size-7 bg-coal-black me-2">
                                <img alt="" class="size-3.5" src="{{ $item['icon'] }}" />
                            </span>
                        @endif
                        <span class="kt-menu-title text-sm">{{ $item['title'] }}</span>
                    </a>
                </div>
            @endforeach
        </div>
    </div>
    <!-- End Secondary Menu -->

</div>