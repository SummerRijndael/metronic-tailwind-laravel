<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Auth\Events\PasswordReset;
use App\Helpers\ActivityLogger;
use App\Enums\ActivityCategory;
use App\Enums\ActivityAction;

class CustomResetPasswordController extends Controller {
    /**
     * Display the password reset view after immediate token validation.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string|null  $token
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function create(Request $request, ?string $token = null): View|RedirectResponse {
        $email = $request->query('email');

        $resetRecord = DB::table('password_reset_tokens')
            ->where('email', $email)
            ->first();

        if (is_null($resetRecord) || ! Hash::check($token, $resetRecord->token)) {
            $message = 'The password reset link is invalid or has expired. Please request a new one.';
            return redirect()->route('password.request')
                ->withErrors(['email' => $message])
                ->withInput(['email' => $email]);
        }

        return view('auth.reset-password', [
            'request' => $request,
            'token' => $token,
            'email' => $email,
        ]);
    }

    /**
     * Handle the password reset POST request and log activity.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function reset(Request $request): RedirectResponse {
        // 1️⃣ Validation
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $email = $request->input('email');

        // 2️⃣ Perform Password Reset
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                    'password_changed_at' => now(),
                ])->save();

                // Fire the standard PasswordReset event
                event(new PasswordReset($user));

                // 3️⃣ Activity Logging
                $meta = [
                    'ip' => request()->ip(),
                    'user_agent' => request()->userAgent(),
                    'timestamp' => now()->toDateTimeString(),
                    'icon' => 'ki-filled ki-lock',
                    'color' => 'bg-info/60',
                ];

                ActivityLogger::category(ActivityCategory::AUTH)
                    ->action(ActivityAction::PASSWORD_RESET)
                    ->message("User {$user->name} reset their password via email link.")
                    ->user($user)
                    ->meta($meta)
                    ->source('system')
                    ->log();
            }
        );

        // 4️⃣ Response Handling
        return $status === Password::PASSWORD_RESET
            ? redirect()->route('login')->with('status', __($status))
            : back()->withErrors(['email' => [__($status)]]);
    }
}
