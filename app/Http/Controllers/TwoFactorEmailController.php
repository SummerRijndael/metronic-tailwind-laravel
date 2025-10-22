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
use App\Helpers\ActivityLogger;
use App\Enums\ActivityCategory;
use App\Enums\ActivityAction;

class TwoFactorEmailController extends Controller {
    /**
     * STEP 1: Show Email OTP Challenge Page
     */
    public function show() {
        $userId = session('login.id');

        if (!$userId) {
            ActivityLogger::category(ActivityCategory::AUTH)
                ->action(ActivityAction::UNAUTHORIZED_ACCESS)
                ->message('Attempted to access email OTP challenge without valid session.')
                ->meta([
                    'ip' => request()->ip(),
                    'user_agent' => request()->userAgent(),
                ])
                ->source('system')
                ->log();

            return redirect()
                ->route('login')
                ->withErrors(['email' => 'Your session expired. Please log in again.']);
        }

        return view('auth.email-otp-challenge');
    }

    /**
     * STEP 2: Request Email OTP
     */
    public function requestOtp(Request $request) {
        $user = User::find(session('login.id'));

        if (!$user) {
            ActivityLogger::category(ActivityCategory::AUTH)
                ->action(ActivityAction::OTP_REQUEST_INVALID)
                ->message('OTP request made without valid user session.')
                ->meta(['ip' => request()->ip()])
                ->source('system')
                ->log();

            return redirect()
                ->route('login')
                ->withErrors(['email' => 'Your session expired. Please log in again.']);
        }

        $key = 'email-otp:' . $user->id;

        if (RateLimiter::tooManyAttempts($key, 3)) {
            $seconds = RateLimiter::availableIn($key);

            ActivityLogger::category(ActivityCategory::AUTH)
                ->action(ActivityAction::OTP_RATE_LIMITED)
                ->message('Too many OTP requests.')
                ->meta([
                    'ip' => request()->ip(),
                    'retry_after' => $seconds,
                ])
                ->user($user)
                ->source('system')
                ->log();

            return back()->withErrors([
                'otp_code' => "Too many attempts. Please try again in {$seconds} seconds.",
            ]);
        }

        // Cleanup previous OTPs
        EmailOtp::where('user_id', $user->id)->delete();

        $plainCode = str_pad(random_int(100000, 999999), 6, '0', STR_PAD_LEFT);

        EmailOtp::create([
            'user_id'    => $user->id,
            'code'       => Hash::make($plainCode),
            'expires_at' => now()->addMinutes(5),
        ]);

        try {
            Mail::to($user->email)->send(new SendOtpCode($plainCode));
        } catch (\Throwable $e) {
            ActivityLogger::category(ActivityCategory::AUTH)
                ->action(ActivityAction::OTP_EMAIL_FAILED)
                ->message('OTP email delivery failed.')
                ->meta(['error' => $e->getMessage()])
                ->user($user)
                ->source('system')
                ->log();

            return back()->withErrors(['otp_code' => 'Failed to send the code. Please try again later.']);
        }

        RateLimiter::hit($key, 300);

        ActivityLogger::category(ActivityCategory::AUTH)
            ->action(ActivityAction::OTP_SENT)
            ->message('OTP code sent successfully to user email.')
            ->meta(['ip' => request()->ip()])
            ->user($user)
            ->source('system')
            ->log();

        return back()->with('status', 'We have successfully sent a 6-digit code to your email.');
    }

    /**
     * STEP 3: Verify OTP
     */
    public function verifyOtp(Request $request) {
        $user = User::find(session('login.id'));

        if (!$user) {
            ActivityLogger::category(ActivityCategory::AUTH)
                ->action(ActivityAction::OTP_VERIFY_INVALID)
                ->message('OTP verification attempted with invalid session.')
                ->meta(['ip' => request()->ip()])
                ->source('system')
                ->log();

            return redirect()
                ->route('login')
                ->withErrors(['email' => 'Your session expired. Please log in again.']);
        }

        $request->validate(['otp_code' => 'required|digits:6']);

        $validOtp = EmailOtp::where('user_id', $user->id)
            ->where('expires_at', '>', now())
            ->get()
            ->first(fn($otp) => Hash::check($request->otp_code, $otp->code));

        if (!$validOtp) {
            ActivityLogger::category(ActivityCategory::AUTH)
                ->action(ActivityAction::OTP_VERIFICATION_FAILED)
                ->message('Invalid or expired OTP entered.')
                ->meta([
                    'ip' => request()->ip(),
                    'entered_code' => $request->otp_code,
                ])
                ->user($user)
                ->source('system')
                ->log();

            return back()->withErrors(['otp_code' => 'The code you entered is invalid or expired.']);
        }

        // OTP valid — finalize login
        EmailOtp::where('user_id', $user->id)->delete();
        Auth::login($user, session('login.remember'));
        session()->forget(['login.id', 'login.remember']);

        ActivityLogger::category(ActivityCategory::AUTH)
            ->action(ActivityAction::LOGIN)
            ->message('User logged in successfully using Email OTP.')
            ->meta([
                'ip' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'timestamp' => now()->toDateTimeString(),
            ])
            ->user($user)
            ->source('system')
            ->log();

        return redirect()->intended(config('fortify.home'))
            ->with('status', 'Login successful.');
    }
}
