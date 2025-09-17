<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('pages.dashboard.dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/myprofile', function () {
    return view('pages.user.profile');
})->name('myprofile')->middleware(['auth', 'verified']);

Route::get('/profile_settings', function () {
    return view('pages.user.settings');
})->middleware(['auth', 'verified'])->name('profile_settings');

Route::get('/userslist', function () {
    return view('pages.user.userlist');
})->middleware(['auth', 'verified'])->name('userslist');

Route::get('/menugen', function () {
    return view('pages.tools.menu_config_gen');
})->middleware(['auth', 'verified'])->name('menugen');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
