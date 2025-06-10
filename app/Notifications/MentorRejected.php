<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\HtmlString;

class MentorRejected extends Notification implements ShouldQueue
{
    use Queueable;

    public $reason;

    /**
     * Create a new notification instance.
     */
    public function __construct($reason)
    {
        $this->reason = $reason;
    }

    /**
     * Get the notification's delivery channels.
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
        return (new MailMessage)
            ->subject('Solicitud de mentor rechazada')
            ->greeting('Hola ' . $notifiable->name . ',')
            ->line('Lamentamos informarte que tu solicitud para convertirte en mentor ha sido rechazada.')
            ->line('Razón del rechazo:')
            ->line(new HtmlString('<blockquote class="bg-gray-100 p-4 border-l-4 border-red-500 italic">' . e($this->reason) . '</blockquote>'))
            ->line('Si crees que esto es un error o deseas obtener más información, no dudes en contactar con nuestro equipo de soporte.')
            ->action('Contactar Soporte', url('/contacto'))
            ->line('Gracias por tu interés en ser parte de nuestra comunidad de mentores.');
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray(object $notifiable): array
    {
        return [
            'message' => 'Tu solicitud de mentor ha sido rechazada. Razón: ' . $this->reason,
            'type' => 'mentor_rejected',
            'action_url' => route('profile.edit'),
        ];
    }
}
