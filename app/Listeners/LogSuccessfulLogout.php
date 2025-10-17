<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Logout;
//use Illuminate\Contracts\Queue\ShouldQueue; // 👈 Import required for optimization
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log; // 👈 Good practice for error handling
use Illuminate\Support\Facades\DB;
use App\Models\User; // 👈 Assuming standard User model location

class LogSuccessfulLogout  // 🚀 OPTIMIZATION: Queue the job
{
    /**
     * Dev Note: This static property prevents the listener from running multiple
     * times within a single HTTP request cycle, which can happen with certain
     * middleware/event setups (e.g., if multiple guards fire the Logout event).
     */
    protected static bool $hasExecuted = false;

    /**
     * Handle the event.
     *
     * @param \Illuminate\Auth\Events\Logout $event
     * @return void
     */
    public function handle(Logout $event): void {
        // CRITICAL DEBOUNCE CHECK: Stop if already executed in this request.
        if (static::$hasExecuted) {
            return;
        }

        static::$hasExecuted = true;

        static::$hasExecuted = true;

        if (!$event->user) {
            return;
        }

        /** @var \App\Models\User $user */
        $user = $event->user;

        // 1️⃣ Remove Redis active marker (THIS IS NOW INSTANT)
        try {
            // This calls Cache::forget(), which correctly deletes the key from DB 1.
            Cache::forget("user:last_active:{$user->id}");
        } catch (\Exception $e) {
            Log::error("Failed to remove active marker for User ID: {$user->id}. Error: {$e->getMessage()}");
        }

        // 2️⃣ Optional: remove DB sessions if SESSION_DRIVER=database
        if (config('session.driver') === 'database') {
            DB::table('sessions')->where('user_id', $user->id)->delete();
        }


        // Get the user's display name for logging purposes
        $name = $user->name ?? $user->email ?? 'Unknown User';

        // Log the logout activity
        // Dev Note: The logUserActivity helper must accept the User object
        // as the fourth argument to correctly associate the log entry.
        logUserActivity(
            'logout',
            "User {$name} logged out.",
            [
                // Custom meta data
                'icon' => 'ki-filled ki-entrance-right',
                'color' => 'bg-accent/60'
            ],
            $user // 👈 Pass the fully loaded User object
        );
    }
}
