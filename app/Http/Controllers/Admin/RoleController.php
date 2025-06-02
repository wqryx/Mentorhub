<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Validation\Rule;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Role::with('permissions');
        
        // Búsqueda por nombre o descripción
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }
        
        $roles = $query->paginate(15);
        return view('admin.roles.index', compact('roles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $permissions = Permission::all()->groupBy('group');
        return view('admin.roles.create', compact('permissions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:50', 'unique:roles', 'regex:/^[a-zA-Z0-9_-]+$/'],
            'display_name' => 'required|string|max:100',
            'description' => 'nullable|string|max:255',
            'permissions' => 'required|array',
            'permissions.*' => 'exists:permissions,id'
        ]); 

        // Crear el rol
        $role = Role::create([
            'name' => Str::slug($validated['name']),
            'display_name' => $validated['display_name'],
            'description' => $validated['description'],
            'guard_name' => 'web'
        ]);

        // Sincronizar permisos
        $role->syncPermissions($validated['permissions']);

        return redirect()
            ->route('admin.roles.index')
            ->with('success', 'Rol creado exitosamente');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $role = Role::findOrFail($id);
        return view('admin.roles.show', compact('role'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $role = Role::with('permissions')->findOrFail($id);
        $permissions = Permission::all()->groupBy('group');
        $rolePermissions = $role->permissions->pluck('id')->toArray();
        
        return view('admin.roles.edit', compact('role', 'permissions', 'rolePermissions'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $role = Role::findOrFail($id);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:50', Rule::unique('roles')->ignore($role->id), 'regex:/^[a-zA-Z0-9_-]+$/'],
            'display_name' => 'required|string|max:100',
            'description' => 'nullable|string|max:255',
            'permissions' => 'required|array',
            'permissions.*' => 'exists:permissions,id'
        ]);

        // Actualizar el rol
        $role->update([
            'name' => Str::slug($validated['name']),
            'display_name' => $validated['display_name'],
            'description' => $validated['description']
        ]);

        // Sincronizar permisos
        $role->syncPermissions($validated['permissions']);

        return redirect()
            ->route('admin.roles.index')
            ->with('success', 'Rol actualizado exitosamente');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $role = Role::findOrFail($id);
        
        // Evitar eliminar roles del sistema
        if ($role->is_system) {
            return redirect()->back()
                ->with('error', 'No se puede eliminar un rol del sistema');
        }
        
        // Verificar si el rol está siendo usado
        if ($role->users()->count() > 0) {
            return redirect()->back()
                ->with('error', 'No se puede eliminar el rol porque está asignado a usuarios');
        }
        
        $role->delete();

        return redirect()
            ->route('admin.roles.index')
            ->with('success', 'Rol eliminado exitosamente');
    }

    /**
     * Muestra la lista de permisos disponibles
     */
    public function permissions(Request $request)
    {
        $permissions = Permission::with('roles')
            ->orderBy('group')
            ->orderBy('name')
            ->get()
            ->groupBy('group');
            
        $roles = Role::orderBy('name')->get();
        
        // Obtener el ID del rol a resaltar si se proporciona
        $highlightRoleId = $request->query('highlight');
        
        return view('admin.roles.permissions', compact('permissions', 'roles', 'highlightRoleId'));
    }
    
    /**
     * Genera permisos automáticamente basados en las rutas de la aplicación
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function generatePermissions()
    {
        try {
            DB::beginTransaction();
            
            // Obtener todas las rutas de la aplicación
            $routes = \Illuminate\Support\Facades\Route::getRoutes();
            $permissions = [];
            $createdCount = 0;
            $existingCount = 0;
            
            foreach ($routes as $route) {
                // Solo considerar rutas que tengan nombre y no sean de la consola
                if ($route->getName() && !$route->isFallback) {
                    $name = $route->getName();
                    $action = $route->getAction();
                    $prefix = $route->getPrefix();
                    
                    // Determinar el grupo basado en el prefijo o el namespace
                    $group = 'general';
                    if (strpos($name, 'admin.') === 0) {
                        $group = 'admin';
                    } elseif (strpos($name, 'student.') === 0) {
                        $group = 'student';
                    } elseif (strpos($name, 'mentor.') === 0) {
                        $group = 'mentor';
                    } elseif ($prefix) {
                        $group = trim($prefix, '/');
                    }
                    
                    // Crear el permiso si no existe
                    $permission = Permission::firstOrCreate(
                        ['name' => $name],
                        [
                            'display_name' => $this->generateDisplayName($name),
                            'group' => $group,
                            'description' => $action['uses'] ?? null,
                            'guard_name' => 'web'
                        ]
                    );
                    
                    if ($permission->wasRecentlyCreated) {
                        $createdCount++;
                    } else {
                        $existingCount++;
                    }
                    
                    $permissions[] = [
                        'name' => $permission->name,
                        'display_name' => $permission->display_name,
                        'group' => $permission->group,
                        'is_new' => $permission->wasRecentlyCreated
                    ];
                }
            }
            
            // Asignar todos los permisos al rol admin
            $adminRole = Role::where('name', 'admin')->first();
            if ($adminRole) {
                $adminRole->syncPermissions(Permission::all());
            }
            
            // Limpiar caché de permisos
            Artisan::call('cache:forget', ['key' => 'spatie.permission.cache']);
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => "Se generaron {$createdCount} nuevos permisos. {$existingCount} permisos ya existían.",
                'permissions' => $permissions
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error al generar permisos: ' . $e->getMessage(), [
                'exception' => $e,
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Error al generar permisos: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Genera un nombre para mostrar a partir del nombre del permiso
     * 
     * @param string $permissionName
     * @return string
     */
    protected function generateDisplayName($permissionName)
    {
        // Reemplazar puntos y guiones bajos por espacios
        $displayName = str_replace(['.', '_'], ' ', $permissionName);
        
        // Convertir a título
        $displayName = ucwords($displayName);
        
        // Reemplazar palabras comunes
        $replacements = [
            'Index' => 'Listar',
            'Create' => 'Crear',
            'Edit' => 'Editar',
            'Update' => 'Actualizar',
            'Destroy' => 'Eliminar',
            'Show' => 'Ver detalle',
            'Store' => 'Guardar',
            'Admin' => 'Administración',
            'Api' => 'API',
            'Id' => 'ID'
        ];
        
        return str_replace(
            array_keys($replacements), 
            array_values($replacements), 
            $displayName
        );
    }
    
    /**
     * Sincroniza los permisos con los roles
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function syncPermissions(Request $request)
    {
        try {
            $request->validate([
                'permissions' => 'required|array',
                'permissions.*.id' => 'required|exists:permissions,id',
                'permissions.*.roles' => 'array',
                'permissions.*.roles.*' => 'exists:roles,id',
            ]);
            
            // Obtener todos los roles para verificar los roles del sistema
            $systemRoles = Role::where('is_system', true)->pluck('id')->toArray();
            $allRoles = Role::pluck('id')->toArray();
            
            DB::beginTransaction();
            
            // Primero, eliminar todos los permisos de los roles no del sistema
            DB::table('role_has_permissions')
                ->whereNotIn('role_id', $systemRoles)
                ->delete();
            
            // Procesar los permisos recibidos
            $processedPermissions = [];
            
            foreach ($request->permissions as $permissionData) {
                $permissionId = $permissionData['id'];
                $roleIds = $permissionData['roles'] ?? [];
                
                // Verificar que el permiso exista
                $permission = Permission::find($permissionId);
                if (!$permission) {
                    throw new \Exception("El permiso con ID {$permissionId} no existe");
                }
                
                // Filtrar roles válidos
                $validRoleIds = array_intersect($roleIds, $allRoles);
                
                // Si hay roles válidos, sincronizar
                if (count($validRoleIds) > 0) {
                    $permission->syncRoles($validRoleIds);
                }
                
                $processedPermissions[$permissionId] = $validRoleIds;
            }
            
            // Asegurarse de que los roles del sistema mantengan sus permisos
            foreach ($systemRoles as $roleId) {
                $role = Role::find($roleId);
                if ($role->name === 'admin') {
                    // El rol admin siempre tiene todos los permisos
                    $role->syncPermissions(Permission::all());
                }
            }
            
            // Limpiar caché de permisos
            Artisan::call('cache:forget', ['key' => 'spatie.permission.cache']);
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Permisos actualizados correctamente',
                'processed' => $processedPermissions
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error al sincronizar permisos: ' . $e->getMessage(), [
                'exception' => $e,
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar los permisos: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
        DB::table('permissions')->insertOrIgnore($permissions);
        
        // Limpiar caché de permisos
        Artisan::call('cache:forget', ['key' => 'spatie.permission.cache']);
        
        return redirect()
            ->route('admin.permissions.index')
            ->with('success', 'Permisos generados correctamente');
    }
    
    /**
     * Mapea un método HTTP a una acción CRUD
     */
    protected function mapMethodToAction($httpMethod, $methodName)
    {
        $httpMethod = strtoupper($httpMethod);
        
        // Mapear según el nombre del método
        if (str_contains($methodName, ['index', 'show', 'create', 'edit', 'update', 'store', 'destroy'])) {
            return strtolower($methodName);
        }
        
        // Mapear según el verbo HTTP
        switch ($httpMethod) {
            case 'GET':
                return 'view';
            case 'POST':
                return 'create';
            case 'PUT':
            case 'PATCH':
                return 'update';
            case 'DELETE':
                return 'delete';
            default:
                return null;
        }
    }
    
    /**
     * Formatea el nombre para mostrar del permiso
     */
    protected function formatDisplayName($controller, $action)
    {
        $controller = str_replace('Controller', '', $controller);
        $controller = strtolower(preg_replace('/(?<!^)[A-Z]/', ' $0', $controller));
        
        $actions = [
            'index' => 'Ver lista',
            'show' => 'Ver detalle',
            'create' => 'Crear',
            'store' => 'Guardar',
            'edit' => 'Editar',
            'update' => 'Actualizar',
            'destroy' => 'Eliminar',
            'view' => 'Ver',
            'delete' => 'Eliminar',
        ];
        
        $actionName = $actions[$action] ?? ucfirst($action);
        
        return $actionName . ' ' . $controller;
    }
    
    /**
     * Obtiene el grupo al que pertenece un permiso basado en el controlador
     */
    protected function getPermissionGroup($controller)
    {
        $controller = strtolower($controller);
        
        $groups = [
            'user' => 'Usuarios',
            'role' => 'Roles',
            'permission' => 'Permisos',
            'course' => 'Cursos',
            'module' => 'Módulos',
            'lesson' => 'Lecciones',
            'enrollment' => 'Inscripciones',
            'category' => 'Categorías',
            'setting' => 'Configuración',
        ];
        
        foreach ($groups as $key => $group) {
            if (str_contains($controller, $key)) {
                return $group;
            }
        }
        
        return 'General';
    }
}
