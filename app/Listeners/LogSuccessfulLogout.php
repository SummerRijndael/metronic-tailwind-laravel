<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Logout;
use Illuminate\Contracts\Queue\ShouldQueue;

class LogSuccessfulLogout {
    /**
     * Handle the event.
     *
     */

    protected static bool $hasExecuted = false;

    public function handle(Logout $event): void {
        // CRITICAL DEBOUNCE CHECK: Stop if already executed in this request
        if (static::$hasExecuted) {
            // This is the line that blocks the duplicate execution
            return;
        }

        // Mark the listener as executed for the remainder of this request
        static::$hasExecuted = true;

        // The Logout event user might be null if a session expires or a non-standard guard is used.
        if ($event->user) {
            $user = $event->user;

            // Get the user's name or email before the session is fully destroyed
            $name = $user->name ?? $user->email ?? 'Unknown User';

            // Use the logUserActivity helper, explicitly passing the $user object
            logUserActivity(
                'logout',
                "User {$name} logged out.",
                [
                    // Custom meta data for front-end display (icon, color)
                    'icon' => 'ki-filled ki-entrance-right',
                    'color' => 'bg-accent/60'
                ],
                $user // 👈 Pass the User object before it's completely destroyed
            );
        }
    }
}
