<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // Crear tabla de roles si no existe
        if (!Schema::hasTable('roles')) {
            Schema::create('roles', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('name');
                $table->string('guard_name');
                $table->string('description')->nullable();
                $table->timestamps();
                $table->unique(['name', 'guard_name']);
            });
        }

        // Crear tabla de permisos si no existe
        if (!Schema::hasTable('permissions')) {
            Schema::create('permissions', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('name');
                $table->string('guard_name');
                $table->string('description')->nullable();
                $table->timestamps();
                $table->unique(['name', 'guard_name']);
            });
        }

        // Crear tabla de relación rol-permiso si no existe
        if (!Schema::hasTable('role_has_permissions')) {
            Schema::create('role_has_permissions', function (Blueprint $table) {
                $table->unsignedBigInteger('permission_id');
                $table->unsignedBigInteger('role_id');

                $table->foreign('permission_id')
                    ->references('id')
                    ->on('permissions')
                    ->onDelete('cascade');

                $table->foreign('role_id')
                    ->references('id')
                    ->on('roles')
                    ->onDelete('cascade');

                $table->primary(['permission_id', 'role_id']);
            });
        }

        // Crear tabla de relación modelo-rol si no existe
        if (!Schema::hasTable('model_has_roles')) {
            Schema::create('model_has_roles', function (Blueprint $table) {
                $table->unsignedBigInteger('role_id');
                $table->string('model_type');
                $table->unsignedBigInteger('model_id');
                $table->index(['model_id', 'model_type']);

                $table->foreign('role_id')
                    ->references('id')
                    ->on('roles')
                    ->onDelete('cascade');

                $table->primary(['role_id', 'model_id', 'model_type']);
            });
        }

        // Crear tabla de relación modelo-permiso si no existe
        if (!Schema::hasTable('model_has_permissions')) {
            Schema::create('model_has_permissions', function (Blueprint $table) {
                $table->unsignedBigInteger('permission_id');
                $table->string('model_type');
                $table->unsignedBigInteger('model_id');
                $table->index(['model_id', 'model_type']);

                $table->foreign('permission_id')
                    ->references('id')
                    ->on('permissions')
                    ->onDelete('cascade');

                $table->primary(['permission_id', 'model_id', 'model_type']);
            });
        }

        // Insertar roles básicos
        $this->insertRolesAndPermissions();
    }

    public function down()
    {
        // Eliminar tablas en orden inverso
        Schema::dropIfExists('model_has_permissions');
        Schema::dropIfExists('model_has_roles');
        Schema::dropIfExists('role_has_permissions');
        Schema::dropIfExists('permissions');
        Schema::dropIfExists('roles');
    }

    private function insertRolesAndPermissions()
    {
        // Roles a crear/verificar
        $rolesData = [
            ['name' => 'Admin', 'description' => 'Administrador del sistema con acceso completo'],
            ['name' => 'Mentor', 'description' => 'Mentor que puede guiar a estudiantes'],
            ['name' => 'Estudiante', 'description' => 'Estudiante que puede inscribirse en cursos'],
            ['name' => 'Guest', 'description' => 'Usuario invitado con acceso limitado'],
        ];

        $roleIds = [];

        foreach ($rolesData as $roleDef) {
            $role = DB::table('roles')
                        ->where('name', $roleDef['name'])
                        ->where('guard_name', 'web')
                        ->first();

            if ($role) {
                $roleIds[$roleDef['name']] = $role->id;
            } else {
                $roleIds[$roleDef['name']] = DB::table('roles')->insertGetId([
                    'name' => $roleDef['name'],
                    'guard_name' => 'web',
                    'description' => $roleDef['description'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
        
        $adminRoleId = $roleIds['Admin'] ?? null;
        $mentorRoleId = $roleIds['Mentor'] ?? null;
        $studentRoleId = $roleIds['Estudiante'] ?? null;
        // $guestRoleId = $roleIds['Guest'] ?? null; // Guest role ID not used later for permissions

        // Insertar permisos básicos
        $permissionsData = [
            // Permisos de administración
            ['name' => 'view admin dashboard', 'description' => 'Ver el panel de administración'],
            ['name' => 'manage users', 'description' => 'Gestionar usuarios'],
            ['name' => 'manage roles', 'description' => 'Gestionar roles'],
            ['name' => 'manage permissions', 'description' => 'Gestionar permisos'],
            ['name' => 'manage settings', 'description' => 'Gestionar configuraciones'],
            
            // Permisos de cursos
            ['name' => 'view courses', 'description' => 'Ver cursos'],
            ['name' => 'create courses', 'description' => 'Crear cursos'],
            ['name' => 'edit courses', 'description' => 'Editar cursos'],
            ['name' => 'delete courses', 'description' => 'Eliminar cursos'],
            ['name' => 'publish courses', 'description' => 'Publicar cursos'],
            
            // Permisos de mentoría
            ['name' => 'mentor students', 'description' => 'Ser mentor de estudiantes'],
            ['name' => 'schedule sessions', 'description' => 'Programar sesiones'],
            ['name' => 'manage content', 'description' => 'Gestionar contenido'],
            
            // Permisos de estudiante
            ['name' => 'enroll courses', 'description' => 'Inscribirse en cursos'],
            ['name' => 'access premium content', 'description' => 'Acceder a contenido premium'],
            ['name' => 'submit assignments', 'description' => 'Enviar tareas'],
        ];

        $permissionIds = []; // This will store 'permission_name' => id
        foreach ($permissionsData as $permDef) {
            $permission = DB::table('permissions')
                                ->where('name', $permDef['name'])
                                ->where('guard_name', 'web')
                                ->first();
            if ($permission) {
                $permissionIds[$permDef['name']] = $permission->id;
            } else {
                $permissionIds[$permDef['name']] = DB::table('permissions')->insertGetId([
                    'name' => $permDef['name'],
                    'guard_name' => 'web',
                    'description' => $permDef['description'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        // Asignar permisos al rol Admin (todos los permisos)
        if ($adminRoleId) {
            foreach ($permissionIds as $permissionName => $permissionId) {
                if (!DB::table('role_has_permissions')
                        ->where('permission_id', $permissionId)
                        ->where('role_id', $adminRoleId)
                        ->exists()) {
                    DB::table('role_has_permissions')->insert([
                        'permission_id' => $permissionId,
                        'role_id' => $adminRoleId
                    ]);
                }
            }
        }

        // Asignar permisos al rol Mentor
        if ($mentorRoleId) {
            $mentorPermissionNames = [
                'view courses',
                'create courses',
                'edit courses',
                'mentor students',
                'schedule sessions',
                'manage content'
            ];

            foreach ($mentorPermissionNames as $permissionName) {
                if (isset($permissionIds[$permissionName])) {
                    if (!DB::table('role_has_permissions')
                            ->where('permission_id', $permissionIds[$permissionName])
                            ->where('role_id', $mentorRoleId)
                            ->exists()) {
                        DB::table('role_has_permissions')->insert([
                            'permission_id' => $permissionIds[$permissionName],
                            'role_id' => $mentorRoleId
                        ]);
                    }
                }
            }
        }

        // Asignar permisos al rol Estudiante
        if ($studentRoleId) {
            $studentPermissionNames = [
                'view courses',
                'enroll courses',
                'access premium content',
                'submit assignments'
            ];

            foreach ($studentPermissionNames as $permissionName) {
                if (isset($permissionIds[$permissionName])) {
                     if (!DB::table('role_has_permissions')
                            ->where('permission_id', $permissionIds[$permissionName])
                            ->where('role_id', $studentRoleId)
                            ->exists()) {
                        DB::table('role_has_permissions')->insert([
                            'permission_id' => $permissionIds[$permissionName],
                            'role_id' => $studentRoleId
                        ]);
                    }
                }
            }
        }
    }
};
