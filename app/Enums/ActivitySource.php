<?php

namespace App\Enums;

enum ActivitySource: string {
    case WEB = 'web';
    case MOBILE = 'mobile';
    case API = 'api';
    case SYSTEM = 'system';
    case CONSOLE = 'console';
}
