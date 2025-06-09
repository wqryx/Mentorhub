<?php

namespace Database\Seeders;

use App\Models\Speciality;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class SpecialitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $specialities = [
            [
                'name' => 'Desarrollo Web',
                'description' => 'Especialización en desarrollo de aplicaciones y sitios web modernos.',
                'is_active' => true,
            ],
            [
                'name' => 'Diseño UX/UI',
                'description' => 'Diseño de experiencias e interfaces de usuario centradas en el usuario.',
                'is_active' => true,
            ],
            [
                'name' => 'Marketing Digital',
                'description' => 'Estrategias de marketing en línea para promocionar productos y servicios.',
                'is_active' => true,
            ],
            [
                'name' => 'Ciencia de Datos',
                'description' => 'Análisis y extracción de conocimiento a partir de conjuntos de datos.',
                'is_active' => true,
            ],
            [
                'name' => 'Inteligencia Artificial',
                'description' => 'Desarrollo de sistemas inteligentes que pueden realizar tareas que requieren inteligencia humana.',
                'is_active' => true,
            ],
            [
                'name' => 'Desarrollo Móvil',
                'description' => 'Creación de aplicaciones para dispositivos móviles iOS y Android.',
                'is_active' => true,
            ],
            [
                'name' => 'Ciberseguridad',
                'description' => 'Protección de sistemas informáticos contra robos o daños en el hardware, software y datos.',
                'is_active' => true,
            ],
        ];

        foreach ($specialities as $speciality) {
            Speciality::create([
                'name' => $speciality['name'],
                'slug' => Str::slug($speciality['name']),
                'description' => $speciality['description'],
                'is_active' => $speciality['is_active'],
            ]);
        }
    }
}
