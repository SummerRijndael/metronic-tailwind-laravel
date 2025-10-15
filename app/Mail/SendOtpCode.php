<?php

// app/Mail/SendOtpCode.php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SendOtpCode extends Mailable {
    use Queueable, SerializesModels;

    /**
     * The plain text OTP code.
     */
    public $otpCode;

    /**
     * Create a new message instance.
     */
    public function __construct(string $otpCode) {
        $this->otpCode = $otpCode;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope {
        return new Envelope(
            subject: 'Your One-Time Verification Code',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content {
        // This links the mail to a simple Blade view
        return new Content(
            view: 'emails.email-otp',
        );
    }

    /**
     * Get the attachments for the message.
     */
    public function attachments(): array {
        return [];
    }
}
