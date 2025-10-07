<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PasswordResetNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $token;
    public $user;
    public $resetUrl;

    public function __construct($token, User $user)
    {
        $this->token = $token;
        $this->user = $user;
        $this->resetUrl = route('password.reset', ['token' => $token]);
    }

    public function build()
    {
        return $this->subject('Password Reset Request - Volunteer System')
                    ->view('emails.password-reset')
                    ->with([
                        'user' => $this->user,
                        'resetUrl' => $this->resetUrl,
                        'expiryHours' => 24
                    ]);
    }
}
