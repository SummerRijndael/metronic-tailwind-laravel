<?php

namespace App\Enums;

enum ActivityCategory: string {
    case AUTH = 'auth';          // login, logout, password reset
    case USER = 'user';          // profile update, settings
    case SYSTEM = 'system';      // cron jobs, maintenance, internal
    case SECURITY = 'security';  // bans, alerts, permission changes
    case APP = 'app';            // generic or legacy logs (used by bridge)

    case ROLE = 'role';       // Role assignments, role edits
    case PERMISSION = 'permission'; // Grant, revoke, temporary permissions
    case REPORT = 'report';   // Viewing, exporting sensitive reports
    case FINANCE = 'finance'; // Financial operations or transactions
    case SETTINGS = 'settings'; // System configuration changes

    case ACCESS    = 'access';     // Access control / critical permission actions
    case SESSION   = 'session';    // Session terminations / invalidations

    case CONTENT = 'content';
    case BILLING = 'billing';
}
