@extends('layouts.admin')

@section('title', 'Gestión de Eventos - Admin')

@section('content')
<div class="container-fluid">
    <!-- Encabezado -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Gestión de Eventos</h1>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addEventModal">
            <i class="fas fa-plus"></i> Nuevo Evento
        </button>
    </div>

    <!-- Filtros -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Filtros</h6>
        </div>
        <div class="card-body">
            <form>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="search">Buscar</label>
                            <input type="text" class="form-control" id="search" placeholder="Buscar eventos...">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="status">Estado</label>
                            <select class="form-control" id="status">
                                <option value="">Todos</option>
                                <option value="upcoming">Próximos</option>
                                <option value="ongoing">En Curso</option>
                                <option value="completed">Completados</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="date">Fecha</label>
                            <input type="date" class="form-control" id="date">
                        </div>
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-filter"></i> Filtrar
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Tabla de Eventos -->
    <div class="card shadow">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Título</th>
                            <th>Fecha y Hora</th>
                            <th>Duración</th>
                            <th>Tipo</th>
                            <th>Asistentes</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($events as $event)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $event->title }}</td>
                            <td>{{ $event->start_date->format('d/m/Y H:i') }}</td>
                            <td>{{ $event->duration }} minutos</td>
                            <td>
                                <span class="badge bg-{{ $event->type === 'workshop' ? 'info' : 'success' }}">
                                    {{ ucfirst($event->type) }}
                                </span>
                            </td>
                            <td>{{ $event->attendees_count }} / {{ $event->max_attendees ?? '∞' }}</td>
                            <td>
                                @if($event->is_completed)
                                    <span class="badge bg-secondary">Completado</span>
                                @elseif($event->is_ongoing)
                                    <span class="badge bg-success">En Curso</span>
                                @else
                                    <span class="badge bg-primary">Próximo</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group">
                                    <button class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#viewEventModal{{ $event->id }}">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editEventModal{{ $event->id }}">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-sm btn-danger" onclick="confirmDelete({{ $event->id }})">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center">No hay eventos programados</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Paginación -->
            @if($events->hasPages())
            <div class="d-flex justify-content-center mt-3">
                {{ $events->links() }}
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal para agregar evento -->
<div class="modal fade" id="addEventModal" tabindex="-1" aria-labelledby="addEventModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addEventModalLabel">Nuevo Evento</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <form action="{{ route('admin.events.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="title" class="form-label">Título</label>
                        <input type="text" class="form-control" id="title" name="title" required>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="start_date" class="form-label">Fecha y Hora</label>
                                <input type="datetime-local" class="form-control" id="start_date" name="start_date" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="duration" class="form-label">Duración (minutos)</label>
                                <input type="number" class="form-control" id="duration" name="duration" min="1" value="60" required>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="type" class="form-label">Tipo de Evento</label>
                        <select class="form-select" id="type" name="type" required>
                            <option value="workshop">Taller</option>
                            <option value="meeting">Reunión</option>
                            <option value="webinar">Webinar</option>
                            <option value="other">Otro</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="max_attendees" class="form-label">Máximo de asistentes (dejar en blanco para ilimitado)</label>
                        <input type="number" class="form-control" id="max_attendees" name="max_attendees" min="1">
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Descripción</label>
                        <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Guardar Evento</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
function confirmDelete(eventId) {
    if (confirm('¿Estás seguro de que deseas eliminar este evento?')) {
        document.getElementById('delete-form-' + eventId).submit();
    }
}

// Inicializar tooltips
var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
    return new bootstrap.Tooltip(tooltipTriggerEl);
});
</script>
@endpush

@endsection
