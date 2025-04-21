<?php

namespace Database\Seeders;

use App\Models\Firma;
use Illuminate\Database\Seeder;

class FirmaSeeder extends Seeder
{
    public function run(): void
    {
        Firma::create(['nombre' => 'Firma Seguridad']);
        Firma::create(['nombre' => 'Firma Gestión']);
    }
}
