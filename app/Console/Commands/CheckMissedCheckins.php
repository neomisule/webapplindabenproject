<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\VolunteerBooking;
use App\Models\User;
use Carbon\Carbon;

class CheckMissedCheckins extends Command
{
    protected $signature = 'check:missed-checkins';
    protected $description = 'Check for missed check-ins and add strikes';

    public function handle()
    {
        $now = Carbon::now();
        $this->info("Checking for missed check-ins at {$now->format('Y-m-d H:i:s')}");

        $bookings = VolunteerBooking::where('status', 'booked')
            ->whereDate('date', $now->toDateString())
            ->whereTime('end_time', '<', $now->toTimeString())
            ->with('volunteer')
            ->get();

        $this->info("Found {$bookings->count()} potential missed check-ins");

        foreach ($bookings as $booking) {
            $user = $booking->volunteer;

            if ($user->status === 0) {
                $this->warn("User {$user->id} is already banned - skipping");
                continue;
            }

            $user->increment('strikes');
            $this->info("Added strike to user {$user->id}. Total strikes: {$user->strikes}");

            if ($user->strikes >= 5) {
                $user->update([
                    'status' => 0,
                    'temporary_ban_until' => $now->copy()->addDays(30)
                ]);

                $this->warn("User {$user->id} has reached 5 strikes - temporary ban for 30 days");

            }

            $booking->update(['status' => 'missed']);
            $this->info("Marked booking {$booking->id} as missed");
        }

        $this->info('Missed check-ins processing completed');
        return Command::SUCCESS;
    }
}
