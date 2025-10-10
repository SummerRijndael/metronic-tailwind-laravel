<?php

return [

    'roles' => [
        'Admin' => [
            'user_view_any',
            'user_edit_any',
            'user_delete',
            'role_edit',
            'settings_edit_security',
            'report_view_financial',
        ],
        'Editor' => [
            'post_create',
            'post_edit_self',
            'post_publish',
            'media_upload',
            'report_view_analytics',
        ],
        'User' => [
            'user_view_self',
            'user_edit_self',
            'post_view_self',
            'billing_view',
        ],
    ],

    'permissions' => [
        'user_view_any'         => 'View all users in the system.',
        'user_view_self'        => 'View own profile details.',
        'user_create'           => 'Create new user accounts.',
        'user_edit_any'         => 'Edit any user profile.',
        'user_edit_self'        => 'Edit own profile.',
        'user_delete'           => 'Delete user accounts.',
        'role_edit'             => 'Create/update roles.',
        'settings_edit_security' => 'Edit security settings.',
        'report_view_financial' => 'View financial reports.',
        'post_create'           => 'Create new posts.',
        'post_edit_self'        => 'Edit own posts.',
        'post_publish'          => 'Publish posts.',
        'media_upload'          => 'Upload media.',
        'report_view_analytics' => 'View analytics reports.',
        'billing_view'          => 'View billing details.',
    ],

    /*
    |--------------------------------------------------------------------------
    | Permission Labels and Categories
    |--------------------------------------------------------------------------
    | Each key represents a permission name used in Spatie.
    | The "label" is a human-readable name for display.
    | The optional "category" lets you group them logically in the UI.
    |--------------------------------------------------------------------------
    */

    'list' => [

        // 👥 User Management
        'user_view_any'          => ['label' => 'View Users', 'category' => 'User Management'],
        'user_view_self'         => ['label' => 'View Profile', 'category' => 'User Management'],
        'user_create'            => ['label' => 'Create User', 'category' => 'User Management'],
        'user_edit_any'          => ['label' => 'Edit Others', 'category' => 'User Management'],
        'user_edit_self'         => ['label' => 'Edit Profile', 'category' => 'User Management'],
        'user_delete'            => ['label' => 'Delete User', 'category' => 'User Management'],

        // 🧩 Roles & Security
        'role_edit'              => ['label' => 'Edit Roles', 'category' => 'Access Control'],
        'settings_edit_security' => ['label' => 'Edit Security', 'category' => 'Access Control'],

        // 📊 Reports
        'report_view_financial'  => ['label' => 'View Financial Reports', 'category' => 'Reports'],
        'report_view_analytics'  => ['label' => 'View Analytics', 'category' => 'Reports'],

        // 📰 Posts
        'post_create'            => ['label' => 'Create Posts', 'category' => 'Content'],
        'post_edit_self'         => ['label' => 'Edit Posts', 'category' => 'Content'],
        'post_publish'           => ['label' => 'Publish Posts', 'category' => 'Content'],

        // 📦 Media
        'media_upload'           => ['label' => 'Upload Media', 'category' => 'Media'],

        // 💰 Billing
        'billing_view'           => ['label' => 'View Billing', 'category' => 'Finance'],
    ],

];
