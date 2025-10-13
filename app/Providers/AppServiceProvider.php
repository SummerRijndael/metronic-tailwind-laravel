<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\User;
use App\Policies\UserPolicy;
use Illuminate\Auth\Notifications\VerifyEmail;
use App\Mail\CustomVerifyEmail; // <-- 1. Import your Mailable Class

class AppServiceProvider extends ServiceProvider {
    /**
     * Register any application services.
     */

    public function register(): void {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void {
        // 👇 Important for auto-discovery
        // Tell Laravel how to guess policy names
        Gate::guessPolicyNamesUsing(function ($modelClass) {
            return 'App\\Policies\\' . class_basename($modelClass) . 'Policy';
        });

        Gate::policy(User::class, UserPolicy::class);

        // 2. This line tells Laravel: "When it's time to send the VerifyEmail notification,
        //    use our new CustomVerifyEmail Mailable instead of the default logic."
        VerifyEmail::toMailUsing(function ($notifiable, $verificationUrl) {
            return (new CustomVerifyEmail($notifiable))
                ->to($notifiable->email); // <-- ADD THIS LINE!
        });
    }
}
