<?php

namespace App\Actions;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use App\Models\User;

class CheckUserStatusDuringLogin {
    public function handle(Request $request, \Closure $next) {
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return $next($request); // continue, Fortify will handle bad creds
        }

        // ⚠️ 1. Blocked or Disabled users
        if (in_array($user->status, ['blocked', 'disabled'])) {
            logUserActivity(
                'login_blocked',
                "Login denied for {$user->status} account.",
                ['ip' => $request->ip()],
                $user
            );

            throw \Illuminate\Validation\ValidationException::withMessages([
                'email' => __("Your account has been {$user->status}. Please contact support."),
            ]);
        }

        // ⚠️ 2. Suspended users (time-based)
        if ($user->isSuspended()) {
            logUserActivity(
                'login_suspended',
                'Login attempt during suspension.',
                [
                    'ip' => $request->ip(),
                    'remaining' => $user->remainingSuspension(),
                ],
                $user
            );

            throw \Illuminate\Validation\ValidationException::withMessages([
                'email' => __("Your account is suspended for another {$user->remainingSuspension()}."),
            ]);
        }

        return $next($request);
    }
}
