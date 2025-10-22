<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\User;
use Illuminate\Auth\Notifications\VerifyEmail;
use App\Mail\CustomVerifyEmail; // <-- 1. Import your Mailable Class
use Illuminate\Foundation\AliasLoader;

class AppServiceProvider extends ServiceProvider {
    /**
     * Register any application services.
     */

    public function register(): void {
        // 🚀 START PATCH: Manually register the GeoIP Facade alias
        $loader = AliasLoader::getInstance();
        $loader->alias('GeoIP', \Torann\GeoIP\Facades\GeoIP::class);
        // 🚀 END PATCH
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
