<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\User;
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

        // 2. This line tells Laravel: "When it's time to send the VerifyEmail notification,
        //    use our new CustomVerifyEmail Mailable instead of the default logic."
        VerifyEmail::toMailUsing(function ($notifiable, $verificationUrl) {
            return (new CustomVerifyEmail($notifiable))
                ->to($notifiable->email); // <-- ADD THIS LINE!
        });
    }
}
