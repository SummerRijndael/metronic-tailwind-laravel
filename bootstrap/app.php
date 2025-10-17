<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // register middleware aliases
        $middleware->alias([
            'email.otp' => \App\Http\Middleware\EnsureEmailOtpChallenged::class,
            'status.verify' => \App\Http\Middleware\VerifyUserStatus::class,
        ]);

        // attach your middleware to the web routes
        $middleware->web(append: [
            \App\Http\Middleware\MarkUserActive::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })
    ->create();
