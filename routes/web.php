<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('pages.dashboard.dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/menugen', function () {
    return view('pages.tools.menu_config_gen');
})->middleware(['auth', 'verified'])->name('menugen');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/myprofile', function () {
    return view('pages.user.profile');
})->name('myprofile')->middleware(['auth', 'verified']);


require __DIR__.'/auth.php';
