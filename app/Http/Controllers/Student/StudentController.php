<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\CourseHistory;
use App\Models\Enrollment;
use App\Models\Event;
use App\Models\Module;
use App\Models\Task;
use App\Models\Tutorial;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StudentController extends Controller
{
    /**
     * Mostrar el dashboard principal del estudiante
     */
    public function dashboard()
    {
        $user = Auth::user();
        
        // Cursos en progreso
        $inProgressCourses = Enrollment::where('user_id', $user->id)
            ->where('progress', '>', 0)
            ->where('progress', '<', 100)
            ->with('course')
            ->take(4)
            ->get();
        
        // Próximos eventos
        $upcomingEvents = Event::whereHas('attendees', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })
        ->where('start_date', '>=', now())
        ->orderBy('start_date')
        ->take(5)
        ->get();
        
        // Tareas pendientes
        $pendingTasks = Task::where('user_id', $user->id)
            ->where('status', 'pending')
            ->where(function($query) {
                $query->where('due_date', '>=', now())
                      ->orWhereNull('due_date');
            })
            ->orderBy('due_date')
            ->take(5)
            ->get();
        
        // Estadísticas para el dashboard
        $stats = [
            'totalCourses' => Enrollment::where('user_id', $user->id)->count(),
            'coursesInProgress' => Enrollment::where('user_id', $user->id)
                ->where('progress', '>', 0)
                ->where('progress', '<', 100)
                ->count(),
            'completedCourses' => Enrollment::where('user_id', $user->id)
                ->where('progress', 100)
                ->count(),
            'averageProgress' => Enrollment::where('user_id', $user->id)->avg('progress') ?? 0,
            'pendingEvents' => Event::whereHas('attendees', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->where('start_date', '>=', now())
            ->count(),
            'todayEvents' => Event::whereHas('attendees', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->whereDate('start_date', now()->toDateString())
            ->count()
        ];
        
        return view('dashboard.student.index', compact(
            'user', 'inProgressCourses', 'upcomingEvents', 'pendingTasks', 'stats'
        ));
    }
    
    /**
     * Mostrar la lista de cursos del estudiante
     */
    public function courses(Request $request)
    {
        $user = Auth::user();
        $query = Enrollment::where('user_id', $user->id)
            ->with('course');
        
        // Aplicar filtros
        if ($request->has('status')) {
            if ($request->status == 'in_progress') {
                $query->where('progress', '>', 0)->where('progress', '<', 100);
            } elseif ($request->status == 'completed') {
                $query->where('progress', 100);
            } elseif ($request->status == 'not_started') {
                $query->where('progress', 0);
            }
        }
        
        // Aplicar ordenamiento
        if ($request->has('sort')) {
            if ($request->sort == 'recent') {
                $query->orderBy('created_at', 'desc');
            } elseif ($request->sort == 'progress') {
                $query->orderBy('progress', 'desc');
            } elseif ($request->sort == 'title') {
                $query->join('courses', 'enrollments.course_id', '=', 'courses.id')
                      ->orderBy('courses.title', 'asc')
                      ->select('enrollments.*');
            }
        } else {
            $query->orderBy('created_at', 'desc');
        }
        
        // Cursos en progreso (para la sección "Continúa aprendiendo")
        $inProgressCourses = Enrollment::where('user_id', $user->id)
            ->where('progress', '>', 0)
            ->where('progress', '<', 100)
            ->with(['course', 'currentTutorial'])
            ->orderBy('last_activity_at', 'desc')
            ->take(3)
            ->get();
        
        // Todos los cursos con paginación
        $enrollments = $query->paginate(9);
        
        return view('dashboard.student.courses', compact('enrollments', 'inProgressCourses'));
    }
    
    /**
     * Mostrar un curso específico
     */
    public function showCourse($id)
    {
        $course = Course::findOrFail($id);
        $user = Auth::user();
        
        // Verificar si el estudiante está inscrito en el curso
        if (!$course->enrollments()->where('user_id', $user->id)->exists()) {
            return redirect()->route('student.courses')
                ->with('error', 'No tienes acceso a este curso');
        }
        
        // Obtener la inscripción para mostrar el progreso
        $enrollment = Enrollment::where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->first();
        
        return view('courses.show', compact('course', 'enrollment'));
    }
    
    /**
     * Mostrar el calendario del estudiante
     */
    public function calendar()
    {
        $user = Auth::user();
        $events = Event::whereHas('attendees', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })->get();
        
        return view('dashboard.student.calendar', compact('events'));
    }
    
    /**
     * Mostrar el perfil del estudiante
     */
    public function profile()
    {
        $user = Auth::user();
        return view('dashboard.student.profile', compact('user'));
    }
    
    /**
     * Actualizar el perfil del estudiante
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'photo' => 'nullable|image|max:1024',
        ]);
        
        $user->name = $request->name;
        $user->email = $request->email;
        
        if ($request->hasFile('photo')) {
            // Lógica para guardar la imagen
            $path = $request->file('photo')->store('profile-photos', 'public');
            $user->photo = $path;
        }
        
        $user->save();
        
        return redirect()->route('student.profile')
            ->with('success', 'Perfil actualizado correctamente');
    }
    
    /**
     * Mostrar las notificaciones del estudiante
     */
    public function notifications()
    {
        $user = Auth::user();
        $notifications = $user->notifications()->paginate(10);
        
        return view('dashboard.student.notifications', compact('notifications'));
    }
    
    /**
     * Mostrar los mensajes del estudiante
     */
    public function messages()
    {
        $user = Auth::user();
        $messages = $user->messages()->latest()->paginate(10);
        
        return view('dashboard.student.messages', compact('messages'));
    }
    
    /**
     * Mostrar las tareas del estudiante
     */
    public function tasks()
    {
        $user = Auth::user();
        $tasks = Task::where('user_id', $user->id)->get();
        
        return view('dashboard.student.tasks', compact('tasks'));
    }
    
    /**
     * Mostrar las calificaciones del estudiante
     */
    public function grades()
    {
        $user = Auth::user();
        
        // Obtener cursos del estudiante con sus calificaciones
        $courses = Course::whereHas('enrollments', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })->with(['grades' => function($query) use ($user) {
            $query->where('user_id', $user->id);
        }])->get();
        
        return view('dashboard.student.grades', compact('courses', 'user'));
    }
    
    /**
     * Mostrar el progreso detallado de un curso
     */
    public function courseProgress(Course $course)
    {
        $user = Auth::user();
        
        // Verificar si el estudiante está inscrito en el curso
        $enrollment = Enrollment::where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->firstOrFail();
            
        // Obtener el progreso por módulo
        $moduleCompletionCount = [];
        $tutorialCompletionStatus = [];
        $tutorialStartedStatus = [];
        
        // Obtener el historial de curso para calcular progreso
        $courseHistories = CourseHistory::where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->get();
            
        foreach ($courseHistories as $history) {
            if ($history->tutorialable_type === Tutorial::class) {
                if ($history->is_completed) {
                    $tutorialCompletionStatus[$history->tutorialable_id] = true;
                    
                    // Incrementar el contador de tutoriales completados por módulo
                    $tutorial = Tutorial::find($history->tutorialable_id);
                    if ($tutorial) {
                        if (!isset($moduleCompletionCount[$tutorial->module_id])) {
                            $moduleCompletionCount[$tutorial->module_id] = 0;
                        }
                        $moduleCompletionCount[$tutorial->module_id]++;
                    }
                } else {
                    $tutorialStartedStatus[$history->tutorialable_id] = true;
                }
            }
        }
        
        // Estadísticas de progreso
        $moduleProgress = [
            'total' => $course->modules->count(),
            'completed' => 0
        ];
        
        $tutorialProgress = [
            'total' => 0,
            'completed' => 0
        ];
        
        $quizProgress = [
            'total' => 0,
            'passed' => 0
        ];
        
        // Contar tutoriales completados y módulos completados
        foreach ($course->modules as $module) {
            $tutorialProgress['total'] += $module->tutorials->count();
            
            $allTutorialsCompleted = true;
            foreach ($module->tutorials as $tutorial) {
                if (!isset($tutorialCompletionStatus[$tutorial->id]) || !$tutorialCompletionStatus[$tutorial->id]) {
                    $allTutorialsCompleted = false;
                }
                
                // Contar quizzes
                $quizContents = $tutorial->contents->where('type', 'quiz');
                $quizProgress['total'] += $quizContents->count();
                
                // Contar quizzes aprobados (simulado, se implementaría la lógica real)
                foreach ($quizContents as $quiz) {
                    if (isset($tutorialCompletionStatus[$tutorial->id]) && $tutorialCompletionStatus[$tutorial->id]) {
                        $quizProgress['passed']++;
                    }
                }
            }
            
            if ($allTutorialsCompleted && $module->tutorials->count() > 0) {
                $moduleProgress['completed']++;
            }
        }
        
        $tutorialProgress['completed'] = count($tutorialCompletionStatus);
        
        // Actividades recientes
        $recentActivities = CourseHistory::where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get()
            ->map(function ($history) {
                // Mapear las actividades para mostrar información relevante
                $history->actionable = null;
                
                if ($history->tutorialable_type === Tutorial::class) {
                    $history->actionable = Tutorial::find($history->tutorialable_id);
                    $history->action_type = $history->is_completed ? 'completed_tutorial' : 'started_tutorial';
                } elseif ($history->tutorialable_type === Module::class) {
                    $history->actionable = Module::find($history->tutorialable_id);
                    $history->action_type = 'completed_module';
                } elseif ($history->tutorialable_type === Course::class) {
                    $history->actionable = Course::find($history->tutorialable_id);
                    $history->action_type = 'completed_course';
                } elseif ($history->tutorialable_type === 'Quiz') {
                    // Simulado, se implementaría la lógica real para quizzes
                    $history->action_type = 'passed_quiz';
                }
                
                return $history;
            });
        
        return view('dashboard.student.course-progress', compact(
            'course', 
            'enrollment', 
            'moduleCompletionCount', 
            'tutorialCompletionStatus', 
            'tutorialStartedStatus',
            'moduleProgress',
            'tutorialProgress',
            'quizProgress',
            'recentActivities'
        ));
    }
    
    /**
     * Marcar una tarea como completada
     */
    public function completeTask(Task $task)
    {
        $user = Auth::user();
        
        // Verificar si la tarea pertenece al usuario
        if ($task->user_id !== $user->id) {
            return response()->json(['success' => false, 'message' => 'No tienes permiso para modificar esta tarea'], 403);
        }
        
        $task->completed = true;
        $task->completed_at = now();
        $task->save();
        
        return response()->json(['success' => true]);
    }
    
    /**
     * Marcar todas las notificaciones como leídas
     */
    public function markAllNotificationsAsRead()
    {
        $user = Auth::user();
        $user->unreadNotifications->markAsRead();
        
        return redirect()->back()->with('success', 'Todas las notificaciones han sido marcadas como leídas');
    }
    
    /**
     * Mostrar la página de configuración del estudiante
     */
    public function settings()
    {
        $user = Auth::user();
        return view('dashboard.student.settings', compact('user'));
    }
    
    /**
     * Mostrar los cursos del estudiante
     */
    public function studentCourses()
    {
        $user = Auth::user();
        $enrollments = Enrollment::where('user_id', $user->id)
            ->with('course')
            ->get();
            
        return view('dashboard.student.courses', [
            'enrollments' => $enrollments,
            'user' => $user
        ]);
    }
    
    /**
     * Mostrar el progreso del estudiante
     */
    public function studentProgress()
    {
        $user = Auth::user();
        $enrollments = Enrollment::where('user_id', $user->id)
            ->with(['course', 'course.modules.tutorials'])
            ->get();
            
        return view('dashboard.student.course-progress', [
            'enrollments' => $enrollments,
            'user' => $user
        ]);
    }
    
    /**
     * Mostrar la sección de mentor
     */
    public function studentMentor()
    {
        $user = Auth::user();
        $mentor = $user->mentor;
        
        return view('dashboard.student.mentor', [
            'mentor' => $mentor,
            'user' => $user
        ]);
    }
    
    /**
     * Mostrar el perfil del estudiante
     */
    public function studentProfile()
    {
        $user = Auth::user();
        
        return view('dashboard.student.settings', [
            'user' => $user
        ]);
    }
    
    /**
     * Mostrar el calendario del estudiante
     */
    public function studentCalendar()
    {
        $user = Auth::user();
        $events = Event::whereHas('attendees', function($query) use ($user) {
            $query->where('user_id', $user->id);
        })->get();
        
        return view('dashboard.student.calendar', [
            'events' => $events,
            'user' => $user
        ]);
    }
    
    /**
     * Mostrar las tareas del estudiante
     */
    public function studentTasks()
    {
        $user = Auth::user();
        $tasks = Task::where('user_id', $user->id)
            ->orderBy('due_date')
            ->get();
            
        return view('dashboard.student.tasks', [
            'tasks' => $tasks,
            'user' => $user
        ]);
    }
}
