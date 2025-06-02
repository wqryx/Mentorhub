<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Task;
use App\Models\Event;
use App\Models\User;
use App\Models\Module;
use App\Models\Tutorial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
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
     * Mostrar el dashboard actualizado del estudiante.
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
            ->paginate(10);
        
        $completedTasks = Task::where('user_id', $user->id)
            ->where('status', 'completed')
            ->latest()
            ->take(5)
            ->get();
        
        return view('student.tasks.index', compact('pendingTasks', 'completedTasks'));
    }

    /**
     * Mostrar el calendario del estudiante.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function calendar()
    {
        return view('student.calendar.index');
    }

    /**
     * Mostrar la configuración del estudiante.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function settings()
    {
        $user = Auth::user();
        return view('student.profile.settings', compact('user'));
    }
    
    /**
     * Mostrar un curso específico.
     *
     * @param int $id
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function showCourse($id)
    {
        $user = Auth::user();
        $course = Course::findOrFail($id);
        
        // Verificar si el estudiante está inscrito
        $enrollment = Enrollment::where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->first();
            
        return view('student.courses.show', compact('course', 'enrollment'));
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
            $moduleTutorialsTotal = $module->tutorials->count();
            $moduleTutorialsCompleted = 0;
            
            $tutorialProgress['total'] += $moduleTutorialsTotal;
            
            foreach ($module->tutorials as $tutorial) {
                // Verificar si el tutorial está completado
                $tutorialCompleted = $tutorial->courseHistories
                    ->where('user_id', $user->id)
                    ->where('completed', true)
                    ->count() > 0;
                
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
     * Mostrar las calificaciones del estudiante.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function grades()
    {
        $user = Auth::user();
        
        $enrollments = Enrollment::where('user_id', $user->id)
            ->with(['course', 'grades'])
            ->get();
        
        return view('student.grades.index', compact('enrollments'));
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