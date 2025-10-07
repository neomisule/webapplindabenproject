<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Volunteer;

class VolunteerRegistrationAdminNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $volunteer;

    public function __construct(User $volunteer)
    {
        $this->volunteer = $volunteer;
    }

    public function build()
    {
        return $this->subject('New Volunteer Registration - Approval Required')
                    ->view('emails.volunteer-registration-admin');
    }
}
