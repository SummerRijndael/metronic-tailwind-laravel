<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Logout;
use App\Helpers\ActivityLogger;
use App\Enums\ActivityCategory;
use App\Enums\ActivityAction;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class LogSuccessfulLogout {
    /**
     * Prevent duplicate firing within the same request cycle.
     */
    protected static bool $hasExecuted = false;

    /**
     * Handle the event.
     */
    public function handle(Logout $event): void {
        // 🧠 Debounce check
        if (static::$hasExecuted) {
            return;
        }
        static::$hasExecuted = true;

        $user = $event->user;

        if (! $user) {
            return;
        }

        // 1️⃣ Remove Redis active marker
        try {
            Cache::forget("user:last_active:{$user->id}");
        } catch (\Exception $e) {
            Log::error("Failed to remove active marker for user {$user->id}: {$e->getMessage()}");
        }

        // 2️⃣ Clear sessions if using database driver
        if (config('session.driver') === 'database') {
            DB::table('sessions')->where('user_id', $user->id)->delete();
        }

        // Meta information
        $meta = [
            'ip' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'icon' => 'ki-filled ki-entrance-right',
            'color' => 'bg-accent/60',
            'timestamp' => now()->toDateTimeString(),
        ];

        // Unified activity log
        ActivityLogger::category(ActivityCategory::AUTH)
            ->action(ActivityAction::LOGOUT)
            ->message("{$user->name} logged out successfully.")
            ->user($user)
            ->meta($meta)
            ->source('system')
            ->log();
    }
}
