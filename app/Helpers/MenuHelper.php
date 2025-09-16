<?php
/*
prepare_menu - improved active detection + debug checklist

What changed / why:
- Children are normalized first so child active flags are known before parent checks.
- Active detection uses the *current route name* (recommended) and supports wildcard patterns via Str::is().
- URL detection compares absolute URLs (external) or request path (internal) safely without calling route() (which may require params).
- Parent gets marked active when any child is active.

Debug checklist (if still not working):
1. Ensure you call prepare_menu() **before** passing the array to the component:
   $primary = prepare_menu(config('menu.primary'));
   <x-sidebar-menu :primaryMenu="$primary" />

2. If you still don't see an `active` key, dump the data inside the component right after @props:
   @php dd($primaryMenu); @endphp

3. Dump the current route name to compare: dd(optional(request()->route())->getName(), request()->path());

4. Clear caches after changing helpers: php artisan view:clear && php artisan cache:clear


Implementation (drop into your helpers file or replace existing prepare_menu):
*/

if (!function_exists('prepare_menu')) {
    function prepare_menu(array $menu): array
    {
        // current request info
        $currentRoute = optional(request()->route())->getName(); // may be null
        $currentUrl = url()->current();
        $currentPath = ltrim(request()->path(), '/'); // path without leading slash

        foreach ($menu as &$item) {
            // normalize keys
            $item['type'] = $item['type'] ?? 'route';
            $item['title'] = $item['title'] ?? 'No Title';
            $item['icon'] = $item['icon'] ?? null;
            $item['route'] = $item['route'] ?? null;
            $item['url'] = $item['url'] ?? null;
            $item['external'] = $item['external'] ?? false;
            $item['children'] = $item['children'] ?? [];

            // normalize children first (important!)
            if (!empty($item['children'])) {
                $item['children'] = prepare_menu($item['children']);
            }

            // default
            $item['active'] = false;

            // 1) match by route name (supports patterns like 'admin.*')
            if (!empty($item['route']) && $currentRoute) {
                // use fully-qualified Str::is without import
                if (\Illuminate\Support\Str::is($item['route'], $currentRoute) || $item['route'] === $currentRoute) {
                    $item['active'] = true;
                }
            }

            // 2) match by url (absolute or path)
            if (!$item['active'] && !empty($item['url'])) {
                $url = $item['url'];

                // absolute external urls
                if (str_starts_with($url, 'http://') || str_starts_with($url, 'https://')) {
                    if (rtrim($url, '/') === rtrim($currentUrl, '/')) {
                        $item['active'] = true;
                    }
                } else {
                    // treat as internal path - compare request path
                    $itemPath = ltrim(parse_url($url, PHP_URL_PATH) ?? $url, '/');
                    if ($itemPath !== '' && request()->is($itemPath)) {
                        $item['active'] = true;
                    }
                }
            }

            // 3) if any child is active, the parent becomes active
            if (!$item['active'] && !empty($item['children'])) {
                foreach ($item['children'] as $child) {
                    if (!empty($child['active'])) {
                        $item['active'] = true;
                        break;
                    }
                }
            }

            // convenience flags
            $item['hasChild'] = !empty($item['children']);
            $item['skip_url'] = $item['skip_url'] ?? ($item['type'] === 'label');
        }

        return $menu;
    }
}


/*
Blade rendering snippet (ensure you apply the active class names that Metronic's CSS/JS expects)

-- Example: add "kt-menu-item-here" to parent and add a link-active class to <a>
-- Adjust class names to match the exact version of Metronic you use.

<div class="kt-menu-item {{ \$item['active'] ? 'kt-menu-item-here' : '' }}">
    <a class="kt-menu-link gap-2.5 py-2 px-2.5 rounded-md {{ \$item['active'] ? 'kt-menu-link-active kt-menu-item-active:bg-secondary' : '' }}" href="{{ \$href }}">
        @if(\$item['icon']) <span class="kt-menu-icon"> <i class="{{ \$item['icon'] }}"></i> </span> @endif
        <span class="kt-menu-title">{{ \$item['title'] }}</span>
    </a>
</div>

*/
