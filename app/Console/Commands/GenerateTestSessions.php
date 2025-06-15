<?php

namespace App\Console\Commands;

use App\Models\MentorshipSession;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;

class GenerateTestSessions extends Command
{
    protected $signature = 'mentor:generate-sessions {mentor_id} {student_id} {--count=3}';
    protected $description = 'Genera sesiones de prueba entre un mentor y un estudiante';

    public function handle()
    {
        $mentor = User::find($this->argument('mentor_id'));
        $student = User::find($this->argument('student_id'));
        $count = $this->option('count');

        if (!$mentor || !$student) {
            $this->error('Uno o ambos usuarios no existen');
            if (!$mentor) $this->error("No se encontró el mentor con ID: " . $this->argument('mentor_id'));
            if (!$student) $this->error("No se encontró el estudiante con ID: " . $this->argument('student_id'));
            return 1;
        }

        // Obtener el primer curso del mentor (o crear uno si no existe)
        $course = $mentor->courses()->first();
        
        if (!$course) {
            $this->warn('El mentor no tiene cursos. Creando uno de prueba...');
            $course = $mentor->courses()->create([
                'title' => 'Curso de Prueba para ' . $student->name,
                'description' => 'Curso de prueba generado automáticamente',
                'price' => 0,
                'level' => 'beginner',
                'is_active' => true,
                'hours_per_week' => 2,
                'start_date' => now(),
                'end_date' => now()->addMonths(3),
            ]);
            $this->info("Curso creado: {$course->title} (ID: {$course->id})");
        }

        // Crear sesiones de prueba
        $sessions = [];
        $startTime = now()->addDays(1)->setTime(10, 0); // Mañana a las 10:00 AM

        for ($i = 0; $i < $count; $i++) {
            $session = MentorshipSession::create([
                'mentor_id' => $mentor->id,
                'student_id' => $student->id,
                'course_id' => $course->id,
                'title' => 'Sesión ' . ($i + 1) . ' - ' . $course->title,
                'description' => 'Sesión de prueba generada automáticamente',
                'start_time' => $startTime->copy()->addDays($i * 7), // Una sesión por semana
                'end_time' => $startTime->copy()->addDays($i * 7)->addHour(),
                'duration_minutes' => 60,
                'status' => $i === 0 ? 'scheduled' : 'pending',
                'type' => 'one_time',
                'format' => 'video_call',
                'meeting_link' => 'https://meet.mentorhub.com/' . uniqid(),
                'student_goals' => 'Objetivos de aprendizaje para la sesión ' . ($i + 1),
                'is_recurring' => false,
            ]);

            $sessions[] = $session;
            $this->line("Sesión creada: {$session->title} - " . $session->start_time->format('Y-m-d H:i'));
        }

        $this->info("\nSe crearon " . count($sessions) . " sesiones entre {$mentor->name} y {$student->name}");
        $this->info("Curso asociado: {$course->title} (ID: {$course->id})");

        return 0;
    }
}
