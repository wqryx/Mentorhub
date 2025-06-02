<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Task;
use App\Models\User;
use App\Models\Event;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class StudentDashboardController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        // El middleware de roles ha sido eliminado temporalmente
    }

    /**
     * Mostrar el dashboard del estudiante.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $user = Auth::user();
        
        // Obtener cursos en progreso
        $inProgressCourses = Enrollment::where('user_id', $user->id)
            ->where('status', 'in_progress')
            ->with('course')
            ->latest()
            ->take(4)
            ->get();
        
        // Obtener próximos eventos
        $upcomingEvents = Event::where('user_id', $user->id)
            ->where('start_date', '>=', Carbon::now())
            ->orderBy('start_date')
            ->take(3)
            ->get();
        
        // Obtener tareas pendientes
        $pendingTasks = Task::where('user_id', $user->id)
            ->where('status', 'pending')
            ->orderBy('due_date')
            ->take(5)
            ->get();
        
        // Estadísticas
        $stats = [
            'totalCourses' => Enrollment::where('user_id', $user->id)->count(),
            'completedCourses' => Enrollment::where('user_id', $user->id)->where('status', 'completed')->count(),
            'coursesInProgress' => Enrollment::where('user_id', $user->id)->where('status', 'in_progress')->count(),
            'averageProgress' => Enrollment::where('user_id', $user->id)->avg('progress') ?? 0,
            'pendingTasks' => Task::where('user_id', $user->id)->where('status', 'pending')->count(),
            'todayEvents' => Event::where('user_id', $user->id)
                ->whereDate('start_date', Carbon::today())
                ->count(),
            'pendingEvents' => Event::where('user_id', $user->id)
                ->where('start_date', '>=', Carbon::now())
                ->count(),
        ];
        
        return view('student.index', compact('inProgressCourses', 'upcomingEvents', 'pendingTasks', 'stats'));
    }

    /**
     * Mostrar los cursos del estudiante.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function courses()
    {
        $user = Auth::user();
        
        $enrollments = Enrollment::where('user_id', $user->id)
            ->with('course')
            ->latest()
            ->paginate(12);
        
        return view('student.courses.index', compact('enrollments'));
    }

    /**
     * Mostrar el progreso del estudiante.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function progress()
    {
        $user = Auth::user();
        
        $enrollments = Enrollment::where('user_id', $user->id)
            ->with('course')
            ->get();
        
        return view('student.courses.progress', compact('enrollments'));
    }
    
    /**
     * Mostrar el progreso de un curso específico.
     *
     * @param int $course
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function courseProgress($course)
    {
        $user = Auth::user();
        
        $course = Course::findOrFail($course);
        
        $enrollment = Enrollment::where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->firstOrFail();
            
        // Obtener módulos y tutoriales del curso
        $modules = $course->modules()->with(['tutorials' => function($query) use ($user) {
            $query->with(['contents', 'courseHistories' => function($query) use ($user) {
                $query->where('user_id', $user->id);
            }]);
        }])->get();
        
        // Calcular estadísticas de progreso
        $moduleProgress = [
            'total' => $modules->count(),
            'completed' => 0,
            'in_progress' => 0
        ];
        
        $tutorialProgress = [
            'total' => 0,
            'completed' => 0,
            'in_progress' => 0
        ];
        
        $quizProgress = [
            'total' => 0,
            'passed' => 0,
            'failed' => 0
        ];
        
        foreach ($modules as $module) {
            $moduleTutorialsCompleted = 0;
            $moduleTutorialsTotal = $module->tutorials->count();
            $tutorialProgress['total'] += $moduleTutorialsTotal;
            
            foreach ($module->tutorials as $tutorial) {
                // Contar tutoriales completados
                $tutorialCompleted = $tutorial->courseHistories->where('user_id', $user->id)->where('completed', true)->count() > 0;
                if ($tutorialCompleted) {
                    $moduleTutorialsCompleted++;
                    $tutorialProgress['completed']++;
                } else if ($tutorial->courseHistories->where('user_id', $user->id)->count() > 0) {
                    $tutorialProgress['in_progress']++;
                }
                
                // Contar quizzes
                $quizContents = $tutorial->contents->where('type', 'quiz');
                $quizProgress['total'] += $quizContents->count();
                
                foreach ($quizContents as $quiz) {
                    $quizHistory = $quiz->courseHistories->where('user_id', $user->id)->first();
                    if ($quizHistory && $quizHistory->score >= 70) {
                        $quizProgress['passed']++;
                    } else if ($quizHistory) {
                        $quizProgress['failed']++;
                    }
                }
            }
            
            // Marcar módulo como completado si todos sus tutoriales están completados
            if ($moduleTutorialsTotal > 0 && $moduleTutorialsCompleted == $moduleTutorialsTotal) {
                $moduleProgress['completed']++;
            } else if ($moduleTutorialsCompleted > 0) {
                $moduleProgress['in_progress']++;
            }
        }
        
        return view('student.courses.progress', compact('course', 'enrollment', 'modules', 'moduleProgress', 'tutorialProgress', 'quizProgress'));
    }

    /**
     * Mostrar el calendario del estudiante.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function calendar()
    {
        $user = Auth::user();
        
        $events = Event::where('user_id', $user->id)
            ->orWhere('is_public', true)
            ->get();
        
        return view('student.calendar.index', compact('events'));
    }

    /**
     * Mostrar el perfil del estudiante.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function profile()
    {
        $user = Auth::user();
        return view('student.profile.index', compact('user'));
    }

    /**
     * Mostrar las tareas del estudiante.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function tasks()
    {
        $user = Auth::user();
        
        $pendingTasks = Task::where('user_id', $user->id)
            ->where('status', 'pending')
            ->orderBy('due_date')
            ->paginate(15);
        
        $completedTasks = Task::where('user_id', $user->id)
            ->where('status', 'completed')
            ->latest()
            ->take(10)
            ->get();
        
        return view('student.tasks.index', compact('pendingTasks', 'completedTasks'));
    }

    /**
     * Marcar una tarea como completada.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function completeTask($id)
    {
        $task = Task::findOrFail($id);
        
        // Verificar que la tarea pertenezca al usuario actual
        if ($task->user_id !== Auth::id()) {
            return response()->json(['success' => false, 'message' => 'No tienes permiso para modificar esta tarea'], 403);
        }
        
        $task->status = 'completed';
        $task->completed_at = now();
        $task->save();
        
        return response()->json(['success' => true, 'message' => 'Tarea completada correctamente']);
    }

    /**
     * Mostrar la página de contacto con el mentor.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function mentor()
    {
        $user = Auth::user();
        
        // Obtener mentores asignados al estudiante
        $mentors = User::role('mentor')
            ->whereHas('mentorStudents', function ($query) use ($user) {
                $query->where('student_id', $user->id);
            })
            ->get();
        
        return view('student.mentor.index', compact('mentors'));
    }
}
