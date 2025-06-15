<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Spatie\Permission\Models\Role;

class CheckUserRoles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:check-user-roles';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Verifica los roles de los usuarios en el sistema';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $users = User::with('roles')->get();
        
        $this->info("Lista de usuarios y sus roles:");
        $this->info("================================");
        
        $headers = ['ID', 'Nombre', 'Email', 'Roles'];
        $rows = [];
        
        foreach ($users as $user) {
            $rows[] = [
                $user->id,
                $user->name,
                $user->email,
                $user->getRoleNames()->implode(', ')
            ];
        }
        
        $this->table($headers, $rows);
        
        return Command::SUCCESS;
    }
}
