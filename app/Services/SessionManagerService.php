<?php

// app/Services/SessionManagerService.php
namespace App\Services;

use App\Models\Session;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Jenssegers\Agent\Agent;
use Torann\GeoIP\Facades\GeoIP; // ← ADD THIS AT THE TOP

class SessionManagerService {

    /**
     * Retrieves and formats all active sessions for a specific user.
     */
    public function getActiveSessionsForUser(User $user): Collection {
        // ... query remains the same ...
        $sessions = Session::query()
            ->active()
            ->where('user_id', $user->id)
            ->get();

        // Map and transform the data for the front-end display
        return $sessions->map(function (Session $session) {

            // 🚀 ROBUST PARSING LOGIC START (REPLACING OLD LOGIC)
            $agent = new Agent();
            // Important: Use the raw user_agent string
            $agent->setUserAgent($session->user_agent);

            // Get components with robust fallbacks
            $os = $agent->platform() ?: 'Other OS';
            $browser = $agent->browser() ?: 'Browser/App';

            // Determine the device type, falling back to 'Unknown'
            if ($agent->isDesktop()) {
                $type = 'Desktop';
            } elseif ($agent->isTablet()) {
                $type = 'Tablet';
            } elseif ($agent->isMobile()) {
                $type = 'Mobile';
            } else {
                $type = 'Unknown';
            }

            // Construct a readable string
            $readableDevice = "{$browser} on {$os} ({$type})";

            // Final check: If all components failed, return a single safe string.
            if ($session->user_agent === null || $session->user_agent === '') {
                $readableDevice = 'N/A (No User Agent)';
            }
            // 🚀 ROBUST PARSING LOGIC END


            $payloadData = @unserialize(base64_decode($session->payload));
            $isCurrent = $session->id === session()->getId();
            $location = 'Unknown Location';

            try {
                $geo = geoip($session->ip_address); // same as GeoIP::getLocation(...)
                if (!empty($geo->city) && !empty($geo->state_name) && !empty($geo->country)) {
                    // Format B (the one you chose)
                    $location = "{$geo->city}, {$geo->state_name}, {$geo->country}";
                } elseif (!empty($geo->country)) {
                    $location = $geo->country; // graceful fallback
                }
            } catch (\Exception $e) {
                // ignore errors silently
            }

            return [
                'id'             => $session->id,
                'ip_address'     => $session->ip_address,
                'user_agent'     => $session->user_agent,
                'last_active_at' => Carbon::createFromTimestamp($session->last_activity)->diffForHumans(),
                'is_current'     => $isCurrent,
                'device'         => $readableDevice,
                'location'       => $location,  // ✅ Now dynamic location, not placeholder
            ];
        });
    }

    /**
     * Forcefully terminates a specific session, ensuring it belongs to the specified user.
     * 🚀 PATCH: Added $user parameter to enforce ownership.
     * 🚀 PATCH: Using User's ID and Session ID for secure, scoped deletion.
     * * @param User $user The user whose session is being revoked.
     * @param string $sessionId The ID of the session to destroy.
     * @return int The number of sessions deleted (0 or 1).
     */
    public function revokeSession(User $user, string $sessionId): int {
        // We must scope the deletion to the provided user's ID for security
        // and to match the intent of the calling controller logic.

        // Use the query builder directly for efficiency
        $deletedCount = DB::table(config('session.table', 'sessions'))
            ->where('id', $sessionId)
            ->where('user_id', $user->getKey()) // <-- CRUCIAL SCOPING CHECK
            ->delete();

        return $deletedCount;
    }

    /**
     * Revokes ALL sessions for a given user.
     * (No changes needed here, logic is correct)
     */
    public function revokeAllSessionsForUser(User $user): int {
        // ... (existing code remains the same)
        if (config('session.driver') !== 'database') {
            return 0;
        }

        $deletedCount = DB::table(config('session.table', 'sessions'))
            ->where('user_id', $user->getKey())
            ->delete();

        return $deletedCount;
    }
}
