<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class CheckAdminRole extends Command
{
    protected $signature = 'app:check-admin-role';
    protected $description = 'Check the admin user\'s roles';

    public function handle()
    {
        $admin = User::where('email', 'admin@mentorhub.com')->first();
        
        if (!$admin) {
            $this->error('Admin user not found!');
            return 1;
        }

        $this->info("User: {$admin->name} ({$admin->email})");
        
        $roles = $admin->getRoleNames();
        $this->info('Roles: ' . $roles->implode(', '));
        
        // Check role assignments in the database
        $roleAssignments = DB::table('model_has_roles')
            ->where('model_id', $admin->id)
            ->join('roles', 'model_has_roles.role_id', '=', 'roles.id')
            ->select('roles.name')
            ->get();
            
        $this->info('Role assignments in database:');
        foreach ($roleAssignments as $role) {
            $this->line("- {$role->name}");
        }
        
        return 0;
    }
}
