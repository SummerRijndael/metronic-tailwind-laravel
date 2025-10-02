<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\User;
use App\Policies\UserPolicy;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */

    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {   
        // 👇 Important for auto-discovery
        // Tell Laravel how to guess policy names
         Gate::guessPolicyNamesUsing(function ($modelClass) {
             return 'App\\Policies\\'.class_basename($modelClass).'Policy';
         });

         Gate::policy(User::class, UserPolicy::class);

        // Global admin check
         Gate::define('is-admin', function (User $user) {
              return $user->is_admin === true; // or however you flag admin users
         });
    }
}
