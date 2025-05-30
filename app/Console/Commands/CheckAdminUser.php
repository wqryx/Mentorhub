<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class CheckAdminUser extends Command
{
    protected $signature = 'app:check-admin-user';
    protected $description = 'Verificar el usuario administrador y sus roles';

    public function handle()
    {
        $admin = User::where('email', 'admin@mentorhub.com')->first();
        
        if (!$admin) {
            $this->error('Usuario administrador no encontrado');
            return 1;
        }

        $this->info("Usuario: {$admin->name} ({$admin->email})");
        
        // Obtener roles asignados directamente
        $directRoles = DB::table('model_has_roles')
            ->where('model_id', $admin->id)
            ->join('roles', 'model_has_roles.role_id', '=', 'roles.id')
            ->select('roles.name')
            ->get();
            
        $this->info('Roles asignados directamente:');
        foreach ($directRoles as $role) {
            $this->line("- {$role->name}");
        }
        
        // Verificar roles usando Spatie
        $this->info('\nVerificación con Spatie:');
        $this->line("Tiene rol 'Admin': " . ($admin->hasRole('Admin') ? 'Sí' : 'No'));
        $this->line("Tiene rol 'admin': " . ($admin->hasRole('admin') ? 'Sí' : 'No'));
        $this->line("Tiene rol 'ADMIN': " . ($admin->hasRole('ADMIN') ? 'Sí' : 'No'));
        
        // Mostrar todos los roles disponibles
        $allRoles = DB::table('roles')->get();
        $this->info('\nTodos los roles en la base de datos:');
        foreach ($allRoles as $role) {
            $this->line("- {$role->name} (ID: {$role->id})");
        }
        
        return 0;
    }
}
