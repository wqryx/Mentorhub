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
            ->where('scheduled_at', '>=', now())
            ->orderBy('scheduled_at')
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
        
        return view('dashboard.mentor.index', compact('upcomingSessions', 'pendingRequests', 'stats'));
    }
    
    /**
     * Muestra el perfil del mentor
     */
    public function profile()
    {
        $user = Auth::user();
        $specialties = $user->specialties ?? [];
        
        return view('dashboard.mentor.profile', compact('user', 'specialties'));
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
        $user = Auth::user();
        $students = $user->menteeStudents()
            ->with(['profile'])
            ->withCount(['mentorshipSessions as upcoming_sessions' => function($query) {
                $query->where('scheduled_at', '>=', now())
                      ->where('status', 'scheduled');
            }])
            ->withCount(['mentorshipSessions as completed_sessions' => function($query) {
                $query->where('status', 'completed');
            }])
            ->paginate(10);
            
        return view('dashboard.mentor.students.index', compact('students'));
    }
    
    /**
     * Muestra el perfil de un estudiante
     */
    public function showStudent($id)
    {
        $student = User::with(['profile', 'enrollments.course'])
            ->whereHas('mentors', function($query) {
                $query->where('mentor_id', Auth::id());
            })
            ->findOrFail($id);
            
        $sessions = MentorshipSession::where('mentor_id', Auth::id())
            ->where('mentee_id', $id)
            ->orderBy('scheduled_at', 'desc')
            ->paginate(10);
            
        return view('dashboard.mentor.students.show', compact('student', 'sessions'));
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
            ->where('scheduled_at', '>=', now()->subMonths(1))
            ->get()
            ->map(function($session) {
                return [
                    'id' => $session->id,
                    'title' => $session->title . ' - ' . $session->mentee->name,
                    'start' => $session->scheduled_at->toIso8601String(),
                    'end' => $session->scheduled_at->addMinutes($session->duration)->toIso8601String(),
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
            
        return view('dashboard.mentor.calendar', compact('events'));
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
            
        return view('dashboard.mentor.resources.index', compact('resources'));
    }
    
    /**
     * Muestra las notificaciones del mentor
     */
    public function notifications()
    {
        $notifications = Auth::user()->notifications()
            ->latest()
            ->paginate(15);
            
        return view('dashboard.mentor.notifications', compact('notifications'));
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
            
        return view('dashboard.mentor.messages', compact('conversations'));
    }
}
