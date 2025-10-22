<?php

namespace App\Enums;

enum ActivityTarget: string {
    case SELF = 'self';          // Action performed by the user on themselves
    case OTHER = 'other';        // Action performed by user on another user/resource
    case SYSTEM = 'system';      // Action triggered by system/automation

    case RESOURCE = 'resource';
}
