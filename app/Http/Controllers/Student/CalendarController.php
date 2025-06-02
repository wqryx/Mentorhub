<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\MentorshipSession;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class CalendarController extends Controller
{
    /**
     * Mostrar el calendario del estudiante
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $user = Auth::user();
        
        // Obtener eventos del estudiante
        $events = Event::whereHas('attendees', function($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->get()
            ->map(function($event) {
                return [
                    'id' => 'event_' . $event->id,
                    'title' => $event->title,
                    'start' => $event->start_date->format('Y-m-d H:i:s'),
                    'end' => $event->end_date->format('Y-m-d H:i:s'),
                    'description' => $event->description,
                    'location' => $event->location,
                    'color' => '#4e73df', // Azul para eventos
                    'type' => 'event'
                ];
            });
            
        // Obtener sesiones de mentoría
        $mentoringSessions = MentorshipSession::where('mentee_id', $user->id)
            ->where('status', 'confirmed')
            ->get()
            ->map(function($session) {
                return [
                    'id' => 'session_' . $session->id,
                    'title' => 'Mentoría: ' . $session->title,
                    'start' => $session->scheduled_at->format('Y-m-d H:i:s'),
                    'end' => $session->scheduled_at->addMinutes($session->duration)->format('Y-m-d H:i:s'),
                    'description' => $session->description,
                    'location' => $session->meeting_url ?? 'Por definir',
                    'color' => '#1cc88a', // Verde para sesiones de mentoría
                    'type' => 'mentoring'
                ];
            });
            
        // Obtener tareas con fecha límite
        $tasks = Task::where('user_id', $user->id)
            ->whereNotNull('due_date')
            ->get()
            ->map(function($task) {
                return [
                    'id' => 'task_' . $task->id,
                    'title' => 'Tarea: ' . $task->title,
                    'start' => $task->due_date->format('Y-m-d H:i:s'),
                    'end' => $task->due_date->format('Y-m-d H:i:s'),
                    'description' => $task->description,
                    'color' => $task->completed ? '#36b9cc' : '#f6c23e', // Azul claro si está completada, amarillo si está pendiente
                    'type' => 'task',
                    'completed' => $task->completed
                ];
            });
            
        // Combinar todos los eventos
        $calendarEvents = $events->concat($mentoringSessions)->concat($tasks);
        
        // Estadísticas para el panel lateral
        $stats = [
            'upcomingEvents' => Event::whereHas('attendees', function($query) use ($user) {
                    $query->where('user_id', $user->id);
                })
                ->where('start_date', '>=', now())
                ->count(),
                
            'upcomingSessions' => MentorshipSession::where('mentee_id', $user->id)
                ->where('status', 'confirmed')
                ->where('scheduled_at', '>=', now())
                ->count(),
                
            'pendingTasks' => Task::where('user_id', $user->id)
                ->where('completed', false)
                ->whereNotNull('due_date')
                ->count(),
                
            'todayEvents' => Event::whereHas('attendees', function($query) use ($user) {
                    $query->where('user_id', $user->id);
                })
                ->whereDate('start_date', now()->toDateString())
                ->count(),
                
            'todaySessions' => MentorshipSession::where('mentee_id', $user->id)
                ->where('status', 'confirmed')
                ->whereDate('scheduled_at', now()->toDateString())
                ->count()
        ];
        
        return view('student.calendar.index', compact('calendarEvents', 'stats'));
    }
    
    /**
     * Crear un nuevo evento personal
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'location' => 'nullable|string|max:255',
            'is_all_day' => 'boolean',
            'reminder' => 'nullable|integer|min:5|max:1440'
        ]);
        
        $user = Auth::user();
        
        // Crear el evento
        $event = new Event();
        $event->title = $validated['title'];
        $event->description = $validated['description'] ?? null;
        $event->start_date = Carbon::parse($validated['start_date']);
        $event->end_date = Carbon::parse($validated['end_date']);
        $event->location = $validated['location'] ?? null;
        $event->is_all_day = $request->has('is_all_day');
        $event->reminder_minutes = $validated['reminder'] ?? null;
        $event->created_by = $user->id;
        $event->save();
        
        // Asociar el evento al usuario
        $event->attendees()->attach($user->id);
        
        return redirect()->route('student.calendar')
            ->with('success', 'Evento creado correctamente');
    }
    
    /**
     * Actualizar un evento existente
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'location' => 'nullable|string|max:255',
            'is_all_day' => 'boolean',
            'reminder' => 'nullable|integer|min:5|max:1440'
        ]);
        
        $user = Auth::user();
        $event = Event::findOrFail($id);
        
        // Verificar que el usuario tenga permiso para editar el evento
        if ($event->created_by !== $user->id && !$event->attendees->contains($user->id)) {
            return redirect()->route('student.calendar')
                ->with('error', 'No tienes permiso para editar este evento');
        }
        
        // Actualizar el evento
        $event->title = $validated['title'];
        $event->description = $validated['description'] ?? null;
        $event->start_date = Carbon::parse($validated['start_date']);
        $event->end_date = Carbon::parse($validated['end_date']);
        $event->location = $validated['location'] ?? null;
        $event->is_all_day = $request->has('is_all_day');
        $event->reminder_minutes = $validated['reminder'] ?? null;
        $event->save();
        
        return redirect()->route('student.calendar')
            ->with('success', 'Evento actualizado correctamente');
    }
    
    /**
     * Eliminar un evento
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $user = Auth::user();
        $event = Event::findOrFail($id);
        
        // Verificar que el usuario tenga permiso para eliminar el evento
        if ($event->created_by !== $user->id) {
            return redirect()->route('student.calendar')
                ->with('error', 'No tienes permiso para eliminar este evento');
        }
        
        // Eliminar el evento
        $event->attendees()->detach();
        $event->delete();
        
        return redirect()->route('student.calendar')
            ->with('success', 'Evento eliminado correctamente');
    }
    
    /**
     * Obtener eventos para el calendario en formato JSON
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getEvents(Request $request)
    {
        $user = Auth::user();
        $start = $request->input('start');
        $end = $request->input('end');
        
        // Obtener eventos del estudiante
        $events = Event::whereHas('attendees', function($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->when($start, function($query) use ($start) {
                return $query->where('start_date', '>=', Carbon::parse($start));
            })
            ->when($end, function($query) use ($end) {
                return $query->where('end_date', '<=', Carbon::parse($end));
            })
            ->get()
            ->map(function($event) {
                return [
                    'id' => 'event_' . $event->id,
                    'title' => $event->title,
                    'start' => $event->start_date->format('Y-m-d H:i:s'),
                    'end' => $event->end_date->format('Y-m-d H:i:s'),
                    'description' => $event->description,
                    'location' => $event->location,
                    'color' => '#4e73df', // Azul para eventos
                    'type' => 'event'
                ];
            });
            
        // Obtener sesiones de mentoría
        $mentoringSessions = MentorshipSession::where('mentee_id', $user->id)
            ->where('status', 'confirmed')
            ->when($start, function($query) use ($start) {
                return $query->where('scheduled_at', '>=', Carbon::parse($start));
            })
            ->when($end, function($query) use ($end) {
                return $query->where('scheduled_at', '<=', Carbon::parse($end));
            })
            ->get()
            ->map(function($session) {
                return [
                    'id' => 'session_' . $session->id,
                    'title' => 'Mentoría: ' . $session->title,
                    'start' => $session->scheduled_at->format('Y-m-d H:i:s'),
                    'end' => $session->scheduled_at->addMinutes($session->duration)->format('Y-m-d H:i:s'),
                    'description' => $session->description,
                    'location' => $session->meeting_url ?? 'Por definir',
                    'color' => '#1cc88a', // Verde para sesiones de mentoría
                    'type' => 'mentoring'
                ];
            });
            
        // Obtener tareas con fecha límite
        $tasks = Task::where('user_id', $user->id)
            ->whereNotNull('due_date')
            ->when($start, function($query) use ($start) {
                return $query->where('due_date', '>=', Carbon::parse($start));
            })
            ->when($end, function($query) use ($end) {
                return $query->where('due_date', '<=', Carbon::parse($end));
            })
            ->get()
            ->map(function($task) {
                return [
                    'id' => 'task_' . $task->id,
                    'title' => 'Tarea: ' . $task->title,
                    'start' => $task->due_date->format('Y-m-d H:i:s'),
                    'end' => $task->due_date->format('Y-m-d H:i:s'),
                    'description' => $task->description,
                    'color' => $task->completed ? '#36b9cc' : '#f6c23e', // Azul claro si está completada, amarillo si está pendiente
                    'type' => 'task',
                    'completed' => $task->completed
                ];
            });
            
        // Combinar todos los eventos
        $calendarEvents = $events->concat($mentoringSessions)->concat($tasks);
        
        return response()->json($calendarEvents);
    }
}
