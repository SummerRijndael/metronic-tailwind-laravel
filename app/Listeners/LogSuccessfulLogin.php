<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use App\Helpers\ActivityLogger;
use App\Enums\ActivityCategory;
use App\Enums\ActivityAction;

class LogSuccessfulLogin {
    /**
     * Prevent duplicate firing within the same request cycle.
     */
    protected static bool $hasExecuted = false;

    /**
     * Handle the event.
     */
    public function handle(Login $event): void {
        // 🧠 Debounce check
        if (static::$hasExecuted) {
            return;
        }
        static::$hasExecuted = true;

        $user = $event->user;

        if (! $user) {
            return;
        }

        // Meta information
        $meta = [
            'ip' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'icon' => 'ki-filled ki-lock-open',
            'color' => 'bg-success/60',
            'timestamp' => now()->toDateTimeString(),
        ];

        // Unified activity log
        ActivityLogger::category(ActivityCategory::AUTH)
            ->action(ActivityAction::LOGIN)
            ->message("{$user->name} logged in successfully.")
            ->user($user)
            ->meta($meta)
            ->source('system')
            ->log();
    }
}
