<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Profile;
use Illuminate\Database\Seeder;

class ProfileSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obtener todos los usuarios que no tengan perfil
        $users = User::whereDoesntHave('profile')->get();
        
        foreach ($users as $user) {
            // Crear un perfil para cada usuario sin perfil
            Profile::create([
                'user_id' => $user->id,
                'bio' => 'Este es el perfil de ' . $user->name,
                'phone' => '123456789',
                'date_of_birth' => now()->subYears(rand(18, 60)),
                'gender' => ['male', 'female', 'other'][rand(0, 2)],
                'country' => 'España',
                'city' => 'Madrid',
                'timezone' => 'Europe/Madrid',
                'preferred_language' => 'es',
                'social_links' => json_encode([
                    'twitter' => 'https://twitter.com/' . str_replace(' ', '', $user->name),
                    'linkedin' => 'https://linkedin.com/in/' . str_replace(' ', '', $user->name),
                    'github' => 'https://github.com/' . str_replace(' ', '', $user->name),
                ]),
                'education' => 'Educación de ' . $user->name,
                'experience' => 'Experiencia de ' . $user->name,
                'skills' => 'Habilidades de ' . $user->name,
                'interests' => 'Intereses de ' . $user->name,
            ]);
        }
        
        $this->command->info('Perfiles creados exitosamente.');
    }
}
