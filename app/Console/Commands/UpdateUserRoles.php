<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class UpdateUserRoles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:update-roles';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update user roles in the database';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Check if the role column exists, if not, add it
        if (!Schema::hasColumn('users', 'role')) {
            $this->info("Adding 'role' column to users table...");
            DB::statement('ALTER TABLE users ADD COLUMN role VARCHAR(255) DEFAULT "student" AFTER email');
            $this->info("Column 'role' added successfully.");
        }

        // Count users
        $count = DB::table('users')->count();
        $this->info("\nTotal users in the database: " . $count);

        // Show first 5 users with their current roles
        $users = DB::table('users')->select('id', 'name', 'email', 'role')->take(5)->get();
        
        $this->info("\nFirst 5 users with their current roles:");
        $this->table(
            ['ID', 'Name', 'Email', 'Current Role'],
            $users->map(function ($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'role' => $user->role ?? 'Not set'
                ];
            })
        );

        // Ask if user wants to update any roles
        if ($this->confirm('Do you want to update any user roles?', true)) {
            $email = $this->ask('Enter the email of the user to update:');
            $user = DB::table('users')->where('email', $email)->first();

            if (!$user) {
                $this->error("User with email {$email} not found!");
                return 1;
            }

            $role = $this->choice(
                'Select the new role for ' . $user->name,
                ['admin', 'mentor', 'student'],
                $user->role ?? 'student'
            );

            DB::table('users')
                ->where('email', $email)
                ->update(['role' => $role]);

            $this->info("\nUser role updated successfully!");
            $this->info("Name: " . $user->name);
            $this->info("Email: " . $user->email);
            $this->info("New Role: " . $role);
        }

        return 0;
    }
}
