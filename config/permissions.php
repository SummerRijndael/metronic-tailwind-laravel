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
            'revoke_sessions_any'
        ],
        'Editor' => [
            'post_create',
            'post_edit_self',
            'post_publish',
            'media_upload',
            'report_view_analytics',
            'revoke_sessions_self'
        ],
        'User' => [
            'user_view_self',
            'user_edit_self',
            'post_view_self',
            'billing_view',
            'revoke_sessions_self'
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
        'revoke_sessions_self' => 'Revoke own sessions',
        'revoke_sessions_self' => 'Revoke any users Session',
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
        'revoke_session_any'            => ['label' => 'Revoke Target Session', 'category' => 'User Management'],
        'revoke_session_self'            => ['label' => 'Revoke Own Session', 'category' => 'User Management'],

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

    /**
     * =========================================================================
     * USER STATUSES (Availability Layer)
     * -------------------------------------------------------------------------
     * These statuses determine the highest level of access: Can the user
     * log in and interact with the system at all? This check should execute
     * BEFORE any detailed permission checks (like AccessHelper::can()).
     *
     * @var array
     * =========================================================================
     */
    $statuses = [
        /**
         * ACTIVE (🟢 Default)
         * Use Case: Normal operational state. The user has full intended access
         * based on their assigned roles and permissions.
         * Result on Access: Full access (proceeds to Permission/RBAC checks).
         * Duration: Indefinite.
         */
        'active',

        /**
         * BLOCKED (🔴 Security Lockout)
         * Use Case: Immediate security-focused denial. Used when the user is
         * suspected of malicious activity (e.g., bot, brute force) or a high-level
         * security policy violation.
         * Result on Access: Immediate Lockout. Cannot log in.
         * Duration: Indefinite (Requires manual administrator review/reversal).
         */
        'blocked',

        /**
         * SUSPENDED (🟡 Disciplinary/Temporary)
         * Use Case: Time-limited disciplinary action for temporary policy
         * violations (e.g., spamming, inappropriate conduct). This status
         * usually requires an associated 'suspended_until' timestamp.
         * Result on Access: Temporary Lockout. Cannot log in until expiration.
         * Duration: Temporary (e.g., 24 hours, 7 days).
         */
        'suspended',

        /**
         * DISABLED (⚫ Administrative/Archive)
         * Use Case: Permanent administrative removal or soft-delete. Used when
         * a user requests account deletion, an employee leaves, or the account
         * is intentionally archived long-term.
         * Result on Access: Permanent Lockout. Cannot log in.
         * Duration: Permanent (Used for soft-deleting the user data).
         */
        'disabled',
    ]
];
