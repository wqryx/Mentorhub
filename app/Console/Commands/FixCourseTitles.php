<?php

namespace App\Console\Commands;

use App\Models\Course;
use App\Models\MentorshipSession;
use Illuminate\Console\Command;

class FixCourseTitles extends Command
{
    protected $signature = 'mentor:fix-course-titles';
    protected $description = 'Corrige los títulos de los cursos y actualiza las sesiones relacionadas';

    public function handle()
    {
        $this->info('=== CORRECCIÓN DE TÍTULOS DE CURSOS ===');
        
        // 1. Verificar y corregir títulos de cursos vacíos
        $emptyTitles = Course::where('name', '')->orWhereNull('name')->get();
        
        if ($emptyTitles->isNotEmpty()) {
            $this->warn('Se encontraron ' . $emptyTitles->count() . ' cursos sin título:');
            
            foreach ($emptyTitles as $course) {
                $newTitle = 'Curso ' . $course->id . ' - ' . ($course->speciality ? $course->speciality->name : 'Sin Especialidad');
                $course->name = $newTitle;
                $course->save();
                $this->line("- ID {$course->id}: Título actualizado a '{$newTitle}'");
            }
            
            $this->info('\n✅ Todos los títulos vacíos han sido actualizados.');
        } else {
            $this->info('No se encontraron cursos con títulos vacíos.');
        }
        
        // 2. Actualizar sesiones con títulos vacíos
        $sessions = MentorshipSession::where(function($query) {
            $query->where('title', '')->orWhereNull('title');
        })->orWhere('title', 'LIKE', 'Sesión % - %')
          ->with(['course', 'mentor', 'student'])
          ->get();
        
        if ($sessions->isNotEmpty()) {
            $this->info('\n=== ACTUALIZANDO TÍTULOS DE SESIONES ===');
            
            foreach ($sessions as $session) {
                $newTitle = $session->course 
                    ? "Sesión con {$session->mentor->name} - {$session->course->name}"
                    : "Sesión con {$session->mentor->name}";
                
                $session->title = $newTitle;
                $session->save();
                $this->line("- Sesión ID {$session->id}: Título actualizado a '{$newTitle}'");
            }
            
            $this->info('✅ Títulos de sesiones actualizados correctamente.');
        } else {
            $this->info('No se encontraron sesiones con títulos vacíos o genéricos.');
        }
        
        // 3. Verificar relaciones
        $this->info('\n=== VERIFICANDO RELACIONES ===');
        
        $sessionsWithoutCourse = MentorshipSession::whereNull('course_id')->count();
        $this->line("Sesiones sin curso asignado: {$sessionsWithoutCourse}");
        
        if ($sessionsWithoutCourse > 0) {
            $this->warn('Hay sesiones sin curso asignado. Se recomienda asignar un curso a estas sesiones.');
        }
        
        $this->info('\n✅ Proceso de verificación completado.');
        
        return 0;
    }
}
