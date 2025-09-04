<?php

namespace App\Mail;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class GuestBookingCanceledMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(
        public Booking $booking
    ) {
        $this->onQueue('booking-mail');
        $this->afterCommit();
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Your Beach House booking has been canceled'
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.guest.canceled',
            with: [
                'booking' => $this->booking,
            ]
        );
    }
}
