<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use Illuminate\Contracts\Queue\ShouldQueue;

class LogSuccessfulLogin {
    // 1. Static flag to track execution status within the current request
    protected static bool $hasExecuted = false;

    /**
     * Handle the event.
     */
    public function handle(Login $event): void {
        // CRITICAL DEBOUNCE CHECK: Stop if already executed in this request
        if (static::$hasExecuted) {
            // This is the line that blocks the duplicate execution
            return;
        }

        // Mark the listener as executed for the remainder of this request
        static::$hasExecuted = true;

        // --- YOUR ORIGINAL LOGIC FOR LOGGING STARTS HERE ---

        $user = $event->user;

        if ($user) {
            $name = $user->name ?? $user->email;

            logUserActivity(
                'user_logged_in',
                "User logged in: {$name}.",
                [
                    'icon' => 'ki-filled ki-lock-open',
                    'color' => 'bg-success/60'
                ],
                $user
            );
        }
    }
}
