<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class EventController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin']);
    }
    
    /**
     * Display a listing of the events.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $query = Event::with(['organizer', 'participants']);
        
        // Filtrar por título
        if ($request->has('title') && $request->title) {
            $query->where('title', 'like', '%' . $request->title . '%');
        }
        
        // Filtrar por organizador
        if ($request->has('organizer_id') && $request->organizer_id) {
            $query->where('organizer_id', $request->organizer_id);
        }
        
        // Filtrar por fecha
        if ($request->has('start_date') && $request->start_date) {
            $query->where('start_date', '>=', $request->start_date);
        }
        
        if ($request->has('end_date') && $request->end_date) {
            $query->where('end_date', '<=', $request->end_date);
        }
        
        // Filtrar por estado
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }
        
        // Ordenar
        $orderBy = $request->input('order_by', 'start_date');
        $orderDir = $request->input('order_dir', 'asc');
        $query->orderBy($orderBy, $orderDir);
        
        $events = $query->paginate(10);
        
        // Obtener organizadores para el filtro
        $organizers = User::whereHas('roles', function($q) {
            $q->whereIn('name', ['admin', 'mentor']);
        })->orderBy('name')->get();
        
        return view('admin.events.index', compact('events', 'organizers'));
    }

    /**
     * Show the form for creating a new event.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $organizers = User::whereHas('roles', function($q) {
            $q->whereIn('name', ['admin', 'mentor']);
        })->orderBy('name')->get();
        
        $participants = User::orderBy('name')->get();
        
        return view('admin.events.create', compact('organizers', 'participants'));
    }

    /**
     * Store a newly created event in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'location' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'organizer_id' => 'required|exists:users,id',
            'max_participants' => 'nullable|integer|min:1',
            'status' => 'required|in:scheduled,cancelled,completed',
            'is_online' => 'boolean',
            'meeting_url' => 'nullable|url|required_if:is_online,1',
            'participants' => 'nullable|array',
            'participants.*' => 'exists:users,id',
        ]);
        
        // Crear el evento
        $event = new Event($validated);
        $event->slug = Str::slug($validated['title'] . '-' . now()->format('YmdHis'));
        $event->save();
        
        // Asignar participantes
        if (isset($validated['participants'])) {
            $event->participants()->attach($validated['participants']);
        }
        
        return redirect()->route('admin.events.index')
            ->with('success', 'Evento creado exitosamente.');
    }

    /**
     * Display the specified event.
     *
     * @param  \App\Models\Event  $event
     * @return \Illuminate\View\View
     */
    public function show(Event $event)
    {
        $event->load(['organizer', 'participants']);
        
        return view('admin.events.show', compact('event'));
    }

    /**
     * Show the form for editing the specified event.
     *
     * @param  \App\Models\Event  $event
     * @return \Illuminate\View\View
     */
    public function edit(Event $event)
    {
        $event->load('participants');
        
        $organizers = User::whereHas('roles', function($q) {
            $q->whereIn('name', ['admin', 'mentor']);
        })->orderBy('name')->get();
        
        $participants = User::orderBy('name')->get();
        
        return view('admin.events.edit', compact('event', 'organizers', 'participants'));
    }

    /**
     * Update the specified event in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Event  $event
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Event $event)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'location' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'organizer_id' => 'required|exists:users,id',
            'max_participants' => 'nullable|integer|min:1',
            'status' => 'required|in:scheduled,cancelled,completed',
            'is_online' => 'boolean',
            'meeting_url' => 'nullable|url|required_if:is_online,1',
            'participants' => 'nullable|array',
            'participants.*' => 'exists:users,id',
        ]);
        
        // Actualizar el evento
        $event->update($validated);
        
        // Actualizar participantes
        if (isset($validated['participants'])) {
            $event->participants()->sync($validated['participants']);
        } else {
            $event->participants()->detach();
        }
        
        return redirect()->route('admin.events.index')
            ->with('success', 'Evento actualizado exitosamente.');
    }

    /**
     * Remove the specified event from storage.
     *
     * @param  \App\Models\Event  $event
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Event $event)
    {
        // Verificar si el evento ya ha pasado y está completado
        if ($event->status === 'completed') {
            return redirect()->route('admin.events.index')
                ->with('error', 'No se puede eliminar un evento que ya ha sido completado.');
        }
        
        // Eliminar relaciones con participantes
        $event->participants()->detach();
        
        // Eliminar el evento
        $event->delete();
        
        return redirect()->route('admin.events.index')
            ->with('success', 'Evento eliminado exitosamente.');
    }
}
