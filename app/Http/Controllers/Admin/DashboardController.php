<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Module;
use App\Models\Task;
use App\Models\Grade;
use App\Models\Message;
use App\Models\Resource;
use App\Models\Event;
use App\Models\User;
use App\Models\RecordClass;
use App\Models\News;

class DashboardController extends Controller
{
    /**
     * Crear una nueva instancia del controlador.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:web');
        $this->middleware('verified');
    }

    /**
     * Mostrar el dashboard basado en el tipo de usuario.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        $user = auth()->user();

        if (!$user) {
            return redirect()->route('login');
        }

        // Redirigir según el rol del usuario
        if ($user->hasRole('Admin')) {
            return redirect()->route('admin.dashboard');
        }
        
        if ($user->hasRole('Mentor')) {
            return redirect()->route('mentor.dashboard');
        }
        
        if ($user->hasRole('Estudiante')) {
            return redirect()->route('student.dashboard');
        }
        
        if ($user->hasRole('Invitado')) {
            return view('dashboard.guest');
        }
        
        // Si el usuario no tiene un rol asignado
        return view('dashboard.no-role', ['user' => $user]);
    }

    /**
     * Mostrar el dashboard del administrador.
     *
     * @return \Illuminate\View\View
     */
    protected function adminDashboard()
    {
        $user = auth()->user();
        
        // Obtener estadísticas generales
        $totalStudents = User::role('estudiante')->count();
        $totalMentors = User::role('mentor')->count();
        $totalCourses = \App\Models\Course::count();
        $totalEvents = \App\Models\Event::count();
        
        // Obtener usuarios recientes con sus roles
        $recentUsers = User::with('roles')->latest()->take(5)->get();
        $recent_users = $recentUsers; // Alias para la vista
        
        // Obtener próximos eventos con conteo de asistentes
        $upcoming_events = \App\Models\Event::with(['createdBy', 'attendees'])
            ->where('start_date', '>=', now())
            ->withCount('attendees')
            ->orderBy('start_date')
            ->take(5)
            ->get();
            
        $upcomingEvents = $upcoming_events; // Alias para mantener compatibilidad
            
        // Obtener estudiantes activos (por ahora usamos el total de estudiantes)
        $activeStudents = $totalStudents; // En un caso real, podrías filtrar por último acceso, estado, etc.
        
        // Obtener cursos activos (por ahora usamos el total de cursos)
        $activeCourses = $totalCourses; // En un caso real, podrías filtrar por fecha de inicio/fin, estado, etc.
        
        // Obtener actividades recientes
        $recent_activities = \App\Models\Activity::with(['causer', 'subject'])
            ->latest()
            ->take(5)
            ->get();
            
        // Obtener mensajes recientes
        $recent_messages = \App\Models\Message::with(['sender', 'receiver'])
            ->latest()
            ->take(5)
            ->get();
        
        // Preparar estadísticas para la vista
        $stats = [
            'total_users' => User::count(),
            'users_growth' => 0, // Podrías calcular esto basado en datos históricos
            'total_students' => $totalStudents,
            'active_students' => $activeStudents, // Añadido para la tarjeta de estudiantes activos
            'students_growth' => 0, // Podrías calcular esto basado en datos históricos
            'total_mentors' => $totalMentors,
            'mentors_count' => $totalMentors, // Añadido para la tarjeta de mentores
            'mentors_growth' => 0, // Podrías calcular esto basado en datos históricos
            'total_courses' => $totalCourses,
            'active_courses' => $activeCourses, // Añadido para la tarjeta de cursos activos
            'courses_growth' => 0, // Podrías calcular esto basado en datos históricos
        ];
            
        // Datos adicionales para la nueva vista
        $stats['paused_courses'] = 0; // Simulamos algunos datos para el estado de cursos
        $stats['completed_courses'] = 0;
        
        // Podríamos calcular estos valores desde la base de datos en una implementación real
        
        // Usar la nueva vista con diseño moderno
        return view('admin.dashboard.new_index', compact(
            'user',
            'totalStudents',
            'totalMentors',
            'totalCourses',
            'totalEvents',
            'recentUsers',
            'upcoming_events',
            'recent_activities',
            'recent_messages',
            'stats'
        ));
    }
    
    /**
     * Mostrar el dashboard del mentor.
     *
     * @return \Illuminate\View\View
     */
    protected function mentorDashboard()
    {
        $user = auth()->user();
        
        // Obtener estudiantes asignados
        $students = $user->mentorStudents()->with('user')->get();
        
        // Obtener próximas sesiones
        $upcomingSessions = \App\Models\MentorSession::where('mentor_id', $user->id)
            ->where('start_date', '>=', now())
            ->orderBy('start_date')
            ->take(5)
            ->get();
            
        // Obtener recursos del mentor
        $resources = $user->mentorResources()->latest()->take(5)->get();
        
        return view('mentor.dashboard.index', compact(
            'students',
            'upcomingSessions',
            'resources'
        ));
    }
    
