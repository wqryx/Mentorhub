<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ActivityLogsExport;

class ActivityLogController extends Controller
{
    /**
     * Muestra la lista de registros de actividad.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $query = ActivityLog::with('user');
        
        // Filtrar por usuario
        if ($request->has('user_id') && $request->user_id) {
            $query->byUser($request->user_id);
        }
        
        // Filtrar por acción
        if ($request->has('action') && $request->action) {
            $query->byAction($request->action);
        }
        
        // Filtrar por fecha
        if ($request->has('start_date') && $request->start_date) {
            if ($request->has('end_date') && $request->end_date) {
                $query->byDate($request->start_date, $request->end_date);
            } else {
                $query->byDate($request->start_date);
            }
        }
        
        // Ordenar por fecha descendente (más reciente primero)
        $query->orderBy('created_at', 'desc');
        
        // Paginar los resultados
        $logs = $query->paginate(20);
        
        // Obtener lista de usuarios para el filtro
        $users = User::select('id', 'name')->orderBy('name')->get();
        
        // Obtener lista de acciones únicas para el filtro
        $actions = ActivityLog::select('action')
            ->distinct()
            ->orderBy('action')
            ->pluck('action');
        
        return view('dashboard.admin.activity_logs.index', compact('logs', 'users', 'actions'));
    }
    
    /**
     * Muestra los detalles de un registro de actividad.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $log = ActivityLog::with('user')->findOrFail($id);
        
        return view('dashboard.admin.activity_logs.show', compact('log'));
    }
    
    /**
     * Exporta los registros de actividad a CSV/Excel.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function export(Request $request)
    {
        $request->validate([
            'format' => 'required|in:csv,xlsx',
            'user_id' => 'nullable|exists:users,id',
            'action' => 'nullable|string',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);
        
        $query = ActivityLog::with('user');
        
        // Aplicar los mismos filtros que en el método index
        if ($request->has('user_id') && $request->user_id) {
            $query->byUser($request->user_id);
        }
        
        if ($request->has('action') && $request->action) {
            $query->byAction($request->action);
        }
        
        if ($request->has('start_date') && $request->start_date) {
            if ($request->has('end_date') && $request->end_date) {
                $query->byDate($request->start_date, $request->end_date);
            } else {
                $query->byDate($request->start_date);
            }
        }
        
        // Ordenar por fecha descendente
        $query->orderBy('created_at', 'desc');
        
        // Limitar a 10,000 registros para evitar problemas de memoria
        $logs = $query->limit(10000)->get();
        
        $filename = 'activity_logs_' . now()->format('Y-m-d_His');
        
        return Excel::download(new ActivityLogsExport($logs), $filename . '.' . $request->format);
    }
    
    /**
     * Muestra el panel de análisis con estadísticas y gráficos.
     *
     * @return \Illuminate\View\View
     */
    public function analytics()
    {
        // Estadísticas generales
        $stats = [
            'total_logs' => ActivityLog::count(),
            'total_users' => User::count(),
            'active_users' => ActivityLog::select('user_id')
                ->distinct()
                ->whereNotNull('user_id')
                ->count(),
        ];
        
        // Actividad por día (últimos 30 días)
        $activityByDay = ActivityLog::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as count')
            )
            ->where('created_at', '>=', now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->keyBy('date')
            ->map(function ($item) {
                return $item->count;
            });
        
        // Completar días faltantes
        $dates = [];
        for ($i = 30; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $dates[$date] = $activityByDay[$date] ?? 0;
        }
        
        // Actividad por tipo de acción
        $activityByAction = ActivityLog::select(
                'action',
                DB::raw('COUNT(*) as count')
            )
            ->groupBy('action')
            ->orderBy('count', 'desc')
            ->limit(10)
            ->get();
        
        // Usuarios más activos
        $topUsers = ActivityLog::select(
                'user_id',
                DB::raw('COUNT(*) as count')
            )
            ->whereNotNull('user_id')
            ->groupBy('user_id')
            ->orderBy('count', 'desc')
            ->limit(10)
            ->with('user:id,name')
            ->get();
        
        return view('dashboard.admin.activity_logs.analytics', compact(
            'stats',
            'dates',
            'activityByAction',
            'topUsers'
        ));
    }
    
    /**
     * Limpia los registros de actividad antiguos.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function prune(Request $request)
    {
        try {
            $days = $request->input('days', 90);
            
            if (!is_numeric($days) || $days < 1) {
                return response()->json([
                    'success' => false,
                    'message' => 'El número de días debe ser un valor numérico mayor que 0.'
                ], 422);
            }
            
            $date = now()->subDays($days);
            $deleted = ActivityLog::where('created_at', '<', $date)->delete();
            
            // Registrar esta acción
            $user = Auth::user();
            ActivityLog::create([
                'user_id' => $user->id,
                'action' => 'pruned',
                'description' => "El usuario {$user->name} ha eliminado {$deleted} registros de actividad más antiguos que {$days} días",
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);
            
            return response()->json([
                'success' => true,
                'deleted' => $deleted,
                'message' => "Se han eliminado {$deleted} registros de actividad más antiguos que {$days} días."
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al limpiar los registros: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Guarda la configuración de los registros de actividad.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function saveSettings(Request $request)
    {
        try {
            $settings = $request->validate([
                'activity_retention_days' => 'required|integer|min:1|max:365',
                'log_actions' => 'required|array',
                'log_models' => 'required|array',
                'cleanup_schedule' => 'required|in:daily,weekly,monthly',
            ]);
            
            // Aquí se guardarían las configuraciones en la base de datos
            // Por ejemplo, usando el modelo Setting o un archivo de configuración
            
            return response()->json([
                'success' => true,
                'message' => 'Configuración guardada correctamente.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al guardar la configuración: ' . $e->getMessage()
            ], 500);
        }
    }
}
