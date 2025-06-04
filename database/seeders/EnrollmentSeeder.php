<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Course;
use App\Models\Enrollment; // Asegúrate que este es el modelo para tu tabla enrollments
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB; // Para borrado rápido si es necesario

class EnrollmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $studentId = 3; // ID del Estudiante Ejemplo
        $mentorId = 2;  // ID del Mentor

        $student = User::find($studentId);
        $mentor = User::find($mentorId);

        if (!$student) {
            $this->command->error("Estudiante Ejemplo con ID {$studentId} no encontrado. No se crearán inscripciones.");
            return;
        }

        if (!$mentor) {
            $this->command->error("Mentor con ID {$mentorId} no encontrado. No se pueden buscar sus cursos.");
            return;
        }

        // Limpiar inscripciones previas para este estudiante para asegurar un estado limpio
        Enrollment::where('user_id', $studentId)->delete();
        // O DB::table('enrollments')->where('user_id', $studentId)->delete();

        $courses = Course::where('teacher_id', $mentorId)->get();

        if ($courses->isEmpty()) {
            $this->command->warn("El mentor con ID {$mentorId} no tiene cursos asignados. No se crearán inscripciones para el estudiante ID {$studentId}.");
            return;
        }

        $this->command->info("Inscribiendo al Estudiante Ejemplo (ID: {$studentId}) en los cursos del Mentor (ID: {$mentorId})...");

        foreach ($courses as $course) {
            Enrollment::updateOrCreate(
                [
                    'user_id' => $student->id,
                    'course_id' => $course->id,
                ],
                [
                    // Campos adicionales para la inscripción:
                    'enrollment_date' => now(),
                    'status' => 'active', // O el estado por defecto que prefieras, ej: 'enrolled'
                ]
            );
            $this->command->line("- Inscrito en: {$course->name} (ID: {$course->id})");
        }

        $this->command->info('Inscripciones del Estudiante Ejemplo completadas.');
    }
}
