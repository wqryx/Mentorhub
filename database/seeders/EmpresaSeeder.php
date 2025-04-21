<?php

namespace Database\Seeders;

use App\Models\Empresa;
use Illuminate\Database\Seeder;

class EmpresaSeeder extends Seeder
{
    public function run(): void
    {
        Empresa::create([
            'nombre' => 'Empresa A',
            'direccion' => 'Calle Falsa 123',
            'telefono' => '912345678',
        ]);

        Empresa::create([
            'nombre' => 'Empresa B',
            'direccion' => 'Av. Real 456',
            'telefono' => '987654321',
        ]);
    }
}
