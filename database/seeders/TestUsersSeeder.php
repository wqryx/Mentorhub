<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use App\Models\User;
use App\Models\Role;

class TestUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Desactivar restricciones de clave foránea
        \DB::statement('SET FOREIGN_KEY_CHECKS=0');
        
        // Limpiar tablas relacionadas
        $tables = [
            'activity_logs',
            'model_has_roles',
            'model_has_permissions',
            'sessions',
            'messages',
            'notifications'
        ];
        
        foreach ($tables as $table) {
            if (\Schema::hasTable($table)) {
                \DB::table($table)->truncate();
            }
        }
        
        // Limpiar usuarios
        User::truncate();
        
        // Reactivar restricciones
        \DB::statement('SET FOREIGN_KEY_CHECKS=1');

        // Crear roles si no existen
        $adminRole = Role::firstOrCreate(
            ['name' => 'admin'],
            ['guard_name' => 'web']
        );

        $mentorRole = Role::firstOrCreate(
            ['name' => 'mentor'],
            ['guard_name' => 'web']
        );

        $studentRole = Role::firstOrCreate(
            ['name' => 'estudiante'],
            ['guard_name' => 'web']
        );
        
        $guestRole = Role::firstOrCreate(
            ['name' => 'invitado'],
            ['guard_name' => 'web']
        );
        
        // Actualizar descripciones si es necesario (en caso de que uses un paquete que las soporte)
        if (Schema::hasColumn('roles', 'description')) {
            $adminRole->description = 'Acceso total al sistema';
            $mentorRole->description = 'Puede gestionar estudiantes y recursos';
            $studentRole->description = 'Acceso al dashboard de estudiante';
            $guestRole->description = 'Acceso limitado a contenido público';
            
            $adminRole->save();
            $mentorRole->save();
            $studentRole->save();
            $guestRole->save();
        }

        // 1. Administrador
        $admin = User::create([
            'name' => 'Administrador Principal',
            'email' => 'admin@mentorhub.com',
            'password' => Hash::make('MentorHub2024!'),
            'phone' => '123456789',
            'address' => 'Oficina Principal',
            'birth_date' => '1990-01-01',
            'emergency_contact' => 'Contacto de Emergencia',
            'emergency_phone' => '987654321'
        ]);
        $admin->assignRole($adminRole);

        // 2. Mentores
        $mentores = [
            [
                'name' => 'Laura Méndez',
                'email' => 'laura.mentor@mentorhub.com',
                'password' => 'Mentor123!',
                'course' => 'Desarrollo Web',
                'phone' => '123456780',
                'address' => 'Oficina de Mentores',
                'birth_date' => '1985-05-15',
                'emergency_contact' => 'Contacto de Emergencia',
                'emergency_phone' => '987654320'
            ],
            [
                'name' => 'Carlos Rojas',
                'email' => 'carlos.mentor@mentorhub.com',
                'password' => 'Mentor123!',
                'course' => 'Base de Datos',
                'phone' => '123456781',
                'address' => 'Oficina de Mentores',
                'birth_date' => '1988-07-20',
                'emergency_contact' => 'Contacto de Emergencia',
                'emergency_phone' => '987654321'
            ]
        ];

        foreach ($mentores as $mentorData) {
            $mentor = User::create([
                'name' => $mentorData['name'],
                'email' => $mentorData['email'],
                'password' => Hash::make($mentorData['password']),
                'course' => $mentorData['course'],
                'phone' => $mentorData['phone'],
                'address' => $mentorData['address'],
                'birth_date' => $mentorData['birth_date'],
                'emergency_contact' => $mentorData['emergency_contact'],
                'emergency_phone' => $mentorData['emergency_phone']
            ]);
            $mentor->assignRole($mentorRole);
        }

        // 3. Estudiantes
        $estudiantes = [
            [
                'name' => 'Ana García',
                'email' => 'ana.estudiante@mentorhub.com',
                'password' => 'Estudiante123!',
                'course' => 'Ingeniería de Software',
                'cycle' => '2025-1',
                'phone' => '123456782',
                'address' => 'Calle Falsa 123',
                'birth_date' => '2000-03-10',
                'emergency_contact' => 'María García',
                'emergency_phone' => '987654322'
            ],
            [
                'name' => 'Pedro López',
                'email' => 'pedro.estudiante@mentorhub.com',
                'password' => 'Estudiante123!',
                'course' => 'Ciencia de Datos',
                'cycle' => '2025-1',
                'phone' => '123456783',
                'address' => 'Avenida Siempre Viva 742',
                'birth_date' => '2001-06-25',
                'emergency_contact' => 'Juan López',
                'emergency_phone' => '987654323'
            ]
        ];

        foreach ($estudiantes as $estudianteData) {
            $estudiante = User::create([
                'name' => $estudianteData['name'],
                'email' => $estudianteData['email'],
                'password' => Hash::make($estudianteData['password']),
                'course' => $estudianteData['course'],
                'cycle' => $estudianteData['cycle'],
                'phone' => $estudianteData['phone'],
                'address' => $estudianteData['address'],
                'birth_date' => $estudianteData['birth_date'],
                'emergency_contact' => $estudianteData['emergency_contact'],
                'emergency_phone' => $estudianteData['emergency_phone']
            ]);
            $estudiante->assignRole($studentRole);
        }

        // 4. Usuario Invitado
        $invitado = User::create([
            'name' => 'Invitado Demo',
            'email' => 'invitado@mentorhub.com',
            'password' => Hash::make('Invitado123!'),
            'phone' => '123456784',
            'address' => 'Sin dirección',
            'birth_date' => '1995-01-01',
            'emergency_contact' => 'Contacto de Emergencia',
            'emergency_phone' => '987654324'
        ]);
        $invitado->assignRole($guestRole);
    }
}
