<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use Illuminate\Support\Str;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'uuid' => Str::uuid(), // ✅ generate UUID for new account
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'permissions' => [
                'view_profile' => true,
                'edit_profile' => false,
                'delete_user' => false,
            ],
            'settings' => [ 
                                'localization' => [
                                'language' => 'en',
                                'timezone' => 'UTC',
                                'date_format' => 'Y-m-d',
                                'time_format' => 'H:i',
                            ],
                            'appearance' => [
                                'theme' => 'light',
                                'color_scheme' => 'default',
                            ],
                            'notifications' => [
                                'email_promotions' => true,
                                'app_updates' => true,
                                'desktop_alerts' => false,
                            ],
                        ],
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }
}