<?php

namespace App\Enums;

enum ActivitySubject: string {
    case USER = 'user';          // Any activity related to a user
    case PROFILE = 'profile';    // Profile-specific actions
    case AUTH = 'auth';          // Login/logout/authentication events
    case SESSION = 'session';    // Session-related events
    case PERMISSION = 'permission'; // Role/permission changes
    case SECURITY = 'security';  // Security events (e.g., OTP, password change)

}
