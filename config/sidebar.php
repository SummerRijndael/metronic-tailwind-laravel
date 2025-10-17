<?php

/**
 * Sidebar Menu Configuration
 *
 * This file defines the structure for the sidebar menu. It is processed by
 * the `MenuHelper::prepare_menu()` function before rendering.
 *
 * The MenuHelper handles:
 * 1. Filtering unauthorized items using the 'permission' key.
 * 2. Hiding parent 'label' items if all children are filtered out (The Empty Menu Fix).
 * 3. Calculating the 'active' state based on the current route/URL.
 *
 * --- ITEM STRUCTURE ---
 *
 * Core Keys (Always Required or Highly Recommended):
 * - 'type': 'route', 'url', or 'label'.
 * - 'title': Displayed text for the menu item.
 *
 * Optional/Conditional Keys (Defaults are set in MenuHelper, so they can be omitted):
 * - 'icon': Metronic icon class OR path to an image file (e.g., assets/media/...).
 * - 'route': Laravel named route (required if type === 'route').
 * - 'url': Full URL (external) or relative path (required if type === 'url').
 * - 'external': bool (Defaults to false). Set true if the link opens an external site.
 * - 'children': array (required if type === 'label'). Nested items.
 *
 * --- ACCESS CONTROL FLAGS ---
 *
 * - 'controlled_access': bool (ONLY on type: 'label').
 * -> DEPRECATED. This flag is now effectively replaced by checking if 'children'
 * is empty after filtering. The MenuHelper will automatically hide the
 * parent 'label' if none of its 'permission'-gated children remain.
 *
 * - 'permission': string (ONLY on leaf/child items).
 * -> The required permission string (e.g., 'user_view_any') to view this item.
 * If missing, the item is publicly visible (within the logged-in user context).
 */

return [
    'primary' => [
        [
            'type' => 'route',
            'title' => 'Overview',
            'icon' => 'ki-filled ki-home-3',
            'route' => 'dashboard',
        ],
        [
            'type' => 'label',
            'title' => 'My Account',
            'icon' => 'ki-filled ki-profile-circle',
            'children' => [
                [
                    'type' => 'route',
                    'title' => 'User Profile',
                    'route' => 'profile.show',
                ],
                [
                    'type' => 'route',
                    'title' => 'Settings',
                    'route' => 'profile_settings.show',
                ],
            ],
        ],
        [
            'type' => 'label',
            'title' => 'User Management',
            'icon' => 'ki-filled ki-people',
            // 'controlled_access' is now redundant as MenuHelper fixes empty parents
            // 'controlled_access' => true,
            'children' => [
                [
                    'type' => 'route',
                    'title' => 'Users List',
                    'route' => 'admin.user_management.dashboard',
                    'permission' => 'user_view_any', // AccessHelper::can('user_view_any') must pass
                ],
                [
                    'type' => 'route',
                    'title' => 'Settings',
                    'route' => 'dashboard',
                    'permission' => 'user_view_any', // AccessHelper::can('user_view_any') must pass
                ],
            ],
        ],
    ],

    'secondary' => [
        [
            'type' => 'url',
            'title' => '@keenthemes',
            'icon' => 'assets/media/brand-logos/x-dark.svg',
            'url' => 'https://keenthemes.com/metronic/tailwind/docs/',
            'external' => true,
        ],
        [
            'type' => 'url',
            'title' => '@keenthemes_hub',
            'icon' => 'assets/media/brand-logos/slack.svg',
            'url' => 'https://github.com/keenthemes/',
            'external' => true,
        ],
    ],
];
