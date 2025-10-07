<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\VolunteerBooking;
use App\Mail\BookingReminderMail;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class SendBookingReminderEmails extends Command
{
    protected $signature = 'send:booking-reminders';
    protected $description = 'Send reminder emails 24 hours before booking or immediately if booked less than 24 hours ago';

    public function handle()
    {
        $now = Carbon::now();

        $bookings = VolunteerBooking::whereIn('status', ['booked', 'checked_in'])
            ->whereNull('reminder_sent_at')
            ->with('volunteer', 'ngo')
            ->get();

        foreach ($bookings as $booking) {
            $bookingDate = Carbon::parse($booking->booking_date);
            $slotDateTime = $this->parseSlotDateTime($bookingDate, $booking->start_time);

            $timeUntilSlot = $slotDateTime->diffInHours($now, false);
            $timeSinceBooking = $booking->created_at->diffInHours($now);

            $shouldSendReminder = false;

            if ($timeUntilSlot <= 24 && $timeUntilSlot >= 0) {
                $shouldSendReminder = true;
            } elseif ($timeSinceBooking <= 24) {
                $shouldSendReminder = true;
            }

            if ($shouldSendReminder) {
                Mail::to('infovishalsinghrajput@gmail.com')->send(new BookingReminderMail($booking));
                $booking->reminder_sent_at = Carbon::now();
                $booking->save();

                $this->info("Reminder sent for booking ID {$booking->id} to user {$booking->volunteer->email}");
            }
        }

        return Command::SUCCESS;
    }

    private function parseSlotDateTime(Carbon $bookingDate, string $timeString): Carbon
    {
        if (preg_match('/^\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}$/', $timeString)) {
            return Carbon::parse($timeString);
        } elseif (preg_match('/^\d{2}:\d{2}:\d{2}$/', $timeString) || preg_match('/^\d{2}:\d{2}$/', $timeString)) {
            $format = strlen($timeString) === 5 ? 'Y-m-d H:i' : 'Y-m-d H:i:s';
            return Carbon::createFromFormat(
                $format,
                $bookingDate->format('Y-m-d') . ' ' . $timeString
            );
        } else {
            return Carbon::parse($timeString);
        }
    }
}
