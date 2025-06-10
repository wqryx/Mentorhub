<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AccountApproved extends Notification
{
    use Queueable;

    public $role;

    /**
     * Create a new notification instance.
     */
    public function __construct($role = 'usuario')
    {
        $this->role = $role;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $roleName = $this->role === 'mentor' ? 'mentor' : 'estudiante';
        
        return (new MailMessage)
            ->subject('¡Tu cuenta ha sido aprobada!')
            ->greeting('¡Bienvenido a MentorHub!')
            ->line('Nos complace informarte que tu solicitud de registro como ' . $roleName . ' ha sido aprobada.')
            ->line('Ahora puedes iniciar sesión en tu cuenta y comenzar a utilizar todos los recursos disponibles para ti.')
            ->action('Iniciar Sesión', url('/login'))
            ->line('Si tienes alguna pregunta, no dudes en contactar con nuestro equipo de soporte.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'message' => 'Tu cuenta ha sido aprobada. ¡Bienvenido a MentorHub!',
            'type' => 'account_approved',
            'action_url' => url('/dashboard'),
        ];
    }
}
