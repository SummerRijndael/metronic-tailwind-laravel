<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Request;
use Carbon\Carbon; // DEV NOTE: Add the Carbon class for cleaner time handling

class MarkUserActive {
    /**
     * DEV NOTE: Middleware to update a user's last active timestamp in the cache (e.g., Redis).
     * This is useful for displaying user status (online/offline) and for session management.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next) {
        // DEV NOTE: Check if a user is currently logged in.
        if (Auth::check()) {
            $userId = Auth::id();

            // Optimization: Use a static key string for clarity and efficiency.
            // Cache::put returns a boolean, but we don't need to check it here.

            // OPTIMIZATION: Instead of using `now()->timestamp`, we can just use the
            // boolean `true` or a simple string as the value. The *existence* of the key
            // and its *expiration time* are what matter, not the specific timestamp data.
            // This slightly reduces the data stored and the serialization overhead.

            // DEV NOTE: The key is set to expire after 10 minutes (`now()->addMinutes(10)`).
            Cache::put(
                "user:last_active:$userId",
                true, // Optimized: Store a simple boolean instead of a full timestamp.
                Carbon::now()->addMinutes(10) // Optimized: Use Carbon directly for clarity.
            );
        }

        return $next($request);
    }
}
