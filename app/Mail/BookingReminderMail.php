<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\VolunteerBooking;

class BookingReminderMail extends Mailable
{
    use Queueable, SerializesModels;

    public $booking;

    public function __construct(VolunteerBooking $booking)
    {
        $this->booking = $booking;
    }

    public function build()
    {
        return $this->subject('Booking Reminder')
                    ->markdown('emails.booking_reminder')
                    ->with(['booking' => $this->booking]);
    }
}
