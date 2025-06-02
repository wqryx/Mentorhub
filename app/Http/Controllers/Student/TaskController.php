<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class TaskController extends Controller
{
    /**
     * Mostrar la lista de tareas del estudiante
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        
        // Consulta base para tareas del estudiante
        $query = Task::where('user_id', $user->id);
        
        // Filtrar por estado
        if ($request->has('status')) {
            if ($request->status === 'pending') {
                $query->where('completed', false);
            } elseif ($request->status === 'completed') {
                $query->where('completed', true);
            }
        }
        
        // Filtrar por fecha
        if ($request->has('date_filter')) {
            if ($request->date_filter === 'today') {
                $query->whereDate('due_date', Carbon::today());
            } elseif ($request->date_filter === 'week') {
                $query->whereBetween('due_date', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
            } elseif ($request->date_filter === 'month') {
                $query->whereBetween('due_date', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()]);
            } elseif ($request->date_filter === 'overdue') {
                $query->where('due_date', '<', Carbon::today())
                      ->where('completed', false);
            }
        }
        
        // Filtrar por curso
        if ($request->has('course_id') && $request->course_id) {
            $query->where('course_id', $request->course_id);
        }
        
        // Ordenar tareas
        if ($request->has('sort')) {
            if ($request->sort === 'due_date') {
                $query->orderBy('due_date', 'asc');
            } elseif ($request->sort === 'priority') {
                $query->orderBy('priority', 'desc');
            } elseif ($request->sort === 'title') {
                $query->orderBy('title', 'asc');
            } elseif ($request->sort === 'created_at') {
                $query->orderBy('created_at', 'desc');
            }
        } else {
            // Ordenamiento predeterminado: primero por fecha de vencimiento, luego por prioridad
            $query->orderBy('due_date', 'asc')
                  ->orderBy('priority', 'desc');
        }
        
        $tasks = $query->paginate(15);
        
        // Obtener cursos del estudiante para el filtro
        $courses = Course::whereHas('students', function($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->pluck('title', 'id');
        
        // Estadísticas para el panel lateral
        $stats = [
            'totalTasks' => Task::where('user_id', $user->id)->count(),
            'pendingTasks' => Task::where('user_id', $user->id)->where('completed', false)->count(),
            'completedTasks' => Task::where('user_id', $user->id)->where('completed', true)->count(),
            'overdueTasks' => Task::where('user_id', $user->id)
                ->where('due_date', '<', Carbon::today())
                ->where('completed', false)
                ->count(),
            'todayTasks' => Task::where('user_id', $user->id)
                ->whereDate('due_date', Carbon::today())
                ->count()
        ];
        
        return view('student.tasks.index', compact('tasks', 'courses', 'stats'));
    }
    
    /**
     * Almacenar una nueva tarea
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'nullable|date',
            'priority' => 'required|in:low,medium,high',
            'course_id' => 'nullable|exists:courses,id',
            'estimated_time' => 'nullable|integer|min:1'
        ]);
        
        $user = Auth::user();
        
        $task = new Task();
        $task->title = $validated['title'];
        $task->description = $validated['description'] ?? null;
        $task->due_date = $validated['due_date'] ? Carbon::parse($validated['due_date']) : null;
        $task->priority = $validated['priority'];
        $task->course_id = $validated['course_id'] ?? null;
        $task->estimated_time = $validated['estimated_time'] ?? null;
        $task->user_id = $user->id;
        $task->completed = false;
        $task->save();
        
        return redirect()->route('student.tasks')
            ->with('success', 'Tarea creada correctamente');
    }
    
    /**
     * Actualizar una tarea existente
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
            'due_date' => 'nullable|date',
            'priority' => 'required|in:low,medium,high',
            'course_id' => 'nullable|exists:courses,id',
            'estimated_time' => 'nullable|integer|min:1'
        ]);
        
        $user = Auth::user();
        $task = Task::where('user_id', $user->id)->findOrFail($id);
        
        $task->title = $validated['title'];
        $task->description = $validated['description'] ?? null;
        $task->due_date = $validated['due_date'] ? Carbon::parse($validated['due_date']) : null;
        $task->priority = $validated['priority'];
        $task->course_id = $validated['course_id'] ?? null;
        $task->estimated_time = $validated['estimated_time'] ?? null;
        $task->save();
        
        return redirect()->route('student.tasks')
            ->with('success', 'Tarea actualizada correctamente');
    }
    
    /**
     * Marcar una tarea como completada o pendiente
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function toggleStatus($id)
    {
        $user = Auth::user();
        $task = Task::where('user_id', $user->id)->findOrFail($id);
        
        $task->completed = !$task->completed;
        $task->completed_at = $task->completed ? now() : null;
        $task->save();
        
        return response()->json([
            'success' => true,
            'completed' => $task->completed,
            'message' => $task->completed ? 'Tarea marcada como completada' : 'Tarea marcada como pendiente'
        ]);
    }
    
    /**
     * Eliminar una tarea
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $user = Auth::user();
        $task = Task::where('user_id', $user->id)->findOrFail($id);
        
        $task->delete();
        
        return redirect()->route('student.tasks')
            ->with('success', 'Tarea eliminada correctamente');
    }
    
    /**
     * Obtener tareas para el calendario o widgets
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getTasks(Request $request)
    {
        $user = Auth::user();
        $limit = $request->input('limit', 5);
        $status = $request->input('status');
        
        $query = Task::where('user_id', $user->id);
        
        if ($status === 'pending') {
            $query->where('completed', false);
        } elseif ($status === 'completed') {
            $query->where('completed', true);
        }
        
        if ($request->has('due_date')) {
            $query->whereDate('due_date', Carbon::parse($request->due_date));
        }
        
        $tasks = $query->orderBy('due_date', 'asc')
            ->orderBy('priority', 'desc')
            ->limit($limit)
            ->get();
            
        return response()->json($tasks);
    }
}
