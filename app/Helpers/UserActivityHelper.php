<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Models\UserActivityTrail;

/**
 * Global User Activity Logging Helper
 *
 * This function provides a central, standardized way to record important
 * actions (e.g., login, profile change, system events).
 *
 * NOTE: The function supports logging actions even when no user is authenticated
 * by setting the 'user_id' to null (requires the column to be nullable in the migration).
 */
if (!function_exists('logUserActivity')) {
    /**
     * Logs an activity for a user, an anonymous action, or a system event.
     *
     * @param string $action      Identifier for the activity (e.g., 'login', 'user_created', 'system_cleanup').
     * @param string|null $description Optional human-readable description for context.
     * @param array|null $meta    Optional structured data (will be stored as JSON).
     * @param User|null $user     Optional specific user object; defaults to currently authenticated user.
     * @param bool $skipAuthCheck If true, allows logging even if no user is found/passed (for anonymous/system actions).
     * @return void
     *
     * --------------------------------------------------------------------------------------------------
     * USAGE EXAMPLES FOR NEXT DEV:
     * --------------------------------------------------------------------------------------------------
     * 1. Standard User Action (Authenticated User):
     * logUserActivity('profile_photo_updated', 'User changed profile picture.');
     * // user_id will be Auth::id(), skipAuthCheck defaults to false.
     *
     * 2. Explicit User Action (e.g., New Registration, User object available):
     * // $newUser is the User object instance
     * logUserActivity('user_registered', 'A new user completed sign-up.', null, $newUser, true);
     * // user_id will be $newUser->id. $skipAuthCheck=true ensures it logs before Auth.
     *
     * 3. Anonymous or System Action (No User ID):
     * // Anonymous: Logging a failed login attempt
     * logUserActivity('login_failed', 'Invalid credentials.', ['ip' => request()->ip()], null, true);
     * // System: Logging a scheduled task (no user object)
     * logUserActivity('system_audit', 'Nightly cleanup finished.', ['records' => 42], null, true);
     * // In both cases, user_id will be NULL.
     * --------------------------------------------------------------------------------------------------
     */
    function logUserActivity(
        string $action,
        ?string $description = null,
        ?array $meta = null,
        ?User $user = null,
        bool $skipAuthCheck = false
    ): void {
        // 1. Determine the user
        $user = $user ?? Auth::user();

        // 2. Conditional check for system/anonymous actions
        if (!$user && !$skipAuthCheck) {
            // Stop logging if an authenticated log is required but no user is found.
            return;
        }

        if (is_array($meta) || $meta instanceof \Illuminate\Support\Collection) {
            $meta = json_decode(json_encode($meta), true); // safely convert everything to array
        }

        // 3. Create the trail record
        UserActivityTrail::create([
            // Set user_id to the user's ID or null if it's an anonymous/system action
            'user_id'     => $user->id ?? null,
            'action'      => $action,
            'description' => $description,

            // Use the global request() helper
            'ip_address'  => request()->ip(),
            'user_agent'  => request()->header('User-Agent'),

            // Meta data is automatically cast to JSON by the model.
            'meta' => $meta ? json_encode($meta, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) : null,
        ]);
    }
}
