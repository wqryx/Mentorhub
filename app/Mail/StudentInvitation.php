<?php

namespace App\Mail;

use App\Models\Invitation;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class StudentInvitation extends Mailable
{
    use Queueable, SerializesModels;

    public $invitation;

    public function __construct(Invitation $invitation)
    {
        $this->invitation = $invitation;
    }

    public function build()
    {
        $url = url('/register?invitation=' . $this->invitation->token);
        
        return $this->subject('Invitación para unirte a MentorHub')
                    ->markdown('emails.student-invitation')
                    ->with([
                        'url' => $url,
                        'mentor' => $this->invitation->mentor->name,
                    ]);
    }
}
