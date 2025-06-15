<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\MentorshipSession;
use App\Models\Course;
use Illuminate\Support\Facades\DB;

class CheckUsersAndRelations extends Command
{
    protected $signature = 'check:users-relations';
    protected $description = 'Verifica usuarios y sus relaciones en el sistema';

    public function handle()
    {
        $this->info('=== VERIFICACIÓN DE USUARIOS Y RELACIONES ===\n');

        // 1. Verificar usuarios
        $users = User::all();
        $this->info("Usuarios en el sistema: " . $users->count());

        $this->info("\n=== DETALLE DE USUARIOS ===");
        foreach ($users as $user) {
            $this->line("ID: {$user->id} | Nombre: {$user->name} | Email: {$user->email}");
            
            // Roles
            try {
                $roles = $user->getRoleNames()->implode(', ');
                $this->line("  - Roles: " . ($roles ?: 'Sin roles asignados'));
            } catch (\Exception $e) {
                $this->error("  - Error al obtener roles: " . $e->getMessage());
            }

            // Cursos creados
            try {
                $courses = $user->courses()->count();
                $this->line("  - Cursos creados: " . $courses);
            } catch (\Exception $e) {
                $this->error("  - Error al obtener cursos: " . $e->getMessage());
            }

            // Sesiones
            try {
                $mentorSessions = $user->mentorSessions()->count();
                $studentSessions = $user->studentSessions()->count();
                $this->line("  - Sesiones como mentor: " . $mentorSessions);
                $this->line("  - Sesiones como estudiante: " . $studentSessions);
            } catch (\Exception $e) {
                $this->error("  - Error al obtener sesiones: " . $e->getMessage());
            }

            $this->line("");
        }

        // 2. Verificar relaciones entre usuarios
        $this->info("\n=== RELACIONES ENTRE USUARIOS ===");
        
        // Verificar relaciones de mentoría
        $mentorSessions = MentorshipSession::all();
        if ($mentorSessions->isEmpty()) {
            $this->info("No hay sesiones de mentoría registradas en el sistema.");
        } else {
            $this->info("Sesiones de mentoría registradas:");
            foreach ($mentorSessions as $session) {
                $this->line("- ID {$session->id}: {$session->mentor->name} (Mentor) -> {$session->student->name} (Estudiante)");
                $this->line("  Curso: " . ($session->course ? $session->course->title : 'Sin curso'));
                $this->line("  Estado: {$session->status}, Fecha: {$session->start_time}");
            }
        }

        // 3. Verificar cursos
        $courses = Course::all();
        $this->info("\n=== CURSOS EN EL SISTEMA ===");
        $this->info("Total de cursos: " . $courses->count());
        
        foreach ($courses as $course) {
            $this->line("\nCurso: {$course->title} (ID: {$course->id})");
            $this->line("  Creado por: " . ($course->creator ? $course->creator->name : 'Desconocido'));
            $this->line("  Estudiantes inscritos: " . $course->students()->count());
        }

        $this->info("\n=== VERIFICACIÓN COMPLETADA ===\n");
        
        return 0;
    }
}
