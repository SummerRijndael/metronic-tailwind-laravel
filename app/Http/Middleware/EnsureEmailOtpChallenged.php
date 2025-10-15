<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureEmailOtpChallenged {
    /**
     * Ensures the Email OTP route is accessed only when:
     * 1. The link signature is valid (prevents tampering or replay attacks).
     * 2. The user has an active "pending login" session (session('login.id')).
     * 3. The user is not already fully authenticated.
     *
     * If any condition fails, it redirects gracefully to the appropriate route.
     */
    public function handle(Request $request, Closure $next) {
        // 🧩 1. Verify Laravel's signed URL integrity (optional but strong protection)
        // This prevents tampering or link reuse after expiration.
        if ($request->routeIs('two-factor.email.show') && ! $request->hasValidSignature()) {
            abort(403, 'Invalid or expired OTP link.');
        }

        // 🧱 2. Block access if user is already fully authenticated.
        // (They already passed OTP or logged in through other means)
        if (Auth::check()) {
            return redirect()->intended(config('fortify.home'));
        }

        // ⏳ 3. Require a pending login session ID
        // (This is set after successful password verification, before 2FA)
        if (! session()->has('login.id')) {
            return redirect()
                ->route('login')
                ->withErrors(['email' => 'Your session expired. Please log in again.']);
        }

        // ✅ Passed all checks, allow request through.
        return $next($request);
    }
}
