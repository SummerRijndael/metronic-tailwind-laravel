<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        // Optional: map events to listener classes
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        parent::boot();

         // Log login with user name
            \Illuminate\Support\Facades\Event::listen(Login::class, function ($event) {
                $name = $event->user->name ?? $event->user->email ?? 'Unknown User';
                logUserActivity(
                    'login',
                    "User {$name} logged in.",  // <-- include the name here
                    ['icon' => 'ki-filled ki-entrance-left', 'color' => 'bg-accent/60']
                );
            });

            // Log logout with user name
            \Illuminate\Support\Facades\Event::listen(Logout::class, function ($event) {
                if ($event->user) {
                    $name = $event->user->name ?? $event->user->email ?? 'Unknown User';
                    logUserActivity(
                        'logout',
                        "User {$name} logged out.", // <-- include the name here
                        ['icon' => 'ki-filled ki-entrance-right', 'color' => 'bg-accent/60']
                    );
                }
            });
    }
}
