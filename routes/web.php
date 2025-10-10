<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TwoFactorSetupController;

// Root route: Guests -> login, Authenticated -> dashboard
Route::get('/', function () {
    return auth()->check()
        ? redirect()->route('dashboard')
        : redirect()->route('login');
});

// Dashboard (protected)
Route::get('/dashboard', function () {
    return view('pages.dashboard.dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Profile controller routes (protected)
Route::middleware('auth')->group(function () {

    Route::get('/profile/{user?}', [ProfileController::class, 'show'])->name('profile.show');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/profile_settings', [ProfileController::class, 'settings'])->name('profile_settings.show');

    Route::post('/user/two-factor-enable', [TwoFactorController::class, 'enable'])->name('user.two-factor.enable');
    Route::post('/user/two-factor-disable', [TwoFactorController::class, 'disable'])->name('user.two-factor.disable');

    Route::get('/2fa/setup', [TwoFactorSetupController::class, 'show'])->name('2fa.setup');
    Route::post('/2fa/setup', [TwoFactorSetupController::class, 'verify'])->name('2fa.setup.verify');

});

Route::get('/userslist', function () {
    return view('pages.user.userlist');
})->middleware(['auth', 'verified'])->name('userslist');

// Tools (protected)
Route::get('/menugen', function () {
    return view('pages.tools.menu_config_gen');
})->middleware(['auth', 'verified'])->name('menugen');

// Test route (protected)
Route::get('/test', function () {
    return view('test');
})->middleware(['auth', 'verified'])->name('test');

require __DIR__.'/auth.php';
