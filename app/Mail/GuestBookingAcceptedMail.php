<?php

namespace App\Mail;

use App\Models\Booking;
use App\Support\IcsBuilder;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class GuestBookingAcceptedMail extends Mailable implements ShouldQueue
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
            subject: 'Your Beach House booking is confirmed'
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.guest.accepted',
            with: [
                'booking' => $this->booking,
            ]
        );
    }

    /**
     * @return array<int, Attachment>
     */
    public function attachments(): array
    {
        /** @var IcsBuilder $icsBuilder */
        $icsBuilder = app(IcsBuilder::class);
        $path = $icsBuilder->build($this->booking);

        return [
            Attachment::fromPath($path)
                ->as(sprintf(
                    'BHB-%s-to-%s.ics',
                    $this->booking->start_at->format('Ymd_His'),
                    $this->booking->end_at->format('Ymd_His')
                ))
                ->withMime('text/calendar'),
        ];
    }
}
