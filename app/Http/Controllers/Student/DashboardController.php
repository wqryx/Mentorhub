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
            ->with(['course' => function($query) {
                $query->with(['instructor', 'category']);
            }])
            ->latest()
            ->take(4)
            ->get();
        
        // Obtener próximos eventos a través de la relación event_user
        $upcomingEvents = $user->events()
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
            'todayEvents' => $user->events()
                ->whereDate('start_date', Carbon::today())
                ->count(),
            'upcomingEvents' => $user->events()
                ->where('start_date', '>=', Carbon::now())
                ->count(),
        ];
        
        return view('student.dashboard', compact('inProgressCourses', 'upcomingEvents', 'pendingTasks', 'stats'));
    }

    /**
     * Mostrar los cursos del estudiante.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function courses()
    {
        $user = Auth::user();
        
        // Obtener matrículas con cursos que existen
        $enrollments = Enrollment::where('user_id', $user->id)
            ->whereHas('course') // Solo matrículas con cursos existentes
            ->with(['course' => function($query) {
                $query->with('instructor');
            }])
            ->latest()
            ->paginate(12);
            
        // Obtener cursos en progreso para la barra lateral
        $inProgressCourses = Enrollment::where('user_id', $user->id)
            ->where('status', 'in_progress')
            ->whereHas('course') // Solo cursos existentes
            ->with(['course' => function($query) {
                $query->with('instructor');
            }])
            ->latest()
            ->take(5)
            ->get();
        
        return view('student.courses.index', compact('enrollments', 'inProgressCourses'));
    }
    
    /**
     * Mostrar cursos disponibles para inscribirse
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function availableCourses()
    {
        $user = Auth::user();
        
        // Obtener IDs de cursos en los que ya está inscrito el usuario
        $enrolledCourseIds = $user->enrollments()->pluck('course_id');
        
        // Obtener cursos disponibles (excluyendo los ya inscritos)
        $availableCourses = Course::where('is_active', true)
            ->whereNotIn('id', $enrolledCourseIds)
            ->with(['instructor', 'category'])
            ->withCount('students')
            ->latest()
            ->paginate(12);
            
        return view('student.courses.available', compact('availableCourses'));
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
        return view('student.settings.settings', compact('user'));
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
        
        // Cargar el curso con sus relaciones necesarias
        $course = Course::with([
            'modules' => function($query) {
                $query->orderBy('order', 'asc')
                      ->with(['tutorials' => function($q) {
                          $q->orderBy('order', 'asc');
                      }]);
            },
            'resources',
            'instructor'
        ])->findOrFail($id);
        
        // Verificar si el estudiante está inscrito
        $enrollment = Enrollment::where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->first();
            
        // Inicializar recursos si no existen
        if (!$course->relationLoaded('resources')) {
            $course->setRelation('resources', collect());
        }
        
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
        
        // Cargar el curso con sus módulos, tutoriales y relaciones necesarias
        $course = Course::with(['modules' => function($query) {
            $query->orderBy('order')->with(['tutorials' => function($q) {
                $q->orderBy('order');
            }]);
        }, 'instructor'])->findOrFail($course);
        
        // Obtener la inscripción del usuario
        $enrollment = Enrollment::where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->firstOrFail();
            
        // Inicializar arrays para el progreso
        $moduleProgress = [
            'total' => $course->modules->count(),
            'completed' => 0,
            'in_progress' => 0,
            'not_started' => 0
        ];
        
        $tutorialProgress = [
            'total' => 0,
            'completed' => 0,
            'in_progress' => 0,
            'not_started' => 0
        ];
        
        $quizProgress = [
            'total' => 0,
            'completed' => 0,
            'in_progress' => 0,
            'not_started' => 0
        ];
        
        // Calcular el progreso por módulo
        $modules = $course->modules->map(function($module) use ($user, &$moduleProgress, &$tutorialProgress) {
            $totalTutorials = $module->tutorials->count();
            $completedTutorials = 0;
            $tutorials = [];
            
            // Procesar cada tutorial del módulo
            foreach ($module->tutorials as $tutorial) {
                $tutorialProgress['total']++;
                $tutorialStatus = 'not_started';
                
                // Verificar si el tutorial está completado (esto es un ejemplo, ajusta según tu lógica)
                $isCompleted = false; // Aquí iría tu lógica para verificar si el tutorial está completado
                
                if ($isCompleted) {
                    $completedTutorials++;
                    $tutorialStatus = 'completed';
                    $tutorialProgress['completed']++;
                } else {
                    // Verificar si hay algún progreso
                    $hasProgress = false; // Aquí iría tu lógica para verificar si hay progreso
                    
                    if ($hasProgress) {
                        $tutorialStatus = 'in_progress';
                        $tutorialProgress['in_progress']++;
                    } else {
                        $tutorialProgress['not_started']++;
                    }
                }
                
                $tutorials[] = [
                    'id' => $tutorial->id,
                    'title' => $tutorial->title,
                    'status' => $tutorialStatus,
                    'order' => $tutorial->order
                ];
            }
            
            // Calcular progreso del módulo
            $progress = $totalTutorials > 0 ? round(($completedTutorials / $totalTutorials) * 100) : 0;
            
            // Actualizar contadores de progreso de módulos
            if ($progress >= 100) {
                $moduleProgress['completed']++;
            } elseif ($progress > 0) {
                $moduleProgress['in_progress']++;
            } else {
                $moduleProgress['not_started']++;
            }
            
            return [
                'id' => $module->id,
                'title' => $module->title,
                'description' => $module->description,
                'order' => $module->order,
                'progress' => $progress,
                'total_tutorials' => $totalTutorials,
                'completed_tutorials' => $completedTutorials,
                'tutorials' => collect($tutorials)
            ];
        });
        
        // Calcular el progreso general del curso
        $totalTutorials = $course->modules->sum('tutorials_count');
        $completedTutorials = $course->modules->sum(function($module) {
            return $module->tutorials->count() * ($module->pivot->progress ?? 0) / 100;
        });
        
        $overallProgress = $totalTutorials > 0 ? round(($completedTutorials / $totalTutorials) * 100) : 0;
        
        // Actualizar el progreso en la inscripción si es necesario
        if ($enrollment->progress != $overallProgress) {
            $enrollment->update(['progress' => $overallProgress]);
        }
        
        // Obtener actividades recientes (últimos tutoriales accedidos)
        $recentActivities = [];
        if (class_exists('App\\Models\\Activity') && method_exists($user, 'activities')) {
            $recentActivities = $user->activities()
                ->where('subject_type', 'App\\Models\\Tutorial')
                ->whereHas('tutorial', function($query) use ($course) {
                    $query->where('course_id', $course->id);
                })
                ->with(['subject' => function($query) {
                    $query->with('module');
                }])
                ->latest()
                ->take(5)
                ->get();
        }
        
        return view('student.courses.progress', [
            'course' => $course,
            'enrollment' => $enrollment,
            'modules' => $modules,
            'recentActivities' => $recentActivities,
            'moduleProgress' => $moduleProgress,
            'tutorialProgress' => $tutorialProgress,
            'quizProgress' => $quizProgress,
            'overallProgress' => $overallProgress
        ]);
        
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
        
        return view('student.courses.progress', [
            'course' => $course,
            'enrollment' => $enrollment,
            'modules' => $modules,
            'recentActivities' => $recentActivities,
            'moduleProgress' => $moduleProgress,
            'tutorialProgress' => $tutorialProgress,
            'quizProgress' => $quizProgress,
            'overallProgress' => $enrollment->progress->where('completed', true)->count() / max($course->tutorials->count(), 1) * 100
        ]);
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
     * Mostrar los mentores asignados al estudiante
     *
     * @return \Illuminate\Http\Response
     */
    public function mentor()
    {
        $user = Auth::user();
        
        // Obtener el primer mentor asignado al estudiante
        $mentor = User::role('mentor')
            ->whereHas('mentorStudents', function ($query) use ($user) {
                $query->where('student_id', $user->id);
            })
            ->with('profile', 'specialities')
            ->first();
            
        if (!$mentor) {
            return view('student.mentor.index', ['mentor' => null]);
        }
        
        return view('student.mentor.index', compact('mentor'));
    }
}