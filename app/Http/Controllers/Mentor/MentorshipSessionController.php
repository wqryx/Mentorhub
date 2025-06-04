<?php

namespace App\Http\Controllers\Mentor;

use App\Http\Controllers\Controller;
use App\Models\MentorshipSession;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class MentorshipSessionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    /**
     * Display a listing of the mentorship sessions.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $user = Auth::user();
        
        // Obtener sesiones próximas (sin paginación para mostrar todas)
        $upcomingSessions = $user->mentorSessions()
            ->with([
                'student', 
                'student.profile',
                'course'
            ])
            ->where('start_time', '>=', now())
            ->where('status', 'scheduled')
            ->orderBy('start_time')
            ->get();
            
        // Obtener sesiones pasadas con paginación
        $pastSessions = $user->mentorSessions()
            ->with([
                'student', 
                'student.profile',
                'course',
                'review'
            ])
            ->where('start_time', '<', now())
            ->orderBy('start_time', 'desc')
            ->paginate(10);
            
        // Obtener solicitudes pendientes
        $pendingRequests = $user->mentorSessions()
            ->with([
                'student', 
                'student.profile',
                'course'
            ])
            ->where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->get();
        
        // Usar las sesiones pasadas paginadas como mentorías principales
        $mentorias = $pastSessions;
        
        return view('mentor.mentorias.index', compact(
            'upcomingSessions',
            'pastSessions',
            'pendingRequests',
            'mentorias'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    /**
     * Show the form for creating a new mentorship session.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $mentorId = Auth::id();

        // 1. Obtener IDs de los cursos activos del mentor
        $mentorCourseIds = \App\Models\Course::where('teacher_id', $mentorId)
            ->where('is_active', true)
            ->pluck('id');

        // 2. Obtener estudiantes que ya han tenido sesiones con el mentor
        $studentsFromSessions = User::whereHas('roles', function($query) {
                $query->where('name', 'student');
            })
            ->whereIn('id', function($query) use ($mentorId) {
                $query->select('student_id')
                      ->from('mentor_sessions')
                      ->where('mentor_id', $mentorId);
            })
            ->with('profile')
            ->get();

        // 3. Obtener estudiantes inscritos en los cursos activos del mentor
        $studentsFromEnrollments = collect(); // Inicializar como colección vacía
        if ($mentorCourseIds->isNotEmpty()) {
            $studentsFromEnrollments = User::whereHas('roles', function($query) {
                    $query->where('name', 'student');
                })
                ->whereHas('enrollments', function($enrollmentQuery) use ($mentorCourseIds) {
                    $enrollmentQuery->whereIn('course_id', $mentorCourseIds);
                })
                ->with('profile')
                ->get();
        }

        // 4. Combinar, eliminar duplicados y formatear para el desplegable
        $allPotentialMentees = $studentsFromSessions->merge($studentsFromEnrollments)->unique('id');

        $mentees = $allPotentialMentees->mapWithKeys(function($user) {
            // Intentar obtener el nombre de usuario del perfil, si no, el email, y como último recurso un texto genérico
            $identifier = $user->profile->username ?? $user->email ?? 'ID: ' . $user->id;
            return [$user->id => $user->name . ' (' . $identifier . ')'];
        });

        // Obtener cursos activos del mentor
        $courses = \App\Models\Course::where('teacher_id', Auth::id())
            ->where('is_active', true)
            ->pluck('name', 'id')
            ->prepend('-- Selecciona un curso (opcional) --', '');
            
        return view('mentor.mentorias.create', compact('mentees', 'courses'));
    }

    /**
     * Store a newly created resource in storage.
     */
    /**
     * Store a newly created mentorship session in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:users,id',
            'course_id' => 'required|exists:courses,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_time' => 'required|date|after:now',
            'duration_minutes' => 'required|integer|min:15|max:240',
            'meeting_link' => 'nullable|url',
            'type' => 'required|in:one_time,recurring',
            'format' => 'required|in:video_call,phone_call,in_person',
            'student_goals' => 'nullable|string',
            'mentor_notes' => 'nullable|string',
        ]);
        
        // Set default values
        $validated['mentor_id'] = Auth::id();
        $validated['status'] = 'scheduled';
        
        // Asegurarse de que duration_minutes sea un entero
        $durationMinutes = (int)$validated['duration_minutes'];
        $validated['end_time'] = Carbon::parse($validated['start_time'])
            ->addMinutes($durationMinutes);
        
        // Create the session
        $session = MentorshipSession::create($validated);
        
        // Notify the student about the new session
        // $session->student->notify(new NewMentoringSession($session));
        
        return redirect()->route('mentor.sessions.index')
            ->with('success', 'Sesión de mentoría programada correctamente.');
    }

    /**
     * Display the specified resource.
     */
    /**
     * Display the specified mentorship session.
     *
     * @param  string  $id
     * @return \Illuminate\View\View
     */
    public function show(string $id)
    {
        $session = MentorshipSession::with([
                'student', 
                'student.profile',
                'course', 
                'review'
            ])
            ->where('mentor_id', Auth::id())
            ->findOrFail($id);
            
        return view('mentor.mentorias.show', compact('session'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    /**
     * Show the form for editing the specified mentorship session.
     *
     * @param  string  $id
     * @return \Illuminate\View\View
     */
    public function edit(string $id)
    {
        $session = MentorshipSession::where('mentor_id', Auth::id())
            ->findOrFail($id);
            
        $students = User::whereHas('roles', function($query) {
                $query->where('name', 'student');
            })
            ->whereIn('id', function($query) {
                $query->select('student_id')
                      ->from('mentor_sessions')
                      ->where('mentor_id', Auth::id());
            })
            ->pluck('name', 'id');
            
        return view('mentor.mentorias.edit', compact('session', 'students'));
    }

    /**
     * Update the specified resource in storage.
     */
    /**
     * Update the specified mentorship session in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, string $id)
    {
        $session = MentorshipSession::where('mentor_id', Auth::id())
            ->findOrFail($id);
            
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_time' => 'required|date|after:now',
            'duration_minutes' => 'required|integer|min:15|max:240',
            'meeting_link' => 'nullable|url',
            'status' => 'required|in:scheduled,completed,cancelled',
            'type' => 'required|in:one_time,recurring',
            'format' => 'required|in:video_call,phone_call,in_person',
            'student_goals' => 'nullable|string',
            'mentor_notes' => 'nullable|string',
            'outcome_summary' => 'nullable|string',
            'cancellation_reason' => 'required_if:status,cancelled|string|nullable',
        ]);
        
        // Update end time based on start time and duration
        $validated['end_time'] = Carbon::parse($validated['start_time'])
            ->addMinutes($validated['duration_minutes']);
            
        // If session is being cancelled, set cancelled_at timestamp
        if ($validated['status'] === 'cancelled' && $session->status !== 'cancelled') {
            $validated['cancelled_at'] = now();
        }
        
        // If session is being marked as completed, set completed_at timestamp
        if ($validated['status'] === 'completed' && $session->status !== 'completed') {
            $validated['completed_at'] = now();
        }
        
        $session->update($validated);
        
        // Notify the student about session updates
        // $session->student->notify(new MentoringSessionUpdated($session));
        
        return redirect()->route('mentor.sessions.show', $session->id)
            ->with('success', 'Sesión de mentoría actualizada correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    /**
     * Remove the specified mentorship session from storage.
     *
     * @param  string  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(string $id)
    {
        $session = MentorshipSession::where('mentor_id', Auth::id())
            ->findOrFail($id);
            
        // Solo permitir cancelar sesiones futuras
        if ($session->start_time > now()) {
            $session->update(['status' => 'cancelled']);
            
            // Notificar al estudiante sobre la cancelación
            // $session->mentee->notify(new MentoringSessionCancelled($session));
            
            return redirect()->route('mentor.sessions.index')
                ->with('success', 'Sesión de mentoría cancelada correctamente.');
        }
        
        return redirect()->back()
            ->with('error', 'No se puede cancelar una sesión que ya ha ocurrido.');
    }
    
    /**
     * Handle session status updates.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateStatus(Request $request, $id)
    {
        $session = MentorshipSession::where('mentor_id', Auth::id())
            ->findOrFail($id);
            
        $validated = $request->validate([
            'status' => 'required|in:confirmed,completed,cancelled',
            'notes' => 'nullable|string',
        ]);
        
        $session->update($validated);
        
        // Notificar al estudiante sobre el cambio de estado
        // $session->mentee->notify(new MentoringSessionStatusUpdated($session));
        
        return response()->json([
            'success' => true,
            'message' => 'Estado de la sesión actualizado correctamente.',
            'session' => $session->fresh(['mentee', 'course'])
        ]);
    }
    
    /**
     * Handle mentorship request responses.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function respondToRequest(Request $request, $id)
    {
        $session = MentorshipSession::where('mentor_id', Auth::id())
            ->findOrFail($id);
            
        $validated = $request->validate([
            'status' => 'required|in:confirmed,rejected',
            'message' => 'nullable|string',
            'proposed_time' => 'required_if:status,rejected|date|after:now',
        ]);
        
        if ($validated['status'] === 'confirmed') {
            $session->update([
                'status' => 'scheduled',
                'mentor_notes' => $validated['message'] ?? null
            ]);
            
            // Notificar al estudiante sobre la confirmación
            // $session->mentee->notify(new MentoringSessionConfirmed($session));
        } else {
            // Crear una nueva propuesta de horario
            $session->update([
                'status' => 'reschedule_requested',
                'proposed_time' => $validated['proposed_time'],
                'mentor_notes' => $validated['message'] ?? null
            ]);
            
            // Notificar al estudiante sobre la solicitud de reprogramación
            // $session->mentee->notify(new MentoringRescheduleRequested($session));
        }
        
        return response()->json([
            'success' => true,
            'message' => $validated['status'] === 'confirmed' 
                ? 'Solicitud de mentoría confirmada correctamente.' 
                : 'Se ha solicitado reprogramar la sesión.',
            'session' => $session->fresh(['mentee', 'course'])
        ]);
    }
    
    /**
     * Añade una reseña a una sesión completada.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function addReview(Request $request, $id)
    {
        $session = MentorshipSession::where('mentor_id', Auth::id())
            ->where('status', 'completed')
            ->findOrFail($id);
            
        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|max:1000',
        ]);
        
        // Verificar que no exista ya una reseña del mentor para esta sesión
        if ($session->reviews()->where('author_id', Auth::id())->exists()) {
            return redirect()->back()
                ->with('error', 'Ya has enviado una reseña para esta sesión');
        }
        
        $review = $session->reviews()->create([
            'author_id' => Auth::id(),
            'target_id' => $session->mentee_id,
            'rating' => $validated['rating'],
            'comment' => $validated['comment'],
            'is_mentor_review' => true
        ]);
        
        // Notificar al estudiante sobre la nueva reseña
        // $session->mentee->notify(new NewMentoringReview($review));
        
        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Reseña enviada correctamente',
                'review' => $review->load('author')
            ]);
        }
        
        return redirect()->back()
            ->with('success', 'Reseña enviada correctamente');
    }
    
    /**
     * Start a mentoring session.
     *
     * @param  string  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function start($id)
    {
        $session = MentorshipSession::where('mentor_id', Auth::id())
            ->where('status', 'scheduled')
            ->findOrFail($id);
            
        // Verificar que la sesión esté programada para comenzar pronto
        if ($session->start_time > now()->addMinutes(15) || $session->start_time < now()->subMinutes(30)) {
            return response()->json([
                'success' => false,
                'message' => 'La sesión no puede iniciarse en este momento. Debe estar dentro del rango de tiempo permitido.'
            ], 400);
        }
        
        // Actualizar el estado de la sesión
        $session->update([
            'status' => 'in_progress',
            'started_at' => now()
        ]);
        
        // Notificar al estudiante que la sesión ha comenzado
        // $session->mentee->notify(new MentoringSessionStarted($session));
        
        return response()->json([
            'success' => true,
            'message' => 'Sesión iniciada correctamente',
            'session' => $session->fresh(['mentee', 'course']),
            'redirect_url' => $session->meeting_url
        ]);
    }
}
