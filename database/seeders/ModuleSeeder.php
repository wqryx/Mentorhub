<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Module;
use Illuminate\Database\Seeder;

class ModuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obtener los cursos para los que crearemos módulos
        $programmingCourse = Course::where('code', 'PROG101')->first();
        $frontendCourse = Course::where('code', 'WEB201')->first();
        $backendCourse = Course::where('code', 'WEB301')->first();
        
        if ($programmingCourse) {
            $this->createProgrammingModules($programmingCourse->id);
        }
        
        if ($frontendCourse) {
            $this->createFrontendModules($frontendCourse->id);
        }
        
        if ($backendCourse) {
            $this->createBackendModules($backendCourse->id);
        }
    }
    
    /**
     * Crear módulos para el curso de Introducción a la Programación
     */
    private function createProgrammingModules($courseId): void
    {
        $modules = [
            [
                'title' => 'Fundamentos de Programación',
                'description' => 'Conceptos básicos, variables, tipos de datos y operadores.',
                'order' => 1,
                'course_id' => $courseId,
                'status' => 'active',
                'is_free' => true
            ],
            [
                'title' => 'Estructuras de Control',
                'description' => 'Condicionales, bucles y control de flujo.',
                'order' => 2,
                'course_id' => $courseId,
                'status' => 'active',
                'is_free' => true
            ],
            [
                'title' => 'Funciones y Procedimientos',
                'description' => 'Creación y uso de funciones, parámetros y retorno de valores.',
                'order' => 3,
                'course_id' => $courseId,
                'status' => 'active',
                'is_free' => false
            ],
            [
                'title' => 'Estructuras de Datos',
                'description' => 'Arrays, listas, pilas y colas.',
                'order' => 4,
                'course_id' => $courseId,
                'status' => 'active',
                'is_free' => false
            ],
        ];
        
        $this->createModules($modules);
    }
    
    /**
     * Crear módulos para el curso de Desarrollo Web Frontend
     */
    private function createFrontendModules($courseId): void
    {
        $modules = [
            [
                'title' => 'HTML5 Avanzado',
                'description' => 'Estructura semántica, formularios y validación.',
                'order' => 1,
                'course_id' => $courseId,
                'status' => 'active',
                'is_free' => true
            ],
            [
                'title' => 'CSS3 y Diseño Responsivo',
                'description' => 'Estilos avanzados, flexbox, grid y media queries.',
                'order' => 2,
                'course_id' => $courseId,
                'status' => 'active',
                'is_free' => true
            ],
            [
                'title' => 'JavaScript Moderno',
                'description' => 'ES6+, promesas, async/await y manipulación del DOM.',
                'order' => 3,
                'course_id' => $courseId,
                'status' => 'active',
                'is_free' => false
            ],
            [
                'title' => 'Frameworks Frontend',
                'description' => 'Introducción a React, Vue o Angular.',
                'order' => 4,
                'course_id' => $courseId,
                'status' => 'active',
                'is_free' => false
            ],
        ];
        
        $this->createModules($modules);
    }
    
    /**
     * Crear módulos para el curso de Desarrollo Backend con Laravel
     */
    private function createBackendModules($courseId): void
    {
        $modules = [
            [
                'title' => 'Introducción a Laravel',
                'description' => 'Instalación, estructura de directorios y conceptos básicos.',
                'order' => 1,
                'course_id' => $courseId,
                'status' => 'active',
                'is_free' => true
            ],
            [
                'title' => 'Rutas, Controladores y Vistas',
                'description' => 'Sistema MVC en Laravel y Blade templating.',
                'order' => 2,
                'course_id' => $courseId,
                'status' => 'active',
                'is_free' => true
            ],
            [
                'title' => 'Eloquent ORM',
                'description' => 'Modelos, migraciones, relaciones y consultas.',
                'order' => 3,
                'course_id' => $courseId,
                'status' => 'active',
                'is_free' => false
            ],
            [
                'title' => 'API RESTful con Laravel',
                'description' => 'Creación de APIs, autenticación y recursos.',
                'order' => 4,
                'course_id' => $courseId,
                'status' => 'active',
                'is_free' => false
            ],
            [
                'title' => 'Despliegue y Optimización',
                'description' => 'Puesta en producción, caché y seguridad.',
                'order' => 5,
                'course_id' => $courseId,
                'status' => 'active',
                'is_free' => false
            ],
        ];
        
        $this->createModules($modules);
    }
    
    /**
     * Método auxiliar para crear módulos
     */
    private function createModules(array $modules): void
    {
        foreach ($modules as $moduleData) {
            Module::firstOrCreate(
                [
                    'title' => $moduleData['title'],
                    'course_id' => $moduleData['course_id']
                ],
                $moduleData
            );
        }
    }
}
