<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request; // Use for type hinting
use Illuminate\Support\Facades\Log; // Added for explicit logging utility

class VerifyUserStatus {
    /**
     * Handle an incoming request. This middleware acts as the primary gate
     * (the Availability Layer) before any route or permission logic runs.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next): mixed {
        // Skip if not authenticated at all
        if (!Auth::check()) {
            return $next($request);
        }

        $user = Auth::user();

        /**
         * 🔒 1. Skip middleware during the Fortify 2FA challenge process
         * ------------------------------------------------------------
         * Fortify sets a temporary "login.id" in session when user has passed
         * password auth but not yet completed 2FA verification.
         * In that state, we must NOT run status verification yet.
         */
        if (
            $request->is('two-factor-challenge') ||
            session()->has('login.id')
        ) {
            return $next($request);
        }

        /**
         * 🧩 2. If user is suspended / blocked / disabled
         * ------------------------------------------------
         */
        if (!$user->isActive()) {
            // Try invalidating all active sessions for this user
            try {
                $user->invalidateAllSessions();
            } catch (\Throwable $e) {
                Log::warning("Session invalidation failed for user ID {$user->id}: " . $e->getMessage());
            }

            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            $status = $user->status;
            $message = match ($status) {
                'blocked'   => 'Your account has been locked for security reasons. Please contact support.',
                'suspended' => $user->suspended_until
                    ? 'Your account is temporarily suspended until ' . $user->suspended_until->format('Y-m-d H:i') . '.'
                    : 'Your account is temporarily suspended.',
                'disabled'  => 'Your account has been deactivated. Please contact the administrator.',
                default     => 'Your session was terminated due to invalid account status.',
            };

            logUserActivity(
                'session_terminated',
                "User session terminated. Status: {$status}.",
                ['status' => $status, 'ip' => $request->ip()],
                $user
            );

            if ($request->expectsJson()) {
                return response()->json(['message' => $message], 403);
            }

            return redirect()->route('login')->withErrors(['email' => $message]);
        }

        // ✅ Active user, continue
        return $next($request);
    }
}
