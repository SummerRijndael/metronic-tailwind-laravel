<?php
/*
Sidebar Menu Config
-------------------
Each menu item must follow this structure:

- type: string
    - "route"   → Laravel named route (uses route())
    - "url"     → External or absolute URL
    - "label"   → Group/section label with children (accordion style)

- title: string (displayed text)
- icon: string|null (Metronic icon class, optional)
- route: string|null (Laravel route name if type === "route")
- url: string|null (full URL if type === "url")
- external: bool (true if link opens external site)
- children: array (nested items, only used if type === "label")
*/

return [
    'primary' => [
        [
            'type' => 'route',
            'title' => 'Overview',
            'icon' => 'ki-filled ki-home-3',
            'route' => 'dashboard',
            'url' => null,
            'external' => false,
            'children' => [],
        ],
        [
            'type' => 'label',
            'title' => 'My Account',
            'icon' => 'ki-filled ki-setting-2',
            'route' => null,
            'url' => null,
            'external' => false,
            'children' => [
                [
                    'type' => 'route',
                    'title' => 'User Profile',
                    'icon' => 'ki-filled ki-profile-circle',
                    'route' => 'myprofile',
                    'url' => null,
                    'external' => false,
                    'children' => [],
                ],
                [
                    'type' => 'route',
                    'title' => 'Settings',
                    'icon' => 'ki-filled ki-setting',
                    'route' => 'menugen',
                    'url' => null,
                    'external' => false,
                    'children' => [],
                ],
            ],
        ],
    ],

    'secondary' => [
        [
            'type' => 'url',
            'title' => '@keenthemes',
            'icon' => null,
            'route' => null,
            'url' => 'https://keenthemes.com/metronic/tailwind/docs/',
            'external' => true,
            'children' => [],
        ],
        [
            'type' => 'url',
            'title' => '@keenthemes_hub',
            'icon' => null,
            'route' => null,
            'url' => 'https://github.com/keenthemes/',
            'external' => true,
            'children' => [],
        ],
    ],
];
