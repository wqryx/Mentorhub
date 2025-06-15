<?php

namespace App\Notifications;

use App\Models\MentorshipSession;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SessionReminder extends Notification implements ShouldQueue
{
    use Queueable;

    protected $session;
    protected $hoursUntilSession;

    /**
     * Create a new notification instance.
     */
    public function __construct(MentorshipSession $session, int $hoursUntilSession)
    {
        $this->session = $session;
        $this->hoursUntilSession = $hoursUntilSession;
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
        $roleText = $notifiable->id === $this->session->mentor_id ? 'mentor' : 'estudiante';
        $otherPersonText = $notifiable->id === $this->session->mentor_id ? 'tu estudiante' : 'tu mentor';
        $otherPersonName = $notifiable->id === $this->session->mentor_id ? $this->session->student->name : $this->session->mentor->name;
        
        return (new MailMessage)
                    ->subject('Recordatorio: Sesión de mentoría en ' . $this->hoursUntilSession . ' horas')
                    ->greeting('Hola ' . $notifiable->name)
                    ->line('Te recordamos que tienes una sesión de mentoría programada para dentro de ' . $this->hoursUntilSession . ' horas.')
                    ->line('Detalles de la sesión:')
                    ->line('Título: ' . $this->session->title)
                    ->line('Fecha y hora: ' . $this->session->start_time->format('d/m/Y H:i'))
                    ->line('Duración: ' . $this->session->duration_minutes . ' minutos')
                    ->line($otherPersonText . ': ' . $otherPersonName)
                    ->action('Ver detalles de la sesión', url('/dashboard'))
                    ->line('Gracias por usar MentorHub!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'session_id' => $this->session->id,
            'title' => $this->session->title,
            'start_time' => $this->session->start_time->format('Y-m-d H:i:s'),
            'hours_until_session' => $this->hoursUntilSession,
            'type' => 'session_reminder'
        ];
    }
}
