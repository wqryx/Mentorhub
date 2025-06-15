<?php

namespace App\Console\Commands;

use App\Models\MentorshipSession;
use App\Notifications\SessionReminder;
use Carbon\Carbon;
use Illuminate\Console\Command;

class SendSessionReminders extends Command
{
    protected $signature = 'mentor:send-session-reminders {--test : Enviar recordatorios de prueba sin importar la fecha}';
    protected $description = 'Envía recordatorios para sesiones próximas';

    // Horas antes de la sesión para enviar recordatorios
    protected $reminderHours = [24, 3, 1];

    public function handle()
    {
        $this->info('=== ENVIANDO RECORDATORIOS DE SESIONES ===');
        
        $testMode = $this->option('test');
        $now = Carbon::now();
        $sentCount = 0;
        
        foreach ($this->reminderHours as $hours) {
            // Calcular el rango de tiempo para enviar recordatorios
            $startTime = $now->copy()->addHours($hours)->subMinutes(5);
            $endTime = $now->copy()->addHours($hours)->addMinutes(5);
            
            $this->info("\nVerificando sesiones en aproximadamente $hours horas...");
            
            // Obtener sesiones programadas dentro del rango de tiempo
            $query = MentorshipSession::with(['mentor', 'student', 'course'])
                ->where('status', 'scheduled');
            
            if (!$testMode) {
                $query->whereBetween('start_time', [$startTime, $endTime]);
            } else {
                $query->where('start_time', '>', $now);
                $query->limit(3); // Limitar a 3 sesiones en modo prueba
            }
            
            $sessions = $query->get();
            
            if ($sessions->isEmpty()) {
                $this->info("No hay sesiones programadas para dentro de aproximadamente $hours horas.");
                continue;
            }
            
            foreach ($sessions as $session) {
                // Calcular horas reales hasta la sesión
                $actualHours = $now->diffInHours($session->start_time);
                $hoursText = $testMode ? "$hours (simulado)" : $actualHours;
                
                $this->line("- Sesión ID {$session->id}: {$session->title}");
                
                // Enviar recordatorio al mentor
                $session->mentor->notify(new SessionReminder($session, $hours));
                $this->line("  ✓ Recordatorio enviado a mentor: {$session->mentor->name}");
                
                // Enviar recordatorio al estudiante
                $session->student->notify(new SessionReminder($session, $hours));
                $this->line("  ✓ Recordatorio enviado a estudiante: {$session->student->name}");
                
                $sentCount += 2; // Dos notificaciones por sesión
            }
        }
        
        if ($sentCount > 0) {
            $this->info("\n✅ Se enviaron $sentCount notificaciones de recordatorio.");
        } else {
            $this->info("\n✅ No se enviaron recordatorios. No hay sesiones próximas en los intervalos configurados.");
        }
        
        return 0;
    }
}
