<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use Laravel\Fortify\Contracts\TwoFactorAuthenticationProvider;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;
use Illuminate\Support\Str;

class TwoFactorSetup extends Component {
    public $step = 1;
    public $password = '';
    public $errorMessage = '';
    public $qrCode = '';
    public $verificationCode = '';
    public $recoveryCodes = [];
    public $twoFactorSecret = '';

    // 'enable' (default setup), 'manage' (2FA active, show disable/regen options), 'disable', 'regen' (in verification step)
    public $action = 'enable';

    public function mount() {
        $user = Auth::user();
        // If the user already has 2FA secret, but hasn't confirmed it, we allow them to proceed to Step 2
        // to finish setup (action remains 'enable').
        if ($user && $user->two_factor_secret && $user->two_factor_confirmed_at) {
            $this->action = 'manage'; // Only set to manage if fully confirmed
        }
    }

    /* ---------------- Step 1: Password Verification ---------------- */
    public function verifyPassword() {
        $this->validate(['password' => 'required']);

        $user = Auth::user();
        if (!$user || !Hash::check($this->password, $user->password)) {
            $this->errorMessage = 'The password you entered is incorrect.';
            return;
        }

        $this->reset('password', 'errorMessage', 'verificationCode');

        // *** FIX APPLIED HERE: Set the action state based on confirmation status ***
        if ($user->two_factor_secret && $user->two_factor_confirmed_at) {
            $this->action = 'manage'; // 2FA is active, show management options
            $this->step = 2;
        } else {
            $this->prepareEnable2FA();
            $this->action = 'enable'; // 2FA is not active, proceed with setup
            $this->step = 2;
        }
    }

    /* ---------------- Prepare Enable Flow ---------------- */
    public function prepareEnable2FA() {
        $user = Auth::user();
        $provider = app(TwoFactorAuthenticationProvider::class);

        // Generate raw Base32 secret
        $secret = $provider->generateSecretKey();

        $this->twoFactorSecret = $secret;

        // Save temporarily (raw, unencrypted) for QR & verification
        $user->forceFill(['two_factor_secret' => $secret])->save();

        // Generate QR code
        $otpauthUrl = $provider->qrCodeUrl(config('app.name'), $user->email, $secret);
        $writer = new Writer(new ImageRenderer(new RendererStyle(200), new SvgImageBackEnd()));
        $this->qrCode = 'data:image/svg+xml;base64,' . base64_encode($writer->writeString($otpauthUrl));
    }

    /* ---------------- Step 2: Confirm Enable ---------------- */
    public function confirmTwoFactor() {
        $user = Auth::user();
        $provider = app(TwoFactorAuthenticationProvider::class);

        // Fetch the secret (which is currently raw and unencrypted from prepareEnable2FA)
        $rawSecret = strtoupper(trim($user->two_factor_secret));

        if (!$provider->verify($rawSecret, $this->verificationCode)) {
            $this->errorMessage = 'Invalid code. Please try again.';
            return;
        }

        // Generate recovery codes
        $this->recoveryCodes = collect(range(1, 8))
            ->map(fn() => Str::random(10) . '-' . Str::random(10))
            ->all();

        // Commit to DB (encrypt the secret and codes)
        $user->forceFill([
            'two_factor_secret' => encrypt($rawSecret),
            'two_factor_recovery_codes' => encrypt(json_encode($this->recoveryCodes)),
            'two_factor_confirmed_at' => now(),
        ])->save();

        $this->reset('verificationCode', 'errorMessage');
        $this->step = 3; // Show recovery codes
    }

    /* ---------------- Manage 2FA Actions ---------------- */

    // Called when user selects 'Disable 2FA'
    public function startDisableFlow() {
        $this->action = 'disable';
        $this->reset('verificationCode', 'errorMessage');
    }

    // Called when user selects 'Regenerate Codes'
    public function startRegenFlow() {
        $this->action = 'regen';
        $this->reset('verificationCode', 'errorMessage');
    }

    /* ---------------- Disable 2FA ---------------- */
    public function disableTwoFactor() {
        $user = Auth::user();
        $provider = app(TwoFactorAuthenticationProvider::class);

        // Decrypt the stored secret to verify the code
        $rawSecret = decrypt($user->two_factor_secret);

        if (!$provider->verify($rawSecret, $this->verificationCode)) {
            $this->errorMessage = 'Invalid code. Please try again.';
            return;
        }

        $user->forceFill([
            'two_factor_secret' => null,
            'two_factor_recovery_codes' => null,
            'two_factor_confirmed_at' => null,
        ])->save();

        $this->resetFlow(); // Back to fresh start
        $this->step = 4; // Show success/completion screen (Disabled)
    }

    /* ---------------- Regenerate Recovery Codes ---------------- */
    public function regenerateRecoveryCodes() {
        $user = Auth::user();
        $provider = app(TwoFactorAuthenticationProvider::class);

        // Decrypt the stored secret to verify the code
        $rawSecret = decrypt($user->two_factor_secret);

        if (!$provider->verify($rawSecret, $this->verificationCode)) {
            $this->errorMessage = 'Invalid code. Please try again.';
            return;
        }

        // Generate new recovery codes
        $this->recoveryCodes = collect(range(1, 8))
            ->map(fn() => Str::random(10) . '-' . Str::random(10))
            ->all();

        // Save new encrypted codes
        $user->forceFill([
            'two_factor_recovery_codes' => encrypt(json_encode($this->recoveryCodes)),
        ])->save();

        $this->reset('verificationCode', 'errorMessage');
        $this->step = 3; // Show new codes
    }

    /* ---------------- Navigation ---------------- */
    public function nextStep() {
        // If enabling, move from codes (3) to success (4)
        if ($this->step == 3) $this->step = 4;

        // If disabling/re-enabling, use specific action methods
    }

    public function previousStep() {
        if ($this->step > 1) {
            // If backing out of disable/regen flow, go back to manage menu
            if (in_array($this->action, ['disable', 'regen'])) {
                $this->action = 'manage';
                $this->reset('verificationCode', 'errorMessage');
            } else {
                $this->step--;
            }
        }
    }

    public function resetFlow() {
        $this->reset([
            'step',
            'password',
            'errorMessage',
            'verificationCode',
            'qrCode',
            'recoveryCodes',
            'twoFactorSecret',
            'action',
        ]);
        $this->step = 1;
        $this->action = 'enable';
    }

    public function render() {
        return view('livewire.two-factor-setup');
    }
}
