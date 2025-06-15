<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Spatie\Permission\Models\Role;

class AssignDefaultRoles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:assign-default-roles';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Asigna los roles por defecto a los usuarios según su correo electrónico';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Asegurarse de que los roles existan
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $mentorRole = Role::firstOrCreate(['name' => 'mentor']);
        $studentRole = Role::firstOrCreate(['name' => 'student']);

        // Asignar rol de administrador
        $admin = User::where('email', 'admin@mentorhub.com')->first();
        if ($admin) {
            $admin->syncRoles(['admin']);
            $this->info("Rol 'admin' asignado a: " . $admin->email);
        }

        // Asignar rol de mentor
        $mentor = User::where('email', 'mentor@mentorhub.com')->first();
        if ($mentor) {
            $mentor->syncRoles(['mentor']);
            $this->info("Rol 'mentor' asignado a: " . $mentor->email);
        }

        // Asignar rol de estudiante (si no lo tiene)
        $student = User::where('email', 'estudiante@mentorhub.com')->first();
        if ($student && !$student->hasRole('student')) {
            $student->assignRole('student');
            $this->info("Rol 'student' asignado a: " . $student->email);
        }

        $this->info("Proceso de asignación de roles completado.");
        
        // Mostrar los roles actualizados
        $this->call('app:check-user-roles');
        
        return Command::SUCCESS;
    }
}