    /**
     * Mostrar el dashboard del estudiante.
     *
     * @return \Illuminate\View\View
     */
    protected function studentDashboard()
    {
        $user = auth()->user();

        // Obtener módulos del estudiante
        $modules = $user->modules()->with('tasks', 'grades')->get();

        // Calcular progreso y tareas pendientes para cada módulo
        $modules->each(function ($module) {
            $totalTasks = $module->tasks->count();
            $completedTasks = $module->tasks->where('status', 'completed')->count();
            $module->progress = $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100) : 0;
            $module->pending_tasks = $module->tasks->where('status', 'pending')->count();
        });

        // Calcular progreso académico general
        $totalTasks = Task::where('user_id', $user->id)->count();
        $completedTasks = Task::where('user_id', $user->id)
            ->where('status', 'completed')
            ->count();
        $overall_progress = $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100) : 0;

        // Calcular el promedio de calificaciones
        $grades = Grade::where('user_id', $user->id)
            ->where('grade', '>=', 5)
            ->get();
        
        $totalGrades = $grades->sum('grade');
        $countGrades = $grades->count();
        $average = $countGrades > 0 ? round($totalGrades / $countGrades) : 0;

        // Obtener eventos próximos
        $upcoming_events = Event::whereHas('module', function ($query) use ($user) {
            $query->whereHas('students', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            });
        })
        ->where('date', '>=', now())
        ->with('module')
        ->orderBy('date')
        ->take(5)
        ->get();

        // Obtener noticias
        $news = News::orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Obtener notificaciones
        $notifications = $user->notifications()->latest()->take(5)->get();

        // Obtener clases grabadas
        $recorded_classes = RecordClass::whereHas('module', function ($query) use ($user) {
            $query->whereHas('students', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            });
        })
        ->with('module')
        ->orderBy('created_at', 'desc')
        ->take(5)
        ->get();

        // Generar el calendario
        $calendar = [];
        $currentDate = now();
        $firstDay = $currentDate->copy()->startOfMonth();
        $lastDay = $currentDate->copy()->endOfMonth();
        
        // Obtener todos los eventos del mes actual
        $events = Event::whereHas('module', function ($query) use ($user) {
            $query->whereHas('students', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            });
        })
        ->where('date', '>=', $firstDay)
        ->where('date', '<=', $lastDay)
        ->get();

        // Crear el calendario con los eventos
        while ($firstDay->lte($lastDay)) {
            $date = $firstDay->copy();
            $calendar[] = [
                'date' => $date,
                'number' => $date->day,
                'active' => $date->isSameDay($currentDate),
                'has_event' => $events->filter(function ($event) use ($date) {
                    return $event->date->format('Y-m-d') === $date->format('Y-m-d');
                })->count() > 0,
                'events' => $events->filter(function ($event) use ($date) {
                    return $event->date->format('Y-m-d') === $date->format('Y-m-d');
                })->map(function ($event) {
                    return [
                        'title' => $event->title,
                        'type' => $event->type
                    ];
                })
            ];
            $firstDay->addDay();
        }

        // Obtener tareas pendientes del usuario
        $pendingTasks = Task::where('user_id', $user->id)
            ->where('status', 'pending')
            ->with('module')
            ->get()
            ->map(function ($task) {
                return [
                    'title' => $task->title,
                    'due_date' => $task->due_date,
                    'status' => $task->status,
                    'module' => $task->module->name ?? 'Sin asignatura'
                ];
            });

        // Obtener calificaciones por módulo
        $module_grades = $modules->map(function ($module) {
            $grades = Grade::where('module_id', $module->id)
                ->where('user_id', $user->id)
                ->where('grade', '>=', 5)
                ->get();
            
            return [
                'module' => $module,
                'grades' => $grades,
                'average' => $grades->count() > 0 ? round($grades->avg('grade')) : 0
            ];
        });

        return view('dashboard.student', compact(
            'user',
            'modules',
            'overall_progress',
            'upcoming_events',
            'notifications',
            'news',
            'recorded_classes',
            'calendar',
            'pendingTasks',
            'average',
            'module_grades'
        ));
    }

    /**
     * Generar el calendario mensual con eventos.
     *
     * @param  \App\Models\User  $user
     * @return array
     */
    public function generateCalendar($user)
    {
        // Obtener eventos relacionados con los módulos del usuario
        $events = Event::whereHas('module', function ($query) use ($user) {
            $query->whereHas('students', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            });
        })
        ->with('module')
        ->get();

        // Obtener fecha actual
        $today = now();
        
        // Crear array para el calendario
        $calendar = [];
        
        // Obtener el primer día del mes actual
        $firstDay = $today->copy()->startOfMonth();
        // Obtener el último día del mes actual
        $lastDay = $today->copy()->endOfMonth();
        
        // Generar días del calendario
        $currentDay = $firstDay->copy();
        while ($currentDay->lte($lastDay)) {
            $day = [
                'number' => $currentDay->day,
                'active' => $currentDay->isToday(),
                'has_event' => false,
                'events' => []
            ];
            
            // Agregar eventos del día
            foreach ($events as $event) {
                if ($event->date->isSameDay($currentDay)) {
                    $day['has_event'] = true;
                    $day['events'][] = [
                        'title' => $event->title,
                        'type' => $event->type,
                        'module' => $event->module->name ?? null
                    ];
                }
            }
            
            $calendar[] = $day;
            $currentDay->addDay();
        }
        
        return $calendar;
    }
}
