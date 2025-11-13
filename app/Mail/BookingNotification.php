<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Booking;

class BookingNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $booking;
    public $type; // 'confirmation' or 'cancellation'

    /**
     * Create a new message instance.
     */
    public function __construct(Booking $booking, string $type)
    {
        $this->booking = $booking;
        $this->type = $type;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        $subject = $this->type === 'confirmation'
            ? 'Booking Confirmation'
            : 'Booking Cancelled';

        return $this->subject($subject)
            ->view('emails.booking-notification');
    }
}
