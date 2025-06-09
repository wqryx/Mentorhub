<?php

namespace App\Notifications;

use App\Models\Invitation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class StudentInvitationNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $invitation;

    public function __construct(Invitation $invitation)
    {
        $this->invitation = $invitation;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $url = url('/register?invitation=' . $this->invitation->token);
        
        return (new MailMessage)
            ->subject('Invitación para unirte a MentorHub')
            ->greeting('¡Hola!')
            ->line($this->invitation->mentor->name . ' te ha invitado a unirte a MentorHub como estudiante.')
            ->action('Aceptar invitación', $url)
            ->line('Este enlace de invitación expirará en 7 días.');
    }
}
