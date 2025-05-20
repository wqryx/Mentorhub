<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Module;
use App\Models\Task;
use App\Models\Grade;
use App\Models\Resource;
use Carbon\Carbon;

class StudentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function dashboard()
    {
        $user = auth()->user();
        
        // Obtener módulos del estudiante
        $modules = Module::whereHas('students', function($query) use ($user) {
            $query->where('user_id', $user->id);
        })->get();

        // Obtener tareas pendientes
        $pendingTasks = Task::where('user_id', $user->id)
            ->where('status', 'pending')
            ->whereDate('due_date', '>=', now())
            ->with('module')
            ->get();

        // Obtener próximos eventos
        $events = [];
        foreach ($modules as $module) {
            $events = array_merge($events, $module->events->toArray());
        }
        usort($events, function($a, $b) {
            return strtotime($a['date']) - strtotime($b['date']);
        });

        // Obtener notas
        $grades = Grade::where('user_id', $user->id)
            ->with('module')
            ->get();

        // Calcular media general
        $average = $grades->avg('grade') ?? 0;

        // Obtener recursos
        $resources = Resource::whereHas('modules', function($query) use ($modules) {
            $query->whereIn('module_id', $modules->pluck('id'));
        })->get();

        return view('dashboard.student', compact(
            'user',
            'modules',
            'pendingTasks',
            'events',
            'grades',
            'average',
            'resources'
        ));
    }

    public function modules()
    {
        $user = auth()->user();
        $modules = Module::whereHas('students', function($query) use ($user) {
            $query->where('user_id', $user->id);
        })->with('tasks', 'grades')->get();

        return view('student.modules', compact('modules'));
    }

    public function calendar()
    {
        $user = auth()->user();
        $modules = Module::whereHas('students', function($query) use ($user) {
            $query->where('user_id', $user->id);
        })->with('events')->get();

        return view('student.calendar', compact('modules'));
    }

    public function tasks()
    {
        $user = auth()->user();
        $tasks = Task::where('user_id', $user->id)
            ->with('module')
            ->orderBy('due_date')
            ->get();

        return view('student.tasks', compact('tasks'));
    }

    public function grades()
    {
        $user = auth()->user();
        $grades = Grade::where('user_id', $user->id)
            ->with('module')
            ->get();

        return view('student.grades', compact('grades'));
    }

    public function messages()
    {
        $user = auth()->user();
        $messages = $user->messages()->latest()->get();

        return view('student.messages', compact('messages'));
    }

    public function resources()
    {
        $user = auth()->user();
        $modules = Module::whereHas('students', function($query) use ($user) {
            $query->where('user_id', $user->id);
        })->with('resources')->get();

        return view('student.resources', compact('modules'));
    }
}
