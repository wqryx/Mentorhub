@extends('layouts.admin')

@section('title', 'Registros de Actividad')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h2 class="mb-0">Registros de Actividad</h2>
                        <div>
                            <a href="{{ route('admin.activities.analytics') }}" class="btn btn-outline-info me-2">
                                <i class="fas fa-chart-line me-1"></i> Panel de Análisis
                            </a>
                            <button type="button" class="btn btn-outline-success" data-bs-toggle="modal" data-bs-target="#exportModal">
                                <i class="fas fa-file-export me-1"></i> Exportar
                            </button>
                        </div>
                    </div>
                </div>
                
                <div class="card-body">
                    <!-- Filtros -->
                    <form method="GET" action="{{ route('admin.activities.index') }}" class="mb-4">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <label for="user_id" class="form-label">Usuario</label>
                                <select name="user_id" id="user_id" class="form-select">
                                    <option value="">Todos los usuarios</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                                            {{ $user->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="action" class="form-label">Acción</label>
                                <select name="action" id="action" class="form-select">
                                    <option value="">Todas las acciones</option>
                                    @foreach($actions as $action)
                                        <option value="{{ $action }}" {{ request('action') == $action ? 'selected' : '' }}>
                                            {{ ucfirst($action) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="start_date" class="form-label">Fecha Inicio</label>
                                <input type="date" name="start_date" id="start_date" class="form-control" value="{{ request('start_date') }}">
                            </div>
                            <div class="col-md-3">
                                <label for="end_date" class="form-label">Fecha Fin</label>
                                <input type="date" name="end_date" id="end_date" class="form-control" value="{{ request('end_date') }}">
                            </div>
                            <div class="col-12 d-flex justify-content-end">
                                <button type="submit" class="btn btn-primary me-2">
                                    <i class="fas fa-filter me-1"></i> Filtrar
                                </button>
                                <a href="{{ route('admin.activities.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-undo me-1"></i> Reiniciar
                                </a>
                            </div>
                        </div>
                    </form>
                    
                    <!-- Tabla de registros -->
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Usuario</th>
                                    <th>Acción</th>
                                    <th>Modelo</th>
                                    <th>ID Modelo</th>
                                    <th>Fecha</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($logs as $log)
                                    <tr>
                                        <td>{{ $log->id }}</td>
                                        <td>{{ $log->user->name ?? 'Sistema' }}</td>
                                        <td>
                                            <span class="badge {{ $log->action == 'create' ? 'bg-success' : ($log->action == 'update' ? 'bg-info' : ($log->action == 'delete' ? 'bg-danger' : 'bg-secondary')) }}">
                                                {{ ucfirst($log->action) }}
                                            </span>
                                        </td>
                                        <td>{{ class_basename($log->model_type) }}</td>
                                        <td>{{ $log->model_id }}</td>
                                        <td>{{ $log->created_at->format('d/m/Y H:i:s') }}</td>
                                        <td>
                                            <a href="{{ route('admin.activities.show', $log->id) }}" class="btn btn-sm btn-info">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center">No se encontraron registros de actividad</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Paginación -->
                    <div class="mt-4">
                        {{ $logs->withQueryString()->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Exportación -->
<div class="modal fade" id="exportModal" tabindex="-1" aria-labelledby="exportModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exportModalLabel">Exportar Registros de Actividad</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.activities.export') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="export_format" class="form-label">Formato</label>
                        <select name="format" id="export_format" class="form-select" required>
                            <option value="csv">CSV</option>
                            <option value="xlsx">Excel</option>
                            <option value="pdf">PDF</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="export_date_range" class="form-label">Rango de Fechas</label>
                        <select name="date_range" id="export_date_range" class="form-select">
                            <option value="all">Todos los registros</option>
                            <option value="today">Hoy</option>
                            <option value="week">Esta semana</option>
                            <option value="month">Este mes</option>
                            <option value="custom">Personalizado</option>
                        </select>
                    </div>
                    <div id="custom_date_range" class="row g-3 d-none">
                        <div class="col-md-6">
                            <label for="export_start_date" class="form-label">Fecha Inicio</label>
                            <input type="date" name="start_date" id="export_start_date" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label for="export_end_date" class="form-label">Fecha Fin</label>
                            <input type="date" name="end_date" id="export_end_date" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Exportar</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Mostrar/ocultar campos de fecha personalizada en el modal de exportación
        const dateRangeSelect = document.getElementById('export_date_range');
        const customDateRange = document.getElementById('custom_date_range');
        
        dateRangeSelect.addEventListener('change', function() {
            if (this.value === 'custom') {
                customDateRange.classList.remove('d-none');
            } else {
                customDateRange.classList.add('d-none');
            }
        });
    });
</script>
@endsection
