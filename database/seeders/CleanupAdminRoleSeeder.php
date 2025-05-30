<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\DB;

class CleanupAdminRoleSeeder extends Seeder
{
    public function run(): void
    {
        // Find the admin user
        $admin = User::where('email', 'admin@mentorhub.com')->first();
        
        if ($admin) {
            // Find the admin role (case insensitive)
            $adminRole = Role::where('name', 'Admin')
                ->orWhere('name', 'admin')
                ->first();
            
            if ($adminRole) {
                // Remove all role assignments for this user
                DB::table('model_has_roles')->where('model_id', $admin->id)->delete();
                
                // Add the correct role
                $admin->assignRole($adminRole);
                
                echo "Admin role has been cleaned up and reassigned.\n";
                
                // Also fix the role name to be 'Admin' with capital A
                if ($adminRole->name !== 'Admin') {
                    $adminRole->name = 'Admin';
                    $adminRole->save();
                    echo "Role name has been updated to 'Admin'.\n";
                }
            } else {
                echo "No admin role found.\n";
            }
        } else {
            echo "Admin user not found.\n";
        }
    }
}
