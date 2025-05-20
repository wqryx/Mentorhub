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

        // Obtener módulos del usuario
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

    /**
     * Mostrar específicamente el dashboard del estudiante.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function studentDashboard()
    {
        return view('dashboard.student');
    }

    /**
     * Mostrar el dashboard del mentor.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function mentorDashboard()
    {
        return view('dashboard.mentor');
    }

    /**
     * Mostrar el dashboard del administrador.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function adminDashboard()
    {
        return view('dashboard.admin');
    }
}
