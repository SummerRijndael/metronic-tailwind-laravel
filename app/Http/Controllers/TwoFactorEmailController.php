<?php

namespace App\Http\Controllers;

use App\Mail\SendOtpCode;
use App\Models\EmailOtp;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str; // DEV NOTE: Str is currently imported but unused. May be removed.

/**
 * DEV NOTE: This controller manages the Email OTP flow, which acts as a secondary
 * authentication step after a user has successfully entered their primary credentials
 * but is *not yet* fully logged in (Auth::check() is false).
 * The user's temporary ID is stored in the session under 'login.id'.
 */
class TwoFactorEmailController extends Controller {
    /**
     * STEP 1: Show Email OTP Challenge Page
     * -------------------------------------
     * This view is accessed only after the user selects “Use Email OTP instead.”
     * User isn't fully logged in yet, but their user ID is stored in session('login.id').
     */
    public function show() {
        // DEV NOTE: We rely on the 'login.id' session key, typically set by the
        // primary login gate (e.g., a custom Fortify Authentication Pipeline action).
        $userId = session('login.id');

        // 🧩 Security Check: prevent direct access
        if (! $userId) {
            // DEV NOTE: logUserActivity() is assumed to be a globally available helper function.
            // Logging this unauthorized attempt is critical for security auditing.
            logUserActivity('unauthorized_access_email_otp', 'Attempted to access email OTP challenge without valid session.', [
                'ip' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ], null, true);

            return redirect()
                ->route('login')
                ->withErrors(['email' => 'Your session expired. Please log in again.']);
        }

        // DEV NOTE: The view must include a form that POSTs to the requestOtp route
        // and another form field for the user to submit the code to the verifyOtp route.
        return view('auth.email-otp-challenge');
    }

    // ----------------------------------------------------------------------------------

    /**
     * STEP 2: Request Email OTP
     * -------------------------
     * Generates a secure OTP, stores it hashed, and emails it to the verified user.
     */
    public function requestOtp(Request $request) {
        // -----------------------------------------------------------
        // 1. SESSION / USER VALIDATION
        // -----------------------------------------------------------
        $user = User::find(session('login.id'));

        if (! $user) {
            // DEV NOTE: Immediate exit if the temporary session is lost.
            logUserActivity('otp_request_invalid_session', 'OTP request made without valid user session.', [
                'ip' => request()->ip(),
            ], null, true);

            return redirect()
                ->route('login')
                ->withErrors(['email' => 'Your session expired. Please log in again.']);
        }

        // -----------------------------------------------------------
        // 2. RATE LIMITING CHECK
        // -----------------------------------------------------------
        // DEV NOTE: Rate limit key is tied to the user ID to prevent one user from
        // consuming the requests allowed for another.
        $key = 'email-otp:' . $user->id;

        if (RateLimiter::tooManyAttempts($key, 3)) { // 3 attempts
            $seconds = RateLimiter::availableIn($key);

            logUserActivity('otp_request_rate_limited', 'Too many OTP requests.', [
                'ip' => request()->ip(),
                'retry_after' => $seconds,
            ], $user);

            return back()->withErrors([
                // DEV NOTE: Use a generic error key for displaying on the form.
                'otp_code' => "Too many attempts. Please try again in {$seconds} seconds.",
            ]);
        }

        // -----------------------------------------------------------
        // 3. OTP GENERATION & STORAGE
        // -----------------------------------------------------------
        // DEV NOTE: **CRITICAL CLEANUP** - Deleting previous codes ensures only the latest
        // code is valid, preventing a user from successfully using an old, intercepted code.
        EmailOtp::where('user_id', $user->id)->delete();

        // DEV NOTE: Use random_int() for crypto-secure random number generation.
        $plainCode = str_pad(random_int(100000, 999999), 6, '0', STR_PAD_LEFT);

        // DEV NOTE: **SECURITY** - Store the code HASHED (one-way encryption) to the database.
        // Never store plaintext secrets. OTP expiry is set to 5 minutes.
        EmailOtp::create([
            'user_id'    => $user->id,
            'code'       => Hash::make($plainCode),
            'expires_at' => now()->addMinutes(5),
        ]);

        // -----------------------------------------------------------
        // 4. SEND EMAIL & RECORD ATTEMPT
        // -----------------------------------------------------------
        try {
            // DEV NOTE: Send the PLAINTEXT code via the SendOtpCode Mailable.
            Mail::to($user->email)->send(new SendOtpCode($plainCode));
        } catch (\Throwable $e) {
            // DEV NOTE: Failure to send email must NOT consume a rate limiter hit.
            // Log the exception for ops team to investigate mail driver issues.
            logUserActivity('otp_email_failed', 'OTP email delivery failed.', [
                'error' => $e->getMessage(),
            ], $user);

            return back()->withErrors([
                'otp_code' => 'Failed to send the code. Please try again later.',
            ]);
        }

        // DEV NOTE: **IMPORTANT** - Record the rate limiter hit ONLY AFTER the email
        // has successfully been sent, preventing wasted attempts due to external failures.
        RateLimiter::hit($key, 300); // 300 seconds (5 minutes)

        logUserActivity('otp_code_sent', 'OTP code sent successfully to user email.', [
            'ip' => request()->ip(),
        ], $user);

        return back()->with('status', 'We have successfully sent a 6-digit code to your email.');
    }

