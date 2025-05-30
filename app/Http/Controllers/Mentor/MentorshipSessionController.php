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
        
        $upcomingSessions = $user->mentorSessions()
            ->with(['mentee', 'course'])
            ->where('scheduled_at', '>=', now())
            ->orderBy('scheduled_at')
            ->get();
            
        $pastSessions = $user->mentorSessions()
            ->with(['mentee', 'course'])
            ->where('scheduled_at', '<', now())
            ->orderBy('scheduled_at', 'desc')
            ->paginate(10);
            
        $pendingRequests = $user->mentorRequests()
            ->where('status', 'pending')
            ->with(['mentee', 'course'])
            ->orderBy('created_at', 'desc')
            ->get();
            
        return view('dashboard.mentor.mentorias', compact(
            'upcomingSessions',
            'pastSessions',
            'pendingRequests'
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
        $mentees = User::whereHas('roles', function($query) {
                $query->where('name', 'student');
            })
            ->where('mentor_id', Auth::id())
            ->pluck('name', 'id');
            
        return view('dashboard.mentor.sessions.create', compact('mentees'));
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
            'mentee_id' => 'required|exists:users,id',
            'course_id' => 'required|exists:courses,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'scheduled_at' => 'required|date|after:now',
            'duration' => 'required|integer|min:15|max:240',
            'meeting_url' => 'nullable|url',
        ]);
        
        $validated['mentor_id'] = Auth::id();
        $validated['status'] = 'scheduled';
        
        $session = MentorshipSession::create($validated);
        
        // Notificar al estudiante sobre la nueva sesión
        // $session->mentee->notify(new NewMentoringSession($session));
        
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
        $session = MentorshipSession::with(['mentee', 'course', 'reviews'])
            ->where('mentor_id', Auth::id())
            ->findOrFail($id);
            
        return view('dashboard.mentor.sessions.show', compact('session'));
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
            
        $mentees = User::whereHas('roles', function($query) {
                $query->where('name', 'student');
            })
            ->where('mentor_id', Auth::id())
            ->pluck('name', 'id');
            
        return view('dashboard.mentor.sessions.edit', compact('session', 'mentees'));
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
            'scheduled_at' => 'required|date|after:now',
            'duration' => 'required|integer|min:15|max:240',
            'meeting_url' => 'nullable|url',
            'status' => 'required|in:scheduled,completed,cancelled',
            'notes' => 'nullable|string',
        ]);
        
        $session->update($validated);
        
        // Notificar al estudiante sobre cambios en la sesión
        // $session->mentee->notify(new MentoringSessionUpdated($session));
        
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
        if ($session->scheduled_at > now()) {
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
}
