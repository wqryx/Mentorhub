<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\Module;
use App\Models\Notification;
use App\Models\Resource;
use App\Models\RecordedClass;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        
        // Mensajes recientes (como receptor o emisor)
        $messages = Message::where(function ($query) use ($user) {
            $query->where('receiver_id', $user->id)
                  ->orWhere('sender_id', $user->id);
        })
        ->with(['sender', 'receiver', 'module'])
        ->orderBy('created_at', 'desc')
        ->limit(5)
        ->get();

        // Notificaciones
        $notifications = $user->notifications()->latest()->take(5)->get();

        // Módulos del usuario
        $modules = $user->modules()->with(['teacher', 'resources'])->get();

        // Recursos recientes
        $resources = Resource::whereHas('module', function ($query) use ($user) {
            $query->whereHas('students', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            });
        })->latest()->take(5)->get();

        // Clases grabadas
        $recorded_classes = RecordedClass::whereHas('module', function ($query) use ($user) {
            $query->whereHas('students', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            });
        })->latest()->take(5)->get();

        // Calcular el promedio general
        $grades = $user->grades()->pluck('grade')->toArray();
        $average = !empty($grades) ? array_sum($grades) / count($grades) : 0;

        // Calendario
        $calendar = $this->generateCalendar();

        return view('dashboard.student', compact(
            'user', 'messages', 'notifications', 'modules', 
            'resources', 'recorded_classes', 'calendar', 'average'
        ));
    }

    private function generateCalendar()
    {
        $calendar = [];
        $today = now();
        $firstDay = $today->copy()->startOfMonth();
        $lastDay = $today->copy()->endOfMonth();

        // Añadir días anteriores al primer día de la semana
        $startDay = clone $firstDay;
        while ($startDay->dayOfWeek !== 0) { // 0 es Domingo
            $calendar[] = [
                'number' => '',
                'active' => false,
                'has_event' => false,
                'events' => []
            ];
            $startDay->subDay();
        }

        // Añadir días del mes
        while ($firstDay->lte($lastDay)) {
            $events = [];
            
            // Aquí iría la lógica para obtener eventos del día
            // Por ejemplo: clases, exámenes, entregas, etc.
            
            $calendar[] = [
                'number' => $firstDay->day,
                'active' => $firstDay->isToday(),
                'has_event' => !empty($events),
                'events' => $events
            ];
            
            $firstDay->addDay();
        }

        // Añadir días posteriores hasta completar la semana
        while ($firstDay->dayOfWeek !== 6) { // 6 es Sábado
            $calendar[] = [
                'number' => '',
                'active' => false,
                'has_event' => false,
                'events' => []
            ];
            $firstDay->addDay();
        }

        return $calendar;
    }
}
