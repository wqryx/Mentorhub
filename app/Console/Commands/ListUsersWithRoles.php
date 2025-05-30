<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class ListUsersWithRoles extends Command
{
    protected $signature = 'users:list-roles';
    protected $description = 'List all users with their roles';

    public function handle()
    {
        $users = User::with('roles')->get();
        
        $this->info('Users and their roles:');
        foreach ($users as $user) {
            $this->line(sprintf(
                '%s (%s): %s',
                $user->name,
                $user->email,
                $user->getRoleNames()->implode(', ')
            ));
        }
        
        return Command::SUCCESS;
    }
}
