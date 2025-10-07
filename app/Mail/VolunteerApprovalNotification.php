<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Volunteer;

class VolunteerApprovalNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $volunteer;

    public function __construct(User $volunteer)
    {
        $this->volunteer = $volunteer;
    }

    public function build()
    {
        return $this->subject('Your Volunteer Account Has Been Approved')
                    ->view('emails.volunteer-approval');
    }
}
