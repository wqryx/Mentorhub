<?php

namespace App\Console\Commands;

use App\Models\MentorshipSession;
use Illuminate\Console\Command;

class EnrichSessionDetails extends Command
{
    protected $signature = 'mentor:enrich-session-details';
    protected $description = 'Agrega más detalles a las sesiones de mentoría existentes';

    public function handle()
    {
        $this->info('=== ENRIQUECIENDO DETALLES DE SESIONES ===');
        
        $sessions = MentorshipSession::all();
        $updatedCount = 0;
        
        foreach ($sessions as $session) {
            $updates = [];
            
            // Agregar descripción si está vacía
            if (empty($session->description)) {
                $courseName = $session->course ? $session->course->name : "curso";
                $session->description = "Sesión de mentoría sobre $courseName. Durante esta sesión se abordarán conceptos clave y se resolverán dudas específicas del estudiante.";
                $updates[] = 'descripción';
            }
            
            // Agregar objetivos del estudiante si están vacíos
            if (empty($session->student_goals) || $session->student_goals === '[]' || $session->student_goals === 'null') {
                $session->student_goals = json_encode([
                    "Comprender los conceptos principales del tema",
                    "Resolver dudas específicas",
                    "Aplicar conocimientos en casos prácticos"
                ]);
                $updates[] = 'objetivos del estudiante';
            }
            
            // Agregar duración si está vacía
            if (empty($session->duration_minutes) || $session->duration_minutes == 0) {
                $session->duration_minutes = 60; // Duración por defecto: 60 minutos
                $updates[] = 'duración';
            }
            
            // Agregar formato si está vacío
            if (empty($session->format)) {
                $session->format = 'video_call';
                $updates[] = 'formato';
            }
            
            // Agregar enlace de reunión si está vacío y es videollamada
            if (empty($session->meeting_link) && $session->format === 'video_call') {
                $meetingId = strtolower(substr(md5($session->id . $session->mentor_id . $session->student_id), 0, 10));
                $session->meeting_link = "https://meet.mentorhub.com/{$meetingId}";
                $updates[] = 'enlace de reunión';
            }
            
            // Guardar cambios si se hizo alguna modificación
            if (!empty($updates)) {
                $session->save();
                $updatedCount++;
                $this->line("- Sesión ID {$session->id}: Actualizados " . implode(', ', $updates));
            }
        }
        
        if ($updatedCount > 0) {
            $this->info("\n✅ Se actualizaron detalles en $updatedCount sesiones.");
        } else {
            $this->info("\n✅ Todas las sesiones ya tenían detalles completos.");
        }
        
        return 0;
    }
}
