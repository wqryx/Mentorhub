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
                            <a href="{{ route('admin.activity_logs.analytics') }}" class="btn btn-outline-info me-2">
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
                    <form method="GET" action="{{ route('admin.activity_logs.index') }}" class="mb-4">
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
                                <input type="date" class="form-control" id="start_date" name="start_date" value="{{ request('start_date') }}">
                            </div>
                            <div class="col-md-3">
                                <label for="end_date" class="form-label">Fecha Fin</label>
                                <input type="date" class="form-control" id="end_date" name="end_date" value="{{ request('end_date') }}">
                            </div>
                            <div class="col-12 text-end">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-filter me-1"></i> Filtrar
                                </button>
                                <a href="{{ route('admin.activity_logs.index') }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-undo me-1"></i> Limpiar
                                </a>
                            </div>
                        </div>
                    </form>
                    
                    <!-- Tabla de registros -->
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Usuario</th>
                                    <th>Acción</th>
                                    <th>Descripción</th>
                                    <th>Fecha</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($logs as $log)
                                    <tr>
                                        <td>{{ $log->id }}</td>
                                        <td>
                                            @if($log->user)
                                                <a href="{{ route('admin.users.show', $log->user_id) }}">
                                                    {{ $log->user->name }}
                                                </a>
                                            @else
                                                <span class="text-muted">Sistema</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge {{ getBadgeClass($log->action) }}">
                                                {{ ucfirst($log->action) }}
                                            </span>
                                        </td>
                                        <td>{{ Str::limit($log->description, 50) }}</td>
                                        <td>{{ $log->created_at->format('d/m/Y H:i') }}</td>
                                        <td>
                                            <a href="{{ route('admin.activity_logs.show', $log->id) }}" class="btn btn-sm btn-info">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-4">
                                            <p class="text-muted mb-0">No se encontraron registros de actividad.</p>
                                        </td>
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
            <form action="{{ route('admin.activity_logs.export') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="exportModalLabel">Exportar Registros de Actividad</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="export_format" class="form-label">Formato</label>
                        <select name="format" id="export_format" class="form-select" required>
                            <option value="xlsx">Excel (XLSX)</option>
                            <option value="csv">CSV</option>
                        </select>
                    </div>
                    
                    <p class="text-muted">Se exportarán los registros según los filtros aplicados actualmente.</p>
                    
                    <!-- Campos ocultos para mantener los filtros -->
                    <input type="hidden" name="user_id" value="{{ request('user_id') }}">
                    <input type="hidden" name="action" value="{{ request('action') }}">
                    <input type="hidden" name="start_date" value="{{ request('start_date') }}">
                    <input type="hidden" name="end_date" value="{{ request('end_date') }}">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-download me-1"></i> Exportar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Inicializar componentes si es necesario
    });
</script>
@endpush

@php
function getBadgeClass($action) {
    switch ($action) {
        case 'created':
            return 'bg-success';
        case 'updated':
            return 'bg-info';
        case 'deleted':
            return 'bg-danger';
        case 'login':
            return 'bg-primary';
        case 'logout':
            return 'bg-secondary';
        default:
            return 'bg-dark';
    }
}
@endphp
