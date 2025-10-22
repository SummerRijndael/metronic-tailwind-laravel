<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Registered;
use App\Helpers\ActivityLogger;
use App\Enums\ActivityCategory;
use App\Enums\ActivityAction;

class LogNewUserRegistration {
    /**
     * Prevent duplicate firing within the same request cycle.
     */
    protected static bool $hasExecuted = false;

    /**
     * Handle the event.
     */
    public function handle(Registered $event): void {
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
            'icon' => 'ki-filled ki-user-plus',
            'color' => 'bg-success/60',
            'timestamp' => now()->toDateTimeString(),
        ];

        // Unified activity log
        ActivityLogger::category(ActivityCategory::USER)
            ->action(ActivityAction::USER_CREATED)
            ->message("New user registered: {$user->name}.")
            ->user($user)
            ->meta($meta)
            ->source('system')
            ->log();
    }
}
