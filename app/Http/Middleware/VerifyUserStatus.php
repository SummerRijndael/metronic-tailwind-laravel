<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Helpers\ActivityLogger;
use App\Enums\ActivityCategory;
use App\Enums\ActivityAction;

class VerifyUserStatus {
    /**
     * Handle an incoming request. Acts as the Availability Layer before any route or permission logic.
     */
    public function handle(Request $request, Closure $next): mixed {
        // Skip if not authenticated at all
        if (!Auth::check()) {
            return $next($request);
        }

        $user = Auth::user();

        // 🔒 Skip middleware during Fortify 2FA challenge
        if ($request->is('two-factor-challenge') || session()->has('login.id')) {
            return $next($request);
        }

        // 🧩 Check if user is suspended / blocked / disabled
        if (!$user->isActive()) {
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

            // --- Unified Activity Logger
            ActivityLogger::category(ActivityCategory::AUTH)
                ->action(ActivityAction::SESSION_TERMINATED)
                ->message("User session terminated. Status: {$status}.")
                ->user($user)
                ->meta([
                    'status' => $status,
                    'ip' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                    'timestamp' => now()->toDateTimeString(),
                ])
                ->source('system')
                ->log();

            if ($request->expectsJson()) {
                return response()->json(['message' => $message], 403);
            }

            return redirect()->route('login')->withErrors(['email' => $message]);
        }

        // ✅ Active user, continue
        return $next($request);
    }
}
