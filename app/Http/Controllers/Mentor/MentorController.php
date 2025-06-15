<?php

namespace App\Http\Controllers\Mentor;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Course;
use App\Models\MentorshipSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Support\Facades\DB;

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
        
        // Cargar relaciones necesarias
        $user->load([
            'specialities',
            'experiences',
            'education',
            'profile',
            'mentorSessions' => function($query) {
                return $query->where('status', 'completed');
            },
            'students' => function($query) {
                return $query->whereHas('mentorshipSessions', function($q) {
                    $q->where('status', 'completed')
                      ->where('mentor_id', auth()->id());
                })->distinct();
            },
            'availabilities'
        ]);
        
        // Calcular estadísticas
        $completedSessions = $user->mentorSessions->count();
        $activeStudents = $user->students->count();
        $averageRating = $user->mentorSessions->avg('rating') ?? 0;
        
        // Obtener disponibilidad
        $availability = [];
        $days = ['sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'];
        
        foreach ($days as $index => $day) {
            $dayAvailability = $user->availabilities->firstWhere('day_of_week', $index);
            
            $availability[$index] = [
                'available' => $dayAvailability ? $dayAvailability->is_available : false,
                'start_time' => $dayAvailability ? $dayAvailability->start_time : '09:00',
                'end_time' => $dayAvailability ? $dayAvailability->end_time : '17:00',
                'day_name' => ucfirst($day)
            ];
        }
        
        // Obtener especialidades
        $specialities = $user->specialities;
        $allSpecialities = \App\Models\Speciality::all();
        
        // Obtener experiencia laboral
        $experiences = $user->experiences()->orderBy('start_date', 'desc')->get();
        
        // Obtener educación
        $education = $user->education()->orderBy('start_date', 'desc')->get();
        
        return view('mentor.profile.index', compact(
            'user', 
            'specialities',
            'allSpecialities',
            'experiences',
            'education',
            'completedSessions',
            'activeStudents',
            'averageRating',
            'availability',
            'days'
        ));
    }
    
    /**
     * Actualiza el perfil del mentor
     */
    /**
     * Handle profile photo upload
     */
    public function updateProfilePhoto(Request $request)
    {
        $request->validate([
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        try {
            $user = Auth::user();
            
            // Delete old photo if exists
            if ($user->profile_photo_path) {
                Storage::delete('public/' . $user->profile_photo_path);
            }
            
            // Store new photo
            $path = $request->file('photo')->store('profile-photos', 'public');
            
            // Update user's profile photo path
            $user->profile_photo_path = $path;
            $user->save();
            
            return response()->json([
                'success' => true,
                'photo_url' => asset('storage/' . $path)
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Error uploading profile photo: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al subir la foto de perfil.'
            ], 500);
        }
    }
    
    /**
     * Update mentor availability
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateAvailability(Request $request)
    {
        $request->validate([
            'availability' => 'required|array',
            'availability.*.day_of_week' => 'required|integer|between:0,6',
            'availability.*.is_available' => 'required|boolean',
            'availability.*.start_time' => 'required|date_format:H:i',
            'availability.*.end_time' => 'required|date_format:H:i|after:availability.*.start_time',
            'availability.*.recurring' => 'required|boolean',
        ]);
        
        try {
            DB::beginTransaction();
            
            $user = Auth::user();
            $availabilities = $request->input('availability');
            
            // Convertir el número del día a texto (ej: 0 -> 'monday')
            $daysOfWeek = ['sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'];
            
            // Eliminar disponibilidades existentes
            $user->availabilities()->delete();
            
            // Crear nuevas disponibilidades
            foreach ($availabilities as $availability) {
                if ($availability['is_available']) {
                    $dayName = $daysOfWeek[$availability['day_of_week']] ?? 'monday';
                    
                    $user->availabilities()->create([
                        'day_of_week' => $dayName,
                        'start_time' => $availability['start_time'],
                        'end_time' => $availability['end_time'],
                        'is_available' => true,
                        'is_recurring' => $availability['recurring'] ?? true,
                        'time_zone' => config('app.timezone'),
                        'specific_date' => null, // Para disponibilidad recurrente
                    ]);
                }
            }
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Disponibilidad actualizada correctamente.'
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error updating availability: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar la disponibilidad. Por favor, inténtalo de nuevo.'
            ], 500);
        }
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
            'phone' => 'nullable|string|max:20',
            'location' => 'nullable|string|max:255',
            'specialities' => 'nullable|array',
            'specialities.*' => 'exists:specialities,id',
            'linkedin_url' => 'nullable|url',
            'github_url' => 'nullable|url',
            'twitter_url' => 'nullable|url',
            'website_url' => 'nullable|url',
            'timezone' => 'required|timezone',
            'hourly_rate' => 'nullable|numeric|min:0',
            'available_for_mentoring' => 'boolean',
        ]);
        
        // Iniciar transacción para asegurar la integridad de los datos
        DB::beginTransaction();
        
        try {
            // Actualizar datos básicos del usuario
            $user->update([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'] ?? $user->phone,
                'location' => $validated['location'] ?? $user->location,
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
                    'available_for_mentoring' => $validated['available_for_mentoring'] ?? true,
                ]
            );
            
            // Sincronizar especialidades
            if (isset($validated['specialities'])) {
                $user->specialities()->sync($validated['specialities']);
            }
            
            DB::commit();
            
            return redirect()->route('mentor.profile')
                ->with('success', 'Perfil actualizado exitosamente.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error al actualizar el perfil: ' . $e->getMessage());
            
            return redirect()->back()
                ->with('error', 'Ocurrió un error al actualizar el perfil. Intente nuevamente.');
        }
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
     * Marcar/desmarcar un estudiante como favorito en un curso
     */
    public function favoriteStudent(Request $request, $courseId, $studentId)
    {
        $mentor = auth()->user();
        
        // Verificar que el mentor sea el propietario del curso
        $course = $mentor->courses()->findOrFail($courseId);
        
        // Verificar que el estudiante esté inscrito en el curso
        if (!$course->students()->where('user_id', $studentId)->exists()) {
            return back()->with('error', 'El estudiante no está inscrito en este curso.');
        }
        
        // Alternar el estado de favorito
        $currentStatus = $course->students()->where('user_id', $studentId)->first()->pivot->is_favorite ?? false;
        $course->students()->updateExistingPivot($studentId, ['is_favorite' => !$currentStatus]);
        
        $message = $currentStatus 
            ? 'Estudiante eliminado de favoritos correctamente.' 
            : '¡Estudiante marcado como favorito correctamente!';
            
        return back()->with('success', $message);
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
        $user = Auth::user();
        
        // Get received messages (inbox)
        $receivedMessages = Message::where('recipient_id', $user->id)
            ->with(['sender', 'recipient'])
            ->latest()
            ->paginate(15, ['*'], 'inbox_page');
            
        // Get sent messages
        $sentMessages = Message::where('sender_id', $user->id)
            ->with(['sender', 'recipient'])
            ->latest()
            ->paginate(15, ['*'], 'sent_page');
        
        // Get total unread messages count for the badge
        $unreadCount = Message::where('recipient_id', $user->id)
            ->where('read', false)
            ->count();
            
        return view('mentor.messages.index', compact(
            'receivedMessages', 
            'sentMessages', 
            'unreadCount'
        ));
    }
    
    /**
     * Show the form to create a new message
     */
    public function createMessage()
    {
        $mentees = auth()->user()->menteeStudents()->get();
        return view('mentor.messages.create', compact('mentees'));
    }
    
    /**
     * Send a new message
     */
    public function sendMessage(Request $request)
    {
        $request->validate([
            'recipient_id' => 'required|exists:users,id',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        // Create a new conversation
        $conversation = DB::transaction(function () use ($request) {
            $conversation = Conversation::create([
                'subject' => $request->subject,
                'last_message_at' => now(),
            ]);

            // Attach users to conversation
            $conversation->users()->attach([
                auth()->id(),
                $request->recipient_id
            ]);

            // Create the message
            $message = Message::create([
                'conversation_id' => $conversation->id,
                'sender_id' => auth()->id(),
                'recipient_id' => $request->recipient_id,
                'message' => $request->message,
                'read' => false,
            ]);

            return $conversation;
        });

        return redirect()->route('mentor.messages.show', $conversation->id)
            ->with('success', 'Mensaje enviado correctamente');
    }
    
    /**
     * Show a conversation
     */
    public function showConversation(Conversation $conversation)
    {
        // Verify the user is part of the conversation
        if (!$conversation->users->contains(auth()->id())) {
            abort(403);
        }

        // Mark messages as read
        $conversation->messages()
            ->where('recipient_id', auth()->id())
            ->where('read', false)
            ->update(['read' => true]);

        // Get the other user in the conversation
        $otherUser = $conversation->users->where('id', '!=', auth()->id())->first();

        return view('mentor.messages.show', compact('conversation', 'otherUser'));
    }
    
    /**
     * Reply to a conversation
     */
    public function replyToConversation(Request $request, Conversation $conversation)
    {
        // Verify the user is part of the conversation
        if (!$conversation->users->contains(auth()->id())) {
            abort(403);
        }

        $request->validate([
            'message' => 'required|string',
        ]);

        // Get the other user in the conversation
        $recipient = $conversation->users->where('id', '!=', auth()->id())->first();

        // Create the message
        $message = Message::create([
            'conversation_id' => $conversation->id,
            'sender_id' => auth()->id(),
            'recipient_id' => $recipient->id,
            'message' => $request->message,
            'read' => false,
        ]);

        // Update conversation's last message timestamp
        $conversation->update([
            'last_message_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Mensaje enviado correctamente');
    }
}
