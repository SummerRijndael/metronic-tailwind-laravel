<?php

namespace App\Enums;

enum ActivityLevel: string {
    case DEBUG = 'debug';
    case INFO  = 'info';
    case NOTICE = 'notice';
    case WARNING = 'warning';
    case ERROR = 'error';
    case CRITICAL = 'critical';
}
