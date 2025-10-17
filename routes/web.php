<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\UserManagement\DashboardController;

// --- Custom Authentication Controller Imports ---
// NOTE: These are custom controllers that override or supplement Fortify's default behavior.
use App\Http\Controllers\Auth\UpdatePasswordController;
use App\Http\Controllers\Auth\CustomResetPasswordController; // Custom controller for immediate token validation
use App\Http\Controllers\TwoFactorEmailController;

Route::middleware(['web', 'email.otp'])->group(function () {
    // Route to show the challenge view (your test blade)
    Route::get('/two-factor/email', [TwoFactorEmailController::class, 'show'])->name('two-factor.email.show');

    // Route to send the email code
    Route::post('/two-factor/email/request', [TwoFactorEmailController::class, 'requestOtp'])->name('two-factor.email.request');

    // Route to verify the submitted code
    Route::post('/two-factor/email/verify', [TwoFactorEmailController::class, 'verifyOtp'])->name('two-factor.email.verify');
});

// -----------------------------------------------------------------------------
// I. ROOT & AUTHENTICATION FLOWS (Guests & Unauthenticated Users)
// -----------------------------------------------------------------------------

// Root Route: Redirects guests to login and authenticated users to dashboard.
Route::get('/', function () {
    return auth()->check()
        ? redirect()->route('dashboard')
        : redirect()->route('login'); // Assuming 'login' is a route name defined by Fortify/Breeze
});


// Password Reset Override Routes (Custom Validation on GET Request)
// These two routes override Fortify's default password reset handling to implement
// immediate token validation on the initial page load (GET request).
Route::get('/reset-password/{token}', [CustomResetPasswordController::class, 'create'])
    ->middleware('guest') // Important: Must be accessible to guests
    ->name('password.reset');

Route::post('/reset-password', [CustomResetPasswordController::class, 'reset'])
    ->middleware('guest') // Important: Must be accessible to guests
    ->name('password.update');


// -----------------------------------------------------------------------------
// II. AUTHENTICATED ROUTES (Middleware: 'auth', 'verified')
// -----------------------------------------------------------------------------

Route::middleware(['auth', 'status.verify', 'verified'])->group(function () {

    // A. CORE APPLICATION ROUTES

    // Dashboard (Main application landing page)
    Route::get('/dashboard', function () {
        return view('pages.dashboard.dashboard');
    })->name('dashboard');

    // B. USER PROFILE MANAGEMENT

    // Authenticated Password Update (Custom Controller with Toast/JSON response)
    // NOTE: This is for profile settings, NOT for unauthenticated password reset.
    Route::put('/user/password', [UpdatePasswordController::class, 'update'])
        ->name('user-password.update');

    // Profile Controller Group
    Route::get('/profile/{user?}', [ProfileController::class, 'show'])->name('profile.show');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Profile Settings/Tools Routes (Grouped under ProfileController)
    Route::get('/profile_settings', [ProfileController::class, 'settings'])->name('profile_settings.show');

    Route::prefix('admin/user-management')->name('admin.user_management.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/userslist', [DashboardController::class, 'list'])->name('dashboard.list');
    });

    // C. TOOL & TEST ROUTES

    // Tools: Menu Configuration Generator
    Route::get('/menugen', function () {
        return view('pages.tools.menu_config_gen');
    })->name('menugen');

    // Test Routes (For development/debugging)
    Route::get('/test', function () {
        return view('welcome');
    })->name('test');

    Route::get('/userslist', function () {
        return view('test2'); // Assuming 'test2' is a view for user list preview
    })->name('userslist');
});

// Fortify/Breeze Authentication routes are assumed to be loaded elsewhere (e.g., Fortify::routes() or /auth.php).
// If you are using Fortify::routes(), ensure the custom password reset routes above come AFTER it,
// OR that Fortify::routes() is not being explicitly called, as your custom routes override its defaults.
