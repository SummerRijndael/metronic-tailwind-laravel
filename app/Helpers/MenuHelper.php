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
use Illuminate\Support\Str;

if (!function_exists('prepare_menu')) {
    function prepare_menu(array $menu): array
    {
        // Current request info
        $currentRoute = optional(request()->route())->getName(); // may be null
        $currentUrl = url()->current();
        $currentPath = ltrim(request()->path(), '/'); // path without leading slash

        foreach ($menu as &$item) {
            // Normalize keys
            $item['type'] = $item['type'] ?? 'route';
            $item['title'] = $item['title'] ?? 'No Title';
            $item['icon'] = $item['icon'] ?? null;
            $item['route'] = $item['route'] ?? null;
            $item['url'] = $item['url'] ?? null;
            $item['external'] = $item['external'] ?? false;
            $item['children'] = $item['children'] ?? [];

            // Normalize children first
            if (!empty($item['children'])) {
                $item['children'] = prepare_menu($item['children']);
            }

            // Smart icon handling
            if (!empty($item['icon'])) {
                $ext = pathinfo($item['icon'], PATHINFO_EXTENSION);

                if (in_array(strtolower($ext), ['png', 'jpg', 'jpeg', 'svg', 'gif'])) {
                    $item['icon'] = asset($item['icon']); // image file → convert to asset URL
                } else {
                    $item['icon'] = $item['icon']; // CSS class or other → leave as-is
                }
            }

            // Default active state
            $item['active'] = false;

            // 1) Match by route name (supports wildcard patterns)
            if (!empty($item['route']) && $currentRoute) {
                if (Str::is($item['route'], $currentRoute) || $item['route'] === $currentRoute) {
                    $item['active'] = true;
                }
            }

            // 2) Match by URL (absolute or path)
            if (!$item['active'] && !empty($item['url'])) {
                $url = $item['url'];

                if (str_starts_with($url, 'http://') || str_starts_with($url, 'https://')) {
                    if (rtrim($url, '/') === rtrim($currentUrl, '/')) {
                        $item['active'] = true;
                    }
                } else {
                    $itemPath = ltrim(parse_url($url, PHP_URL_PATH) ?? $url, '/');
                    if ($itemPath !== '' && request()->is($itemPath)) {
                        $item['active'] = true;
                    }
                }
            }

            // 3) If any child is active, parent becomes active
            if (!$item['active'] && !empty($item['children'])) {
                foreach ($item['children'] as $child) {
                    if (!empty($child['active'])) {
                        $item['active'] = true;
                        break;
                    }
                }
            }

            // Convenience flags
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
