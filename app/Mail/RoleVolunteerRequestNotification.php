<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RoleVolunteerRequestNotification extends Mailable
{
    use Queueable, SerializesModels;

     public $user;
    public $type; // 'staff' or 'user'

    public function __construct(User $user, $type)
    {
        $this->user = $user;
        $this->type = $type;
    }

    public function build()
    {
        $subject = ucfirst($this->type) . ' Volunteer Request - Approval Required';
        return $this->subject($subject)
                   ->view('emails.role-volunteer-request-admin');
    }
}
