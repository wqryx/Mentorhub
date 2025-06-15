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

class StudentController extends Controller
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
    public function dashboard()
    {
        $user = Auth::user();
        
        // Obtener cursos en progreso
        $inProgressCourses = Enrollment::where('user_id', $user->id)
            ->where('status', 'in_progress')
            ->with('course')
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
            
        // Get in-progress courses for the sidebar
        $inProgressCourses = Enrollment::where('user_id', $user->id)
            ->where('status', 'in_progress')
            ->with('course')
            ->latest()
            ->take(5)
            ->get();
        
        return view('student.courses.index', compact('enrollments', 'inProgressCourses'));
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
        
        $enrollment = Enrollment::where('user_id', $user->id)
            ->where('course_id', $id)
            ->firstOrFail();
        
        return view('student.courses.show', compact('course', 'enrollment'));
    }

    /**
     * Mostrar el progreso de un curso.
     *
     * @param Course $course
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function courseProgress(Course $course)
    {
        $user = Auth::user();
        
        $enrollment = Enrollment::where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->firstOrFail();
        
        return view('student.courses.progress', compact('course', 'enrollment'));
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
     * Actualizar el perfil del estudiante.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'bio' => 'nullable|string|max:500',
            'phone' => 'nullable|string|max:20',
        ]);
        
        $user->name = $validated['name'];
        $user->email = $validated['email'];
        
        if ($request->hasFile('avatar')) {
            // Guardar avatar
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
            
            // Actualizar o crear perfil
            if ($user->profile) {
                $user->profile->avatar = $avatarPath;
                $user->profile->bio = $validated['bio'] ?? $user->profile->bio;
                $user->profile->phone = $validated['phone'] ?? $user->profile->phone;
                $user->profile->save();
            } else {
                $user->profile()->create([
                    'avatar' => $avatarPath,
                    'bio' => $validated['bio'] ?? null,
                    'phone' => $validated['phone'] ?? null,
                ]);
            }
        } elseif ($request->has('bio') || $request->has('phone')) {
            // Actualizar o crear perfil sin avatar
            if ($user->profile) {
                $user->profile->bio = $validated['bio'] ?? $user->profile->bio;
                $user->profile->phone = $validated['phone'] ?? $user->profile->phone;
                $user->profile->save();
            } else {
                $user->profile()->create([
                    'bio' => $validated['bio'] ?? null,
                    'phone' => $validated['phone'] ?? null,
                ]);
            }
        }
        
        $user->save();
        
        return redirect()->route('student.profile')->with('success', 'Perfil actualizado correctamente');
    }

    /**
     * Mostrar las notificaciones del estudiante.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function notifications()
    {
        $user = Auth::user();
        $notifications = $user->notifications()->paginate(15);
        
        return view('student.notifications.index', compact('notifications'));
    }

    /**
     * Marcar todas las notificaciones como leídas.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function markAllNotificationsAsRead()
    {
        $user = Auth::user();
        $user->unreadNotifications->markAsRead();
        
        return redirect()->back()->with('success', 'Todas las notificaciones han sido marcadas como leídas');
    }
    
    /**
     * Mostrar una notificación específica.
     *
     * @param string $notification
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function showNotification($notification)
    {
        $user = Auth::user();
        $notification = $user->notifications()->where('id', $notification)->firstOrFail();
        
        // Marcar como leída si no lo está
        if (!$notification->read_at) {
            $notification->markAsRead();
        }
        
        // Determinar la vista según el tipo de notificación
        $view = 'student.notifications.show';
        
        return view($view, compact('notification'));
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
     * Mostrar las calificaciones del estudiante.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function grades()
    {
        $user = Auth::user();
        $enrollments = Enrollment::where('user_id', $user->id)
            ->with('course')
            ->get();
        
        return view('student.courses.grades', compact('enrollments'));
    }
}
