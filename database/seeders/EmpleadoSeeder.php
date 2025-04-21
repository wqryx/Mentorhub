<?php

namespace Database\Seeders;

use App\Models\Empleado;
use Illuminate\Database\Seeder;

class EmpleadoSeeder extends Seeder
{
    public function run(): void
    {
        Empleado::create([
            'nombre' => 'Pierre',
            'email' => 'pierre@gmail.com',
            'empresa_id' => 1
        ]);

        Empleado::create([
            'nombre' => 'Elisa',
            'email' => 'elisa@gmail.com',
            'empresa_id' => 2
        ]);
    }
}
