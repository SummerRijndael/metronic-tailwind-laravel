<?php

namespace App\Providers;

// --- Fortify Action Imports ---
use App\Actions\Fortify\CreateNewUser;
use App\Actions\Fortify\ResetUserPassword;
use App\Actions\Fortify\UpdateUserPassword;
use App\Actions\Fortify\UpdateUserProfileInformation;
use App\Actions\CheckUserStatusDuringLogin;
use Laravel\Fortify\Actions\AttemptToAuthenticate;

// --- Laravel Framework & Core Imports ---
use App\Models\User;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

// --- Fortify Imports ---
use Laravel\Fortify\Contracts\ResetPasswordViewResponse;
use Laravel\Fortify\Actions\RedirectIfTwoFactorAuthenticatable;
use Laravel\Fortify\Fortify;

// --- Custom Responses & Views ---
use App\Http\Responses\CustomResetPasswordViewResponse; // Custom response for reset view

class FortifyServiceProvider extends ServiceProvider {
    /**
     * Register any application services.
     */
    public function register(): void {
        // Bind the custom response for the Reset Password View
        // NOTE: This will only be used if the password.reset route is NOT overridden by a custom controller.
        $this->app->singleton(ResetPasswordViewResponse::class, CustomResetPasswordViewResponse::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void {
        // --- 1. ACTION BINDINGS ---

        // Custom Action to handle user registration (app/Actions/Fortify/CreateNewUser.php)
        Fortify::createUsersUsing(CreateNewUser::class);

        // Action to handle password updates for an AUTHENTICATED user (e.g., Profile Settings)
        Fortify::updateUserPasswordsUsing(UpdateUserPassword::class);

        // Action to handle password RESETS for an UNAUTHENTICATED user (includes token check/deletion)
        Fortify::resetUserPasswordsUsing(ResetUserPassword::class);

        // Action for Two-Factor Authentication redirect logic
        Fortify::redirectUserForTwoFactorAuthenticationUsing(RedirectIfTwoFactorAuthenticatable::class);

        // Fortify::updateUserProfileInformationUsing(UpdateUserProfileInformation::class); // Commented out by dev

        // --- 2. RATE LIMITING ---

        // Throttling for general login attempts (limits by email and IP address)
        RateLimiter::for('login', function (Request $request) {
            $throttleKey = Str::transliterate(Str::lower($request->input(Fortify::username())) . '|' . $request->ip());
            return Limit::perMinute(5)->by($throttleKey);
        });

        // Throttling for two-factor challenge attempts (limits by session ID)
        RateLimiter::for('two-factor', function (Request $request) {
            return Limit::perMinute(5)->by($request->session()->get('login.id'));
        });

        Fortify::authenticateThrough(function () {
            return [
                \Laravel\Fortify\Actions\EnsureLoginIsNotThrottled::class,
                \App\Actions\CheckUserStatusDuringLogin::class,       // 🛡️ custom check early
                \Laravel\Fortify\Actions\RedirectIfTwoFactorAuthenticatable::class,
                \Laravel\Fortify\Actions\AttemptToAuthenticate::class,
                \Laravel\Fortify\Actions\PrepareAuthenticatedSession::class,     // continue normal login
            ];
        });

        // --- 3. VIEW DEFINITIONS (Frontend Customization) ---

        // Binds Fortify's internal routes to use your custom Blade views
        Fortify::loginView(fn() => view('auth.login'));
        Fortify::registerView(fn() => view('auth.register'));
        Fortify::requestPasswordResetLinkView(fn() => view('auth.forgot-password'));

        // NOTE: The request object is passed to the resetPasswordView for accessing token/email parameters.
        Fortify::resetPasswordView(fn($request) => view('auth.reset-password', ['request' => $request]));

        Fortify::twoFactorChallengeView(fn() => view('auth.two-factor-challenge'));
        Fortify::verifyEmailView(fn() => view('auth.verify-email'));

        // --- 4. REDIRECTS ---

        // Define where the user is redirected after successful registration and login
        Fortify::redirects('register', '/dashboard');
        Fortify::redirects('login', '/dashboard');

        // --- 5. CUSTOM AUTHENTICATION LOGIC ---

        // Override the default way Fortify authenticates a user (allows for custom logic before login)
        Fortify::authenticateUsing(function ($request) {
            // Retrieve user by email (or whatever is defined as Fortify::username())
            $user = User::where(Fortify::username(), $request->input(Fortify::username()))->first();

            // Check if user exists and password is correct
            if ($user && Hash::check($request->password, $user->password)) {
                return $user;
            }

            // If credentials fail, throw a validation exception
            throw ValidationException::withMessages([
                Fortify::username() => [__('These credentials do not match our records.')],
            ]);
        });
    }
}
