<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;       // <-- We need this for the secure direct check
use Illuminate\Support\Facades\Hash;     // <-- We need this for the secure token comparison
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse; // <-- Add this import

class CustomResetPasswordController extends Controller {
    /**
     * Display the password reset view after immediate token validation.
     * Mapped to the 'password.reset' GET route.
     * @param  \Illuminate\Http\Request  $request
     * @param  string|null  $token The token from the URL segment.
     * @return \Illuminate\View\View
     * @throws \Illuminate\Validation\ValidationException
     */
    public function create(Request $request, ?string $token = null): View|RedirectResponse {
        $email = $request->query('email');

        $resetRecord = DB::table('password_reset_tokens')
            ->where('email', $email)
            ->first();

        // Failure Check (Token non-existent or hash mismatch)
        if (is_null($resetRecord) || ! Hash::check($token, $resetRecord->token)) {

            $message = 'The password reset link is invalid or has expired. Please request a new one.';

            // This RedirectResponse object is now allowed by the new signature
            return redirect()->route('password.request')
                ->withErrors(['email' => $message])
                ->withInput(['email' => $email]);
        }

        // 3. SUCCESS: Render the reset form
        return view('auth.reset-password', [
            'request' => $request,
            'token' => $token,
            'email' => $email,
        ]);
    }

    // The 'reset' method for the POST request remains the same as it correctly uses the Password facade.
    public function reset(Request $request): \Illuminate\Http\RedirectResponse {
        // 1. Validation
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // 2. Perform Password Reset (This will run the final, official token/expiry validation)
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                // This callback runs ONLY if the token is valid, not expired, and credentials match.
                $user->forceFill([
                    'password' => Hash::make($password),
                    // 'password_changed_at' => now(), // Add your custom timestamp here if needed
                ])->save();

                event(new PasswordReset($user));
            }
        );

        // 3. Handle Response
        return $status === Password::PASSWORD_RESET
            ? redirect()->route('login')->with('status', __($status))
            : back()->withErrors(['email' => [__($status)]]);
    }
}
