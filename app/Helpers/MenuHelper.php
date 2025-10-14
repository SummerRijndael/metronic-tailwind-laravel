<?php

/**
 * MenuHelper Functions
 *
 * This file contains helper functions responsible for processing the sidebar
 * menu configuration array before it is passed to a Blade component.
 *
 * It performs two main operations:
 * 1. filter_menu_by_access: Removes menu items the current user is unauthorized to see.
 * 2. prepare_menu: Normalizes data, handles icon paths, and calculates the 'active' state.
 */

use Illuminate\Support\Str;
use App\Helpers\AccessHelper;

if (!function_exists('filter_menu_by_access')) {
    /**
     * Recursively filters menu items based on user permissions.
     *
     * This function uses the 'permission' key on leaf items (type: 'route' or 'url')
     * and ensures parent 'label' items are only kept if they have at least one
     * authorized child remaining.
     *
     * @param array $menu The menu configuration array to filter.
     * @return array The filtered menu array.
     */
    function filter_menu_by_access(array $menu): array {
        $filteredMenu = [];

        foreach ($menu as $item) {

            // 1. Recurse into children first to get their filtered state.
            if (!empty($item['children'])) {
                $item['children'] = filter_menu_by_access($item['children']);
            }

            // 2. Permission Check for Leaf Items
            // If a 'permission' key exists, check if the current user has it.
            if (isset($item['permission'])) {
                // Uses AccessHelper from App\Helpers\AccessHelper::can(permission_string).
                if (AccessHelper::can($item['permission']) === false) {
                    continue; // Skip this unauthorized leaf item.
                }
            }

            // 3. Parent Visibility Check (The Empty Menu Fix)
            // If the item is a group ('label') and has an empty 'children' array
            // (meaning all children were filtered out in step 1 or 2), skip the parent.
            if ($item['type'] === 'label') {
                if (empty($item['children'])) {
                    continue; // Skip the parent because it has no visible children.
                }
            }

            // 4. If all checks pass (item is authorized or non-permissioned), add it.
            $filteredMenu[] = $item;
        }

        return $filteredMenu;
    }
}


if (!function_exists('prepare_menu')) {
    /**
     * Prepares the raw menu array for rendering in the sidebar.
     *
     * This function runs after filtering and is responsible for:
     * - Setting default values for missing optional keys (normalization).
     * - Resolving icon paths.
     * - Calculating the 'active' state based on the current request.
     *
     * @param array $menu The menu array (already filtered by access control).
     * @return array The processed menu array with 'active' and 'hasChild' flags.
     */
    function prepare_menu(array $menu): array {

        // 0. Filter by access control BEFORE checking active state
        // This ensures 'active' calculations only run on visible items.
        $menu = filter_menu_by_access($menu);

        // Get current request data once for optimization
        $currentRoute = optional(request()->route())->getName(); // The current Laravel named route
        $currentUrl = url()->current();                         // The full absolute URL
        // $currentPath is used for relative path matching
        $currentPath = ltrim(request()->path(), '/'); // path without leading slash

        foreach ($menu as &$item) {
            // --- Normalization: Set default values for missing keys ---
            $item['type'] = $item['type'] ?? 'route';
            $item['title'] = $item['title'] ?? 'No Title';
            $item['icon'] = $item['icon'] ?? null;
            $item['route'] = $item['route'] ?? null;
            $item['url'] = $item['url'] ?? null;
            $item['external'] = $item['external'] ?? false;
            $item['children'] = $item['children'] ?? [];
            $item['permission'] = $item['permission'] ?? null;
            $item['controlled_access'] = $item['controlled_access'] ?? false;

            // Normalize children first recursively
            if (!empty($item['children'])) {
                $item['children'] = prepare_menu($item['children']);
            }

            // --- Icon Handling ---
            if (!empty($item['icon'])) {
                $ext = pathinfo($item['icon'], PATHINFO_EXTENSION);
                $isImage = in_array(strtolower($ext), ['png', 'jpg', 'jpeg', 'svg', 'gif']);

                if ($isImage) {
                    // If it's a file extension, assume it's an asset path
                    $item['icon'] = asset($item['icon']);
                }
                // Otherwise, assume it's a CSS class and leave it as-is
            }

            // Default active state
            $item['active'] = false;

            // --- Active State Calculation ---

            // 1) Match by route name (supports exact match AND wildcard patterns like 'user.*')
            if (!empty($item['route']) && $currentRoute) {
                if (Str::is($item['route'], $currentRoute) || $item['route'] === $currentRoute) {
                    $item['active'] = true;
                }
            }

            // 2) Match by URL (Absolute or Relative Path)
            if (!$item['active'] && !empty($item['url'])) {
                $url = $item['url'];

                if (str_starts_with($url, 'http://') || str_starts_with($url, 'https://')) {
                    // Absolute URL match (for external or absolute links)
                    if (rtrim($url, '/') === rtrim($currentUrl, '/')) {
                        $item['active'] = true;
                    }
                } else {
                    // Relative Path match (uses Laravel's request()->is() for wildcards)
                    $itemPath = ltrim(parse_url($url, PHP_URL_PATH) ?? $url, '/');
                    if ($itemPath !== '' && request()->is($itemPath)) {
                        $item['active'] = true;
                    }
                }
            }

            // 3) Propagate Active State (Parent becomes active if any child is active)
            if (!$item['active'] && !empty($item['children'])) {
                foreach ($item['children'] as $child) {
                    if (!empty($child['active'])) {
                        $item['active'] = true;
                        break;
                    }
                }
            }

            // --- Convenience Flags for Blade Rendering ---
            $item['hasChild'] = !empty($item['children']);
            // Flag to prevent the anchor tag from rendering a URL, typically for type 'label'
            $item['skip_url'] = $item['skip_url'] ?? ($item['type'] === 'label');
        }

        return $menu;
    }
}
