<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\User;
use Illuminate\Database\Seeder;

class CourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Buscar un profesor para asignar a los cursos
        $teacher = User::where('email', 'like', '%@mentorhub.com')->first() ?? User::first();
        
        // Crear cursos de ejemplo
        $courses = [
            [
                'name' => 'Introducción a la Programación',
                'description' => 'Curso básico para aprender los fundamentos de la programación.',
                'code' => 'PROG101',
                'credits' => 3,
                'hours_per_week' => 4,
                'level' => 'Principiante',
                'is_active' => true,
                'start_date' => now(),
                'end_date' => now()->addMonths(3),
                'classroom' => 'Aula Virtual 1',
                'schedule' => 'Lunes y Miércoles 18:00-20:00',
                'teacher_id' => $teacher?->id
            ],
            [
                'name' => 'Desarrollo Web Frontend',
                'description' => 'Aprende a crear interfaces web modernas con HTML, CSS y JavaScript.',
                'code' => 'WEB201',
                'credits' => 4,
                'hours_per_week' => 6,
                'level' => 'Intermedio',
                'is_active' => true,
                'start_date' => now()->addDays(15),
                'end_date' => now()->addMonths(4),
                'classroom' => 'Aula Virtual 2',
                'schedule' => 'Martes y Jueves 17:00-20:00',
                'teacher_id' => $teacher?->id
            ],
            [
                'name' => 'Desarrollo Backend con Laravel',
                'description' => 'Domina el desarrollo de aplicaciones web con Laravel, el framework PHP más popular.',
                'code' => 'WEB301',
                'credits' => 5,
                'hours_per_week' => 8,
                'level' => 'Avanzado',
                'is_active' => true,
                'start_date' => now()->addMonth(),
                'end_date' => now()->addMonths(5),
                'classroom' => 'Aula Virtual 3',
                'schedule' => 'Viernes 15:00-19:00 y Sábados 9:00-13:00',
                'teacher_id' => $teacher?->id
            ],
        ];
        
        foreach ($courses as $courseData) {
            Course::firstOrCreate(
                ['code' => $courseData['code']],
                $courseData
            );
        }
    }
}
