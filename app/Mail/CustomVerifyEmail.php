<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\URL;

class CustomVerifyEmail extends Mailable {
    use Queueable, SerializesModels;

    /**
     * The user instance.
     *
     * @var \Illuminate\Contracts\Auth\MustVerifyEmail
     */
    public $user;

    /**
     * The verification URL.
     *
     * @var string
     */
    public $verificationUrl;

    /**
     * Create a new message instance.
     */
    public function __construct(MustVerifyEmail $user) {
        $this->user = $user;
        $this->verificationUrl = $this->verificationUrl($user);
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope {
        return new Envelope(
            subject: 'Action Required: Verify Your Email Address',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content {
        // This points to the Blade template below: resources/views/emails/custom-verify-email.blade.php
        return new Content(
            markdown: 'emails.auth.verify-email',
            with: [
                'user' => $this->user,
                'verificationUrl' => $this->verificationUrl,
            ],
        );
    }

    /**
     * Get the verification URL for the given notifiable.
     * This logic is necessary to manually generate the correct signed URL.
     */
    protected function verificationUrl($user) {
        return URL::temporarySignedRoute(
            'verification.verify',
            Carbon::now()->addMinutes(Config::get('auth.verification.expire', 60)),
            [
                'id' => $user->getKey(),
                'hash' => sha1($user->getEmailForVerification()),
            ]
        );
    }
}
