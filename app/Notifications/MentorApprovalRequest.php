<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\HtmlString;

class MentorApprovalRequest extends Notification
{
    use Queueable;

    public $user;

    /**
     * Create a new notification instance.
     */
    public function __construct(User $user)
    {
        $this->user = $user;
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
        $approveUrl = route('admin.users.approve', $this->user->id);
        $rejectUrl = route('admin.users.reject', $this->user->id);
        
        return (new MailMessage)
            ->subject('Solicitud de aprobación de mentor')
            ->greeting('¡Nueva solicitud de mentor!')
            ->line('Un nuevo usuario ha solicitado registrarse como mentor y está esperando tu aprobación.')
            ->line(new HtmlString(
                '<strong>Nombre:</strong> ' . $this->user->name . '<br>' .
                '<strong>Email:</strong> ' . $this->user->email . '<br>' .
                '<strong>Fecha de registro:</strong> ' . $this->user->created_at->format('d/m/Y H:i')
            ))
            ->line('Por favor, revisa la información del usuario y decide si aprobar o rechazar la solicitud.')
            ->action('Aprobar Mentor', $approveUrl)
            ->line(new HtmlString(
                'O copia y pega esta URL en tu navegador:<br>' . 
                '<a href="' . $approveUrl . '">' . $approveUrl . '</a>'
            ));
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray(object $notifiable): array
    {
        return [
            'user_id' => $this->user->id,
            'user_name' => $this->user->name,
            'message' => 'Nueva solicitud de mentor pendiente de aprobación',
            'type' => 'mentor_approval_request',
            'action_url' => route('admin.users.show', $this->user->id),
        ];
    }
}
