<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use App\Models\UserActivityTrail;

if (!function_exists('logUserActivity')) {
    /**
     * Logs an activity for a user.
     *
     * @param string $action   Identifier for the activity (login, logout, update_profile, etc.)
     * @param string|null $description Optional human-readable description
     * @param array|null $meta Optional structured data to store as JSON
     * @param User|null $user Optional, defaults to currently authenticated user
     * @return void
     */
    function logUserActivity(string $action, ?string $description = null, ?array $meta = null, ?User $user = null): void
    {
        $user = $user ?? Auth::user();

        if (!$user) {
            // If no user is available, skip logging
            return;
        }
        
        UserActivityTrail::create([
            'user_id'     => $user->id,
            'action'      => $action,
            'description' => $description,
            'ip_address'  => Request::ip(),
            'user_agent'  => Request::header('User-Agent'),
            'meta'        => $meta ? $meta: null,
        ]);
    }
}
