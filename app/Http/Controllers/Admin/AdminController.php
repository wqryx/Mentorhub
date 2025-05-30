<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Event;
use App\Models\User;
use Spatie\Permission\Models\Role;
use App\Models\Activity;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AdminController extends Controller
{
    
    /**
     * Mostrar el dashboard principal del administrador
     */
    public function dashboard()
    {
        $user = auth()->user();
        
        // Estadísticas para las tarjetas
        $stats = [
            'total_users' => User::count(),
            'active_students' => User::whereHas('roles', function($q) {
                $q->where('name', 'Estudiante');
            })->count(),
            'mentors_count' => User::whereHas('roles', function($q) {
                $q->where('name', 'Mentor');
            })->count(),
            'active_courses' => Course::count(),
            'users_growth' => $this->calculateGrowthPercentage('users'),
            'students_growth' => $this->calculateGrowthPercentage('students'),
            'courses_growth' => $this->calculateGrowthPercentage('courses'),
        ];
        
        // Usuarios recientes
        $recent_users = User::with('roles')
            ->latest()
            ->take(5)
            ->get();
        
        // Próximos eventos
        $upcoming_events = Event::where('start_date', '>=', now())
            ->orderBy('start_date')
            ->take(5)
            ->get();
            
        // Mensajes recientes
        $recent_messages = []; // Añadir lógica para obtener mensajes si es necesario
        
        // Actividad reciente
        $recent_activities = []; // Añadir lógica para obtener actividad si es necesario
        
        // Obtener información de sesiones
        $activeSessions = 1; // Como solo estamos en una sesión, mostramos 1
        
        // Pasar el usuario y las sesiones a la vista
        return view('admin.dashboard', compact('user', 'activeSessions'));
    }
    
    /**
     * Calcular el porcentaje de crecimiento para distintas entidades
     */
    private function calculateGrowthPercentage($entity)
    {
        $now = now();
        $lastMonth = $now->copy()->subMonth();
        
        // Inicio y fin del mes actual
        $currentMonthStart = $now->copy()->startOfMonth();
        $currentMonthEnd = $now->copy()->endOfMonth();
        
        // Inicio y fin del mes anterior
        $lastMonthStart = $lastMonth->copy()->startOfMonth();
        $lastMonthEnd = $lastMonth->copy()->endOfMonth();
        
        switch($entity) {
            case 'users':
                $currentCount = User::whereBetween('created_at', [$currentMonthStart, $currentMonthEnd])->count();
                $lastCount = User::whereBetween('created_at', [$lastMonthStart, $lastMonthEnd])->count();
                break;
            case 'students':
                $currentCount = User::whereHas('roles', function($q) {
                    $q->where('name', 'student');
                })->whereBetween('created_at', [$currentMonthStart, $currentMonthEnd])->count();
                
                $lastCount = User::whereHas('roles', function($q) {
                    $q->where('name', 'student');
                })->whereBetween('created_at', [$lastMonthStart, $lastMonthEnd])->count();
                break;
            case 'courses':
                $currentCount = Course::whereBetween('created_at', [$currentMonthStart, $currentMonthEnd])->count();
                $lastCount = Course::whereBetween('created_at', [$lastMonthStart, $lastMonthEnd])->count();
                break;
            default:
                return 0;
        }
        
        if ($lastCount == 0) {
            return $currentCount > 0 ? 100 : 0;
        }
        
        return round((($currentCount - $lastCount) / $lastCount) * 100);
    }
    
    /**
     * Mostrar la lista de usuarios
     */
    public function users()
    {
        $users = User::with('roles')->paginate(10);
        return view('admin.users.index', compact('users'));
    }
    
    /**
     * Mostrar el formulario para crear un usuario
     */
    public function createUser()
    {
        $roles = Role::all();
        return view('admin.users.create', compact('roles'));
    }
    
    /**
     * Guardar un nuevo usuario
     */
    public function storeUser(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role_id' => 'required|exists:roles,id',
        ]);
        
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->save();
        
        // Asignar rol
        $user->roles()->attach($request->role_id);
        
        // Registrar actividad
        Activity::create([
            'type' => 'create',
            'user_id' => Auth::id(),
            'description' => 'Creó el usuario <strong>' . $user->name . '</strong>'
        ]);
        
        return redirect()->route('admin.users.index')
            ->with('success', 'Usuario creado correctamente');
    }
    
    /**
     * Mostrar el formulario para editar un usuario
     */
    public function editUser($id)
    {
        $user = User::with('roles')->findOrFail($id);
        $roles = Role::all();
        return view('admin.users.edit', compact('user', 'roles'));
    }
    
    /**
     * Actualizar un usuario
     */
    public function updateUser(Request $request, $id)
    {
        $user = User::findOrFail($id);
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'role_id' => 'required|exists:roles,id',
        ]);
        
        $user->name = $request->name;
        $user->email = $request->email;
        
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }
        
        $user->save();
        
        // Actualizar rol
        $user->roles()->sync([$request->role_id]);
        
        // Registrar actividad
        Activity::create([
            'type' => 'update',
            'user_id' => Auth::id(),
            'description' => 'Actualizó el usuario <strong>' . $user->name . '</strong>'
        ]);
        
        return redirect()->route('admin.users.index')
            ->with('success', 'Usuario actualizado correctamente');
    }
    
    /**
     * Eliminar un usuario
     */
    public function destroyUser($id)
    {
        $user = User::findOrFail($id);
        $userName = $user->name;
        
        $user->delete();
        
        // Registrar actividad
        Activity::create([
            'type' => 'delete',
            'user_id' => Auth::id(),
            'description' => 'Eliminó el usuario <strong>' . $userName . '</strong>'
        ]);
        
        return redirect()->route('admin.users.index')
            ->with('success', 'Usuario eliminado correctamente');
    }
    
    /**
     * Mostrar la lista de cursos
     */
    public function courses()
    {
        $courses = Course::with('teacher')->paginate(10);
        return view('admin.courses.index', compact('courses'));
    }
    
    /**
     * Mostrar la lista de eventos
     */
    public function events()
    {
        $events = Event::with('creator')->paginate(10);
        return view('admin.events.index', compact('events'));
    }
    
    /**
     * Mostrar la lista de notificaciones
     */
    public function notifications()
    {
        $notifications = Auth::user()->notifications()->paginate(10);
        return view('admin.notifications.index', compact('notifications'));
    }
    
    /**
     * Mostrar la lista de mensajes
     */
    public function messages()
    {
        $messages = Message::with(['sender', 'receiver'])->latest()->paginate(10);
        return view('admin.messages.index', compact('messages'));
    }
    
    /**
     * Mostrar la configuración del sistema
     */
    public function settings()
    {
        return view('admin.settings.index');
    }
    
    /**
     * Actualizar la configuración del sistema
     */
    public function updateSettings(Request $request)
    {
        $request->validate([
            'app_name' => 'required|string|max:255',
            'app_description' => 'nullable|string',
            'contact_email' => 'required|email',
            'logo' => 'nullable|image|max:1024',
            'favicon' => 'nullable|image|max:1024',
        ]);
        
        // Actualizar configuraciones
        // Aquí se podría utilizar la tabla settings o un archivo .env
        
        return redirect()->route('admin.settings.index')
            ->with('success', 'Configuración actualizada correctamente');
    }
}
