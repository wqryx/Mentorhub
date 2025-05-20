<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::firstOrCreate(['name' => 'Admin', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'Mentor', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'Estudiante', 'guard_name' => 'web']);
    }
}
