<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            // Deshabilitar temporalmente seeders problemáticos
            // SpatieRolesSeeder::class,
            // UserSeeder::class,
            // TestUsersSeeder::class,
            // AdminUserSeeder::class,
            
            // Usar nuestros seeders personalizados
            BasicRolesAndPermissionsSeeder::class,
            SettingsSeeder::class,
            CourseSeeder::class,
            ModuleSeeder::class,
            EnrollmentSeeder::class,
            ProfileSeeder::class, // Asegurarse de que esto venga después de UserSeeder si se usa
        ]);
    }
}
