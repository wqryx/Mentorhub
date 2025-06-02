<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Course;
use App\Models\Event;
use App\Models\Setting;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Models\Activity;
use App\Models\Message;
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
        
        // Pasar todos los datos recopilados a la vista
        return view('admin.dashboard', compact(
            'user', 
            'activeSessions', 
            'stats', 
            'recent_users', 
            'upcoming_events', 
            'recent_messages', 
            'recent_activities'
        ));
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
    
    // El método users() ha sido eliminado para evitar duplicación con UserController
    
    // El método createUser() ha sido eliminado para evitar duplicación con UserController
    
    // El método storeUser() ha sido eliminado para evitar duplicación con UserController
    
    // El método editUser() ha sido eliminado para evitar duplicación con UserController
    
    // El método updateUser() ha sido eliminado para evitar duplicación con UserController
    
    // El método destroyUser() ha sido eliminado para evitar duplicación con UserController
    
    /**
     * Mostrar la lista de cursos
     */
    public function courses()
    {
        $courses = Course::with('teacher')->paginate(10);
        return view('admin.courses.index', compact('courses'));
    }

    /**
     * Mostrar el formulario para crear un nuevo curso
     */
    public function createCourse()
    {
        $mentors = User::role('mentor')->get();
        return view('admin.courses.create', compact('mentors'));
    }

    /**
     * Almacenar un nuevo curso en la base de datos
     */
    public function storeCourse(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'teacher_id' => 'required|exists:users,id',
            'price' => 'nullable|numeric|min:0',
            'duration' => 'nullable|integer|min:1',
            'is_active' => 'boolean',
            'image' => 'nullable|image|max:2048',
        ]);
        
        $course = new Course($validated);
        
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('courses', 'public');
            $course->image = $imagePath;
        }
        
        $course->save();
        
        return redirect()->route('admin.courses.index')
            ->with('success', 'Curso creado correctamente');
    }

    /**
     * Mostrar el formulario para editar un curso
     */
    public function editCourse(Course $course)
    {
        $mentors = User::role('mentor')->get();
        return view('admin.courses.edit', compact('course', 'mentors'));
    }

    /**
     * Actualizar un curso en la base de datos
     */
    public function updateCourse(Request $request, Course $course)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'teacher_id' => 'required|exists:users,id',
            'price' => 'nullable|numeric|min:0',
            'duration' => 'nullable|integer|min:1',
            'is_active' => 'boolean',
            'image' => 'nullable|image|max:2048',
        ]);
        
        $course->fill($validated);
        
        if ($request->hasFile('image')) {
            // Eliminar imagen anterior si existe
            if ($course->image) {
                Storage::disk('public')->delete($course->image);
            }
            
            $imagePath = $request->file('image')->store('courses', 'public');
            $course->image = $imagePath;
        }
        
        $course->save();
        
        return redirect()->route('admin.courses.index')
            ->with('success', 'Curso actualizado correctamente');
    }

    /**
     * Eliminar un curso de la base de datos
     */
    public function destroyCourse(Course $course)
    {
        // Eliminar imagen si existe
        if ($course->image) {
            Storage::disk('public')->delete($course->image);
        }
        
        $course->delete();
        
        return redirect()->route('admin.courses.index')
            ->with('success', 'Curso eliminado correctamente');
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
     * Mostrar el formulario para crear un nuevo evento
     */
    public function createEvent()
    {
        $mentors = User::role('mentor')->get();
        return view('admin.events.create', compact('mentors'));
    }

    /**
     * Almacenar un nuevo evento en la base de datos
     */
    public function storeEvent(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'location' => 'required|string|max:255',
            'capacity' => 'nullable|integer|min:1',
            'type' => 'required|string|in:workshop,webinar,conference,mentorship',
            'creator_id' => 'required|exists:users,id',
            'is_online' => 'boolean',
            'image' => 'nullable|image|max:2048',
        ]);
        
        $event = new Event($validated);
        
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('events', 'public');
            $event->image = $imagePath;
        }
        
        $event->save();
        
        return redirect()->route('admin.events.index')
            ->with('success', 'Evento creado correctamente');
    }

    /**
     * Mostrar el formulario para editar un evento
     */
    public function editEvent(Event $event)
    {
        $mentors = User::role('mentor')->get();
        return view('admin.events.edit', compact('event', 'mentors'));
    }

    /**
     * Actualizar un evento en la base de datos
     */
    public function updateEvent(Request $request, Event $event)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'location' => 'required|string|max:255',
            'capacity' => 'nullable|integer|min:1',
            'type' => 'required|string|in:workshop,webinar,conference,mentorship',
            'creator_id' => 'required|exists:users,id',
            'is_online' => 'boolean',
            'image' => 'nullable|image|max:2048',
        ]);
        
        $event->fill($validated);
        
        if ($request->hasFile('image')) {
            // Eliminar imagen anterior si existe
            if ($event->image) {
                Storage::disk('public')->delete($event->image);
            }
            
            $imagePath = $request->file('image')->store('events', 'public');
            $event->image = $imagePath;
        }
        
        $event->save();
        
        return redirect()->route('admin.events.index')
            ->with('success', 'Evento actualizado correctamente');
    }

    /**
     * Eliminar un evento de la base de datos
     */
    public function destroyEvent(Event $event)
    {
        // Eliminar imagen si existe
        if ($event->image) {
            Storage::disk('public')->delete($event->image);
        }
        
        $event->delete();
        
        return redirect()->route('admin.events.index')
            ->with('success', 'Evento eliminado correctamente');
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
        // Obtener todas las configuraciones del sistema
        $settings = Setting::pluck('value', 'key')->toArray();
        
        return view('admin.settings.index', compact('settings'));
    }
    
    /**
     * Actualizar la configuración del sistema
     */
    public function updateSettings(Request $request)
    {
        // Validar según la sección que se está actualizando
        $section = $request->input('section', 'general');
        
        switch ($section) {
            case 'general':
                $validated = $request->validate([
                    'site_name' => 'required|string|max:255',
                    'site_description' => 'nullable|string',
                    'contact_email' => 'required|email',
                    'footer_text' => 'nullable|string',
                ]);
                break;
                
            case 'email':
                $validated = $request->validate([
                    'mail_driver' => 'required|string',
                    'mail_host' => 'required|string',
                    'mail_port' => 'required|numeric',
                    'mail_username' => 'nullable|string',
                    'mail_password' => 'nullable|string',
                    'mail_encryption' => 'nullable|string',
                ]);
                break;
                
            case 'registration':
                $validated = $request->validate([
                    'enable_registration' => 'nullable|boolean',
                    'email_verification' => 'nullable|boolean',
                    'default_role' => 'required|string',
                    'password_min_length' => 'required|numeric|min:6|max:20',
                ]);
                break;
                
            case 'activity':
                $validated = $request->validate([
                    'enable_activity_log' => 'nullable|boolean',
                    'log_retention_days' => 'required|numeric|min:1|max:365',
                    'log_login_attempts' => 'nullable|boolean',
                    'log_admin_actions' => 'nullable|boolean',
                ]);
                break;
                
            default:
                return redirect()->route('admin.settings.index')
                    ->with('error', 'Sección de configuración no válida');
        }
        
        // Actualizar cada configuración en la base de datos
        foreach ($validated as $key => $value) {
            // Si es un checkbox y no está marcado, el valor no viene en la solicitud
            if (in_array($key, ['enable_registration', 'email_verification', 'enable_activity_log', 'log_login_attempts', 'log_admin_actions'])) {
                $value = $request->has($key) ? '1' : '0';
            }
            
            Setting::set($key, $value, null, $section);
        }
        
        return redirect()->route('admin.settings.index')
            ->with('success', 'Configuración actualizada correctamente');
    }
}