    // ----------------------------------------------------------------------------------

    /**
     * STEP 3: Verify OTP
     * ------------------
     * Validates the code, completes login, and cleans up.
     */
    public function verifyOtp(Request $request) {
        // -----------------------------------------------------------
        // 1. VALIDATION AND SESSION CHECK
        // -----------------------------------------------------------
        $user = User::find(session('login.id'));

        if (! $user) {
            logUserActivity('otp_verify_invalid_session', 'OTP verification attempted with invalid session.', [
                'ip' => request()->ip(),
            ], null, true);

            return redirect()
                ->route('login')
                ->withErrors(['email' => 'Your session expired. Please log in again.']);
        }

        // 🧾 Input validation: Ensure a 6-digit code was submitted.
        $request->validate(['otp_code' => 'required|digits:6']);

        // -----------------------------------------------------------
        // 2. CODE VERIFICATION
        // -----------------------------------------------------------

        // 🔍 DEV NOTE: This query efficiently filters valid, non-expired codes and
        // uses the Collection's `first()` method to perform the Hash::check()
        // against the HASHED code. This is a secure and performant way to verify.
        $validOtp = EmailOtp::where('user_id', $user->id)
            ->where('expires_at', '>', now())
            ->get()
            ->first(fn($otp) => Hash::check($request->otp_code, $otp->code));

        if (! $validOtp) {
            // DEV NOTE: Always return a generic error message to prevent revealing
            // whether the code failed due to expiration or incorrect input.
            logUserActivity('otp_verification_failed', 'Invalid or expired OTP entered.', [
                'ip' => request()->ip(),
                'entered_code' => $request->otp_code,
            ], $user);

            return back()->withErrors(['otp_code' => 'The code you entered is invalid or expired.']);
        }

        // -----------------------------------------------------------
        // 3. FINAL AUTHENTICATION & CLEANUP
        // -----------------------------------------------------------

        // ✅ OTP valid — finalize login:
        // 1. Delete all codes for the user (cleanup).
        EmailOtp::where('user_id', $user->id)->delete();

        // 2. Complete the primary login process using the user model.
        // The remember status is pulled from the temporary session data.
        Auth::login($user, session('login.remember'));

        // 3. CRITICAL: Forget the temporary session data.
        session()->forget(['login.id', 'login.remember']);

        logUserActivity('login_success_email_otp', 'User logged in successfully using Email OTP.', [
            'ip' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ], $user);

        // DEV NOTE: Redirect using intended() for robustness, falling back to
        // the Fortify home path (typically /dashboard or /home).
        return redirect()->intended(config('fortify.home'))
            ->with('status', 'Login successful.');
    }
}
