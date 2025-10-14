<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification; // We keep this use statement
use App\Listeners\LogSuccessfulLogin;
use App\Listeners\LogSuccessfulLogout;
use App\Listeners\LogNewUserRegistration;

class EventServiceProvider extends ServiceProvider {

    /**
     * CRITICAL FIX: Disable Event Auto-Discovery
     * This stops the duplicate entries (like the '@handle' ones) caused by a conflict
     * between manual registration and discovery.
     *
     * @var bool
     */
    public static $shouldDiscoverEvents = false;

    /**
     * The event listener mappings for the application.
     * We now register each listener exactly ONCE.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Login::class => [
            LogSuccessfulLogin::class,
        ],
        Logout::class => [
            LogSuccessfulLogout::class,
        ],
        Registered::class => [
            LogNewUserRegistration::class,
            // Only one entry for the email verification listener:
            SendEmailVerificationNotification::class,
        ],
    ];

    /**
     * CRITICAL FIX: Configure email verification listeners.
     * Leaving this empty prevents the framework's base ServiceProvider
     * from registering the listener automatically (which was causing a duplicate).
     */
    protected function configureEmailVerification(): void {
        // We handle registration above in $listen, so we block the framework's default here.
    }

    /**
     * Register any events for your application.
     */
    public function boot(): void {
        parent::boot();
    }
}
