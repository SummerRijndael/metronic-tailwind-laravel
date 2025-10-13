<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use App\Mail\TestMail; // Import the Mailable class

class SendTestEmail extends Command {
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:email {recipient}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sends a test email to the specified recipient to verify mail configuration.';

    /**
     * Execute the console command.
     */
    public function handle() {
        $recipient = $this->argument('recipient');

        $this->info("Attempting to send test email to: {$recipient}...");

        try {
            // Dispatch the Mailable
            Mail::to($recipient)->send(new TestMail());

            $this->info('✅ Success! Test email sent successfully.');
            $this->info("Please check the inbox and spam folder for {$recipient}.");
        } catch (\Exception $e) {
            $this->error('❌ Email Failed to Send!');
            $this->error('Error Message: ' . $e->getMessage());
            $this->warn('Please double-check your .env settings and clear the config cache.');
            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }
}
