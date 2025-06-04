<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\MentorshipSession;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class CreateMentorshipSessionDirectly extends Command
{
    protected $signature = 'mentorship:create-session-directly';
    protected $description = 'Crea una sesión de mentoría directamente en la base de datos';

    public function handle()
    {
        $this->info('Buscando usuarios...');
        
        // Obtener el mentor
        $mentor = User::where('email', 'mentor@mentorhub.com')->first();
        if (!$mentor) {
            $this->error('No se encontró el mentor con el email: mentor@mentorhub.com');
            return 1;
        }

        // Obtener el estudiante
        $student = User::where('email', 'estudiante@mentorhub.com')->first();
        if (!$student) {
            $this->error('No se encontró el estudiante con el email: estudiante@mentorhub.com');
            return 1;
        }

        $this->info("Mentor encontrado: {$mentor->name} (ID: {$mentor->id})");
        $this->info("Estudiante encontrado: {$student->name} (ID: {$student->id})");

        // Verificar si ya existe una sesión
        $existingSession = MentorshipSession::where('mentor_id', $mentor->id)
            ->where('student_id', $student->id)
            ->first();

        if ($existingSession) {
            $this->warn('¡Atención! Ya existe una sesión entre estos usuarios:');
            $this->line("- ID de la sesión: {$existingSession->id}");
            $this->line("- Estado: {$existingSession->status}");
            $this->line("- Fecha: {$existingSession->start_time}");
            return 0;
        }

        // Crear la sesión usando DB::table para evitar problemas de modelo
        try {
            $now = now();
            $startTime = $now->copy()->addDay();
            
            $sessionId = DB::table('mentor_sessions')->insertGetId([
                'title' => 'Sesión de mentoría inicial',
                'description' => 'Sesión de mentoría entre ' . $mentor->name . ' y ' . $student->name,
                'mentor_id' => $mentor->id,
                'student_id' => $student->id,
                'type' => 'individual',
                'format' => 'video',
                'status' => 'scheduled',
                'start_time' => $startTime,
                'end_time' => $startTime->copy()->addHour(),
                'duration_minutes' => 60,
                'meeting_link' => 'https://meet.mentorhub.com/' . uniqid(),
                'student_goals' => 'Conocer al mentor y establecer objetivos de aprendizaje',
                'is_recurring' => false,
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            $this->info('\n¡Sesión de mentoría creada exitosamente!');
            $this->line("- ID de la sesión: {$sessionId}");
            $this->line("- Mentor: {$mentor->name} (ID: {$mentor->id})");
            $this->line("- Estudiante: {$student->name} (ID: {$student->id})");
            $this->line("- Fecha: " . $startTime->format('Y-m-d H:i:s'));
            
        } catch (\Exception $e) {
            $this->error('Error al crear la sesión: ' . $e->getMessage());
            $this->error('Archivo: ' . $e->getFile() . ' - Línea: ' . $e->getLine());
            return 1;
        }

        return 0;
    }
}
