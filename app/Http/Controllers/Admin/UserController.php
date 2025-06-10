<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\UsersExport;
use App\Notifications\MentorApprovalRequest;
use App\Notifications\MentorRejected;
use Illuminate\Support\Facades\Notification;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    /**
     * Display a listing of the resource with search and filters.
     */
    public function index(Request $request)
    {
        $query = User::with('roles')
            ->withCount('roles')
            ->latest();

        // Búsqueda por nombre o email
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Filtro por rol
        if ($request->has('role') && !empty($request->role)) {
            $query->whereHas('roles', function($q) use ($request) {
                $q->where('name', $request->role);
            });
        }

        // Filtro por estado
        if ($request->has('status') && $request->status !== '') {
            $query->where('is_active', $request->status);
        }

        $users = $query->paginate(15);
        $roles = Role::all();
        
        return view('admin.users.index', compact('users', 'roles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = Role::all();
        return view('admin.users.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'roles' => 'required|array',
            'roles.*' => 'exists:roles,id',
            'profile_photo' => 'nullable|image|max:2048',
            'is_active' => 'boolean'
        ]);

        $userData = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'is_active' => $request->has('is_active') ? 1 : 0,
            'email_verified_at' => now()
        ];

        // Subir foto de perfil si se proporciona
        if ($request->hasFile('profile_photo')) {
            $path = $request->file('profile_photo')->store('profile-photos', 'public');
            $userData['profile_photo_path'] = $path;
        }

        $user = User::create($userData);
        $user->roles()->sync($validated['roles']);

        return redirect()->route('admin.users.index')
            ->with('success', 'Usuario creado exitosamente');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = User::with('roles')->findOrFail($id);
        return view('admin.users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user = User::with('roles')->findOrFail($id);
        $roles = Role::all();
        return view('admin.users.edit', compact('user', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($user->id)
            ],
            'password' => 'nullable|string|min:8|confirmed',
            'roles' => 'required|array',
            'roles.*' => 'exists:roles,id',
            'profile_photo' => 'nullable|image|max:2048',
            'is_active' => 'boolean'
        ]);

        $userData = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'is_active' => $request->has('is_active') ? 1 : 0
        ];

        // Actualizar contraseña si se proporciona
        if (!empty($validated['password'])) {
            $userData['password'] = Hash::make($validated['password']);
        }

        // Actualizar foto de perfil si se proporciona
        if ($request->hasFile('profile_photo')) {
            // Eliminar la foto anterior si existe
            if ($user->profile_photo_path) {
                Storage::disk('public')->delete($user->profile_photo_path);
            }
            $path = $request->file('profile_photo')->store('profile-photos', 'public');
            $userData['profile_photo_path'] = $path;
        }

        $user->update($userData);
        $user->roles()->sync($validated['roles']);

        return redirect()->route('admin.users.index')
            ->with('success', 'Usuario actualizado exitosamente');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::findOrFail($id);
        
        // Evitar que el usuario se elimine a sí mismo
        if ($user->id === auth()->id()) {
            return redirect()->back()
                ->with('error', 'No puedes eliminar tu propio usuario');
        }

        // Eliminar la foto de perfil si existe
        if ($user->profile_photo_path) {
            Storage::disk('public')->delete($user->profile_photo_path);
        }

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'Usuario eliminado exitosamente');
    }
    
    /**
     * Toggle user active status
     */
    public function toggleStatus($id)
    {
        $user = User::findOrFail($id);
        $user->update(['is_active' => !$user->is_active]);
        
        $status = $user->is_active ? 'activada' : 'desactivada';
        return redirect()->back()
            ->with('success', "La cuenta ha sido {$status} correctamente");
    }
    
    /**
     * Approve a mentor registration
     */
    public function approveMentor($id)
    {
        $user = User::findOrFail($id);
        
        // Verificar que el usuario sea un mentor pendiente
        if (!$user->hasRole('mentor')) {
            return redirect()->back()
                ->with('error', 'Este usuario no es un mentor pendiente de aprobación');
        }
        
        // Activar la cuenta del mentor
        $user->update(['is_active' => true]);
        
        // Notificar al usuario que su cuenta ha sido aprobada
        $user->notify(new \App\Notifications\AccountApproved('mentor'));
        
        // Marcar la notificación como leída para todos los administradores
        $admins = User::role('admin')->get();
        foreach ($admins as $admin) {
            $admin->unreadNotifications()
                ->where('type', 'App\Notifications\MentorApprovalRequest')
                ->where('data->user_id', $user->id)
                ->update(['read_at' => now()]);
        }
        
        return redirect()->route('admin.users.show', $user->id)
            ->with('success', 'La solicitud de mentor ha sido aprobada correctamente');
    }
    
    /**
     * Reject a mentor registration
     */
    public function rejectMentor(Request $request, $id)
    {
        $request->validate([
            'rejection_reason' => 'required|string|max:1000',
        ]);
        
        $user = User::findOrFail($id);
        
        // Verificar que el usuario sea un mentor pendiente
        if (!$user->hasRole('mentor')) {
            return redirect()->back()
                ->with('error', 'Este usuario no es un mentor pendiente de aprobación');
        }
        
        // Enviar notificación de rechazo al usuario
        $user->notify(new \App\Notifications\MentorRejected($request->rejection_reason));
        
        // Marcar la notificación como leída para todos los administradores
        $admins = User::role('admin')->get();
        foreach ($admins as $admin) {
            $admin->unreadNotifications()
                ->where('type', 'App\Notifications\MentorApprovalRequest')
                ->where('data->user_id', $user->id)
                ->update(['read_at' => now()]);
        }
        
        // Eliminar al usuario (o marcar como rechazado, según prefieras)
        // $user->delete();
        
        return redirect()->route('admin.users.index')
            ->with('success', 'La solicitud de mentor ha sido rechazada y el usuario ha sido notificado');
    }
    
    /**
     * Show pending mentor approvals
     */
    public function pendingApprovals()
    {
        $pendingMentors = User::role('mentor')
            ->where('is_active', false)
            ->latest()
            ->paginate(15);
            
        return view('admin.users.pending-approvals', compact('pendingMentors'));
    }
    
    /**
     * Export users to CSV
     *
     * @param string $format The format to export to (currently only csv is supported)
     * @return \Illuminate\Http\Response
     */
    public function export($format = 'csv')
    {
        $export = new UsersExport();
        return $export->toCsv();
    }
}
