<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewUserWelcomeMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(
        public User $user,
        public string $plainPassword
    ) {
        $this->onQueue('mail');   // or 'booking-mail' if you prefer
        $this->afterCommit();
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Welcome to BHB — Your account details'
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.users.welcome',
            with: [
                'user' => $this->user,
                'plainPassword' => $this->plainPassword,
                'appUrl' => config('app.url'),
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
