<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Registered;
use Illuminate\Contracts\Queue\ShouldQueue;
// If logging is a background task, you can add 'implements ShouldQueue'

class LogNewUserRegistration {
    /**
     * Handle the event.
     *
     * @param Registered $event The event fired after a new user registers.
     */
    protected static bool $hasExecuted = false;

    public function handle(Registered $event): void {

        if (static::$hasExecuted) {
            // This is the line that blocks the duplicate execution
            return;
        }

        // Mark the listener as executed for the remainder of this request
        static::$hasExecuted = true;

        // The Registered event contains the newly created User object in its 'user' property.
        $user = $event->user;

        // Ensure we have a user object to log
        if ($user) {
            $name = $user->name ?? $user->email ?? 'New User';

            // Use the logUserActivity helper
            // We pass the $user object and set $skipAuthCheck to TRUE
            // because the user is often not yet fully logged in/authenticated
            // when this event fires.
            logUserActivity(
                'user_registered',
                "New user registered: {$name}.",
                [
                    // Custom meta data
                    'new_user_id' => $user->id,
                    'icon' => 'ki-filled ki-user-plus',
                    'color' => 'bg-success/60'
                ],
                $user,        // 👈 Explicitly pass the new User object
                true         // 👈 TRUE: Log the activity even if Auth::user() is null
            );
        }
    }
}
