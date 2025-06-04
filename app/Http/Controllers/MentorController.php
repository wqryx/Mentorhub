<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Course;
use App\Models\MentorshipSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class MentorController extends Controller
{
    /**
     * Muestra el dashboard del mentor
     */
    public function dashboard()
    {
        $user = Auth::user();
        
        // Próximas sesiones
        $upcomingSessions = $user->mentorSessions()
            ->with(['mentee', 'course'])
            ->where('start_time', '>=', now())
            ->orderBy('start_time')
            ->take(5)
            ->get();
            
        // Solicitudes pendientes
        $pendingRequests = $user->mentorRequests()
            ->where('status', 'pending')
            ->with(['mentee', 'course'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
            
        // Estadísticas
        $stats = [
            'total_sessions' => $user->mentorSessions()->count(),
            'upcoming_sessions' => $upcomingSessions->count(),
            'pending_requests' => $pendingRequests->count(),
            'completion_rate' => $user->mentorSessions()
                ->where('status', 'completed')
                ->count() / max($user->mentorSessions()->count(), 1) * 100
        ];
        
        return view('mentor.dashboard', compact('upcomingSessions', 'pendingRequests', 'stats'));
    }
    
    /**
     * Muestra el perfil del mentor
     */
    public function profile()
    {
        $user = Auth::user();
        $specialties = $user->specialties ?? [];
        
        return view('mentor.profile.index', compact('user', 'specialties'));
    }
    
    /**
     * Actualiza el perfil del mentor
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'bio' => 'nullable|string|max:1000',
            'specialties' => 'nullable|array',
            'specialties.*' => 'exists:specialties,id',
            'linkedin_url' => 'nullable|url',
            'github_url' => 'nullable|url',
            'twitter_url' => 'nullable|url',
            'website_url' => 'nullable|url',
            'timezone' => 'required|timezone',
            'hourly_rate' => 'nullable|numeric|min:0',
            'available_for_mentoring' => 'boolean',
        ]);
        
        // Actualizar datos del usuario
        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
        ]);
        
        // Actualizar o crear perfil
        $user->profile()->updateOrCreate(
            ['user_id' => $user->id],
            [
                'bio' => $validated['bio'] ?? null,
                'linkedin_url' => $validated['linkedin_url'] ?? null,
                'github_url' => $validated['github_url'] ?? null,
                'twitter_url' => $validated['twitter_url'] ?? null,
                'website_url' => $validated['website_url'] ?? null,
                'timezone' => $validated['timezone'],
                'hourly_rate' => $validated['hourly_rate'] ?? null,
                'available_for_mentoring' => $validated['available_for_mentoring'] ?? false,
            ]
        );
        
        // Sincronizar especialidades
        if (isset($validated['specialties'])) {
            $user->specialties()->sync($validated['specialties']);
        }
        
        return redirect()->route('mentor.profile')
            ->with('success', 'Perfil actualizado correctamente');
    }
    
    /**
     * Muestra la lista de estudiantes del mentor
     */
    public function students()
    {
        $mentor = Auth::user();
        $mentorId = $mentor->id;

        // 1. Obtener IDs de los cursos activos del mentor
        $mentorCourseIds = Course::where('teacher_id', $mentorId)
            ->where('is_active', true)
            ->pluck('id');

        // 2. Obtener estudiantes que ya han tenido sesiones con el mentor
        // Usamos una consulta directa para facilitar la combinación y evitar problemas con withCount en la unión
        $studentsFromSessions = User::whereHas('roles', function($query) {
                $query->where('name', 'student');
            })
            ->whereHas('menteeSessions', function ($query) use ($mentorId) {
                $query->where('mentor_id', $mentorId);
            })
            ->select('users.*') // Asegurar que seleccionamos todas las columnas de users
            ->distinct();
            // No aplicamos with/withCount aquí todavía, lo haremos después de unir

        // 3. Obtener estudiantes inscritos en los cursos activos del mentor
        $studentsFromEnrollments = collect(); // Inicializar como colección vacía
        if ($mentorCourseIds->isNotEmpty()) {
            $studentsFromEnrollments = User::whereHas('roles', function($query) {
                    $query->where('name', 'student');
                })
                ->whereHas('enrollments', function($enrollmentQuery) use ($mentorCourseIds) {
                    $enrollmentQuery->whereIn('course_id', $mentorCourseIds);
                })
                ->select('users.*') // Asegurar que seleccionamos todas las columnas de users
                ->distinct();
        }

        // 4. Combinar IDs, eliminar duplicados y luego obtener los modelos completos con sus relaciones
        $studentIdsFromSessions = $studentsFromSessions->pluck('id');
        $studentIdsFromEnrollments = $studentsFromEnrollments instanceof \Illuminate\Database\Eloquent\Builder ? $studentsFromEnrollments->pluck('id') : $studentsFromEnrollments->pluck('id');
        
        $allStudentIds = $studentIdsFromSessions->merge($studentIdsFromEnrollments)->unique();

        $students = User::whereIn('id', $allStudentIds)
            ->with(['profile'])
            ->withCount(['mentorshipSessions as upcoming_sessions' => function($query) use ($mentorId) {
                $query->where('mentor_id', $mentorId)
                      ->where('start_time', '>=', now())
                      ->where('status', 'scheduled');
            }])
            ->withCount(['mentorshipSessions as completed_sessions' => function($query) use ($mentorId) {
                $query->where('mentor_id', $mentorId)
                      ->where('status', 'completed');
            }])
            ->orderBy('name') // Opcional: ordenar por nombre
            ->paginate(10);
            
        return view('mentor.students.index', compact('students'));
    }
    
    /**
     * Muestra el perfil de un estudiante
     */
    public function showStudent($id)
    {
        $mentorId = Auth::id();
        
        // Obtener el estudiante si está en los cursos del mentor o ha tenido sesiones con el mentor
        $student = User::where('id', $id)
            ->whereHas('roles', function($query) {
                $query->where('name', 'student');
            })
            ->where(function($query) use ($mentorId) {
                // Estudiantes que han tenido sesiones con el mentor
                $query->whereHas('menteeSessions', function($q) use ($mentorId) {
                    $q->where('mentor_id', $mentorId);
                })
                // O están inscritos en cursos del mentor
                ->orWhereHas('enrollments.course', function($q) use ($mentorId) {
                    $q->where('teacher_id', $mentorId);
                });
            })
            ->with(['profile', 'enrollments.course'])
            ->firstOrFail();
            
        // Obtener las sesiones entre este mentor y el estudiante
        $sessions = MentorshipSession::where('mentor_id', $mentorId)
            ->where('student_id', $id)
            ->orderBy('start_time', 'desc')
            ->paginate(10);
            
        return view('mentor.students.show', compact('student', 'sessions'));
    }
    
    /**
     * Muestra el calendario del mentor
     */
    public function calendar()
    {
        $user = Auth::user();
        
        // Obtener eventos para el calendario
        $events = $user->mentorSessions()
            ->with(['mentee', 'course'])
            ->where('start_time', '>=', now()->subMonths(1))
            ->get()
            ->map(function($session) {
                return [
                    'id' => $session->id,
                    'title' => $session->title . ' - ' . $session->mentee->name,
                    'start' => $session->start_time->toIso8601String(),
                    'end' => $session->start_time->addMinutes($session->duration)->toIso8601String(),
                    'url' => route('mentor.sessions.show', $session->id),
                    'backgroundColor' => $session->status === 'completed' ? '#28a745' : 
                                        ($session->status === 'cancelled' ? '#dc3545' : '#007bff'),
                    'borderColor' => $session->status === 'completed' ? '#218838' : 
                                    ($session->status === 'cancelled' ? '#c82333' : '#0056b3'),
                    'extendedProps' => [
                        'status' => $session->status,
                        'mentee' => $session->mentee->name,
                        'course' => $session->course->title ?? 'Sin curso asociado',
                    ]
                ];
            });
            
        return view('mentor.calendar.index', compact('events'));
    }
    
    /**
     * Muestra los recursos del mentor
     */
    public function resources()
    {
        $resources = Auth::user()->resources()
            ->with(['course'])
            ->latest()
            ->paginate(10);
            
        return view('mentor.resources.index', compact('resources'));
    }
    
    /**
     * Muestra las notificaciones del mentor
     */
    public function notifications()
    {
        $notifications = Auth::user()->notifications()
            ->latest()
            ->paginate(15);
            
        return view('mentor.notifications', compact('notifications'));
    }
    
    /**
     * Muestra los mensajes del mentor
     */
    public function messages()
    {
        $conversations = Auth::user()->conversations()
            ->with(['users', 'messages' => function($query) {
                $query->latest()->first();
            }])
            ->latest('updated_at')
            ->paginate(10);
            
        return view('mentor.messages.index', compact('conversations'));
    }
}
