<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Carbon\Carbon;

class CalendarController extends Controller
{
    /**
     * Sincronizar calendario con Google Calendar
     */
    public function sync(Request $request)
    {
        $user = Auth::user();
        
        // Verificar si ya existe un token de sincronización
        if ($user->calendar_token) {
            return response()->json([
                'success' => true,
                'message' => 'Ya tienes sincronización activa con Google Calendar'
            ]);
        }

        // Generar token único
        $token = Str::random(32);
        
        // Guardar el token
        $user->update([
            'calendar_token' => $token,
            'calendar_token_expires_at' => now()->addDays(30)
        ]);

        // Generar URL de sincronización
        $syncUrl = route('calendar.ical', ['token' => $token]);

        return response()->json([
            'success' => true,
            'message' => 'Sincronización activada exitosamente',
            'sync_url' => $syncUrl
        ]);
    }

    /**
     * Generar archivo iCalendar para sincronización
     */
    public function ical($token)
    {
        // Verificar token
        $user = User::where('calendar_token', $token)
            ->where('calendar_token_expires_at', '>', now())
            ->firstOrFail();

        // Obtener eventos y tareas del usuario
        $events = $user->events()->with('module')->get();
        $tasks = $user->tasks()->where('status', '!=', 'completed')->get();

        // Crear archivo iCalendar
        $ical = "BEGIN:VCALENDAR\n";
        $ical .= "VERSION:2.0\n";
        $ical .= "PRODID:-//MentorHub//Calendar//EN\n";

        // Agregar eventos
        foreach ($events as $event) {
            $ical .= "BEGIN:VEVENT\n";
            $ical .= "UID:{$event->id}@mentorhub\n";
            $ical .= "DTSTAMP:" . $event->created_at->format('Ymd\THis\Z') . "\n";
            $ical .= "DTSTART:" . $event->start->format('Ymd\THis\Z') . "\n";
            $ical .= "DTEND:" . $event->end->format('Ymd\THis\Z') . "\n";
            $ical .= "SUMMARY:" . $event->title . "\n";
            $ical .= "DESCRIPTION:" . $event->description . "\n";
            $ical .= "LOCATION:" . $event->location . "\n";
            $ical .= "END:VEVENT\n";
        }

        // Agregar tareas como eventos
        foreach ($tasks as $task) {
            $ical .= "BEGIN:VEVENT\n";
            $ical .= "UID:task_{$task->id}@mentorhub\n";
            $ical .= "DTSTAMP:" . $task->created_at->format('Ymd\THis\Z') . "\n";
            $ical .= "DTSTART:" . $task->due_date->format('Ymd\THis\Z') . "\n";
            $ical .= "SUMMARY:Tarea: " . $task->title . "\n";
            $ical .= "DESCRIPTION:" . $task->description . "\n";
            $ical .= "END:VEVENT\n";
        }

        $ical .= "END:VCALENDAR\n";

        // Configurar headers para descarga
        $headers = [
            'Content-Type' => 'text/calendar; charset=utf-8',
            'Content-Disposition' => 'attachment; filename="mentorhub-calendar.ics"',
            'Cache-Control' => 'public, max-age=3600',
        ];

        return response($ical, 200, $headers);
    }
}
