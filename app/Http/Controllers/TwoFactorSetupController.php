<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Laravel\Fortify\Actions\EnableTwoFactorAuthentication;

class TwoFactorSetupController extends Controller
{
    public function show(Request $request)
    {
        $user = $request->user();

        // Ensure 2FA secret exists
        if (!$user->two_factor_secret) {
            app(EnableTwoFactorAuthentication::class)($user);
        }

        
        $qrCode = $user->twoFactorQrCodeSvg(); // For display
        $recoveryCodes = $user->recoveryCodes();

        return view('auth.setup2fa', compact('qrCode', 'recoveryCodes'));
    }

    public function verify(Request $request)
    {
        $request->validate([
            'code' => ['required', 'digits:6'],
        ]);

        $user = $request->user();

        // Verify the code
        if (! app(\PragmaRX\Google2FA\Google2FA::class)->verifyKey(
            decrypt($user->two_factor_secret),
            $request->code
        )) {
            return back()->withErrors(['code' => 'Invalid verification code.']);
        }

        // Mark as confirmed
        $user->two_factor_confirmed_at = now();
        $user->save();

        return redirect()->route('2fa/setup')->with('status', 'Two-Factor Authentication enabled successfully!');
    }
}
