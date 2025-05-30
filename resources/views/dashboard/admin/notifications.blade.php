@extends('layouts.admin')

@section('title', 'Notificaciones - Admin')



@section('content')
<div class="container-fluid">
    <!-- Encabezado -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Gestión de Notificaciones</h1>
        <div>
            <button class="btn btn-success me-2">
                <i class="fas fa-envelope"></i> Enviar a Todos
            </button>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#newNotificationModal">
                <i class="fas fa-plus"></i> Nueva Notificación
            </button>
        </div>
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
                            <input type="text" class="form-control" id="search" placeholder="Buscar notificaciones...">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="type">Tipo</label>
                            <select class="form-control" id="type">
                                <option value="">Todos los tipos</option>
                                <option value="system">Sistema</option>
                                <option value="announcement">Anuncio</option>
                                <option value="alert">Alerta</option>
                                <option value="update">Actualización</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="status">Estado</label>
                            <select class="form-control" id="status">
                                <option value="">Todos</option>
                                <option value="unread">No leídas</option>
                                <option value="read">Leídas</option>
                            </select>
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

    <!-- Lista de Notificaciones -->
    <div class="card shadow">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Notificaciones Recientes</h6>
            <div class="dropdown">
                <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" id="exportDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-download"></i> Exportar
                </button>
                <ul class="dropdown-menu" aria-labelledby="exportDropdown">
                    <li><a class="dropdown-item" href="#">Excel</a></li>
                    <li><a class="dropdown-item" href="#">PDF</a></li>
                    <li><a class="dropdown-item" href="#">CSV</a></li>
                </ul>
            </div>
        </div>
        <div class="card-body">
            <div class="list-group">
                @forelse($notifications as $notification)
                <div class="list-group-item list-group-item-action {{ $notification->read_at ? '' : 'bg-light' }}">
                    <div class="d-flex w-100 justify-content-between">
                        <h6 class="mb-1">
                            @if($notification->type === 'system')
                                <i class="fas fa-cog text-primary me-2"></i>
                            @elseif($notification->type === 'announcement')
                                <i class="fas fa-bullhorn text-warning me-2"></i>
                            @elseif($notification->type === 'alert')
                                <i class="fas fa-exclamation-triangle text-danger me-2"></i>
                            @else
                                <i class="fas fa-bell text-info me-2"></i>
                            @endif
                            {{ $notification->title }}
                        </h6>
                        <small>{{ $notification->created_at->diffForHumans() }}</small>
                    </div>
                    <p class="mb-1">{{ $notification->message }}</p>
                    <div class="d-flex justify-content-between align-items-center">
                        <small>
                            @if($notification->read_at)
                                <span class="text-muted">Leída el {{ $notification->read_at->format('d/m/Y H:i') }}</span>
                            @else
                                <span class="text-success">Nueva</span>
                            @endif
                        </small>
                        <div class="btn-group">
                            @if(!$notification->read_at)
                            <form action="{{ route('admin.notifications.mark-as-read', $notification->id) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-check"></i> Marcar como leída
                                </button>
                            </form>
                            @endif
                            <button class="btn btn-sm btn-outline-info" data-bs-toggle="modal" data-bs-target="#viewNotificationModal{{ $notification->id }}">
                                <i class="fas fa-eye"></i> Ver
                            </button>
                            <button class="btn btn-sm btn-outline-danger" onclick="confirmDelete({{ $notification->id }})">
                                <i class="fas fa-trash"></i> Eliminar
                            </button>
                            <form id="delete-form-{{ $notification->id }}" action="{{ route('admin.notifications.destroy', $notification->id) }}" method="POST" class="d-none">
                                @csrf
                                @method('DELETE')
                            </form>
                        </div>
                    </div>
                </div>
                @empty
                <div class="text-center py-4">
                    <i class="fas fa-bell-slash fa-3x text-muted mb-3"></i>
                    <p class="text-muted">No hay notificaciones para mostrar</p>
                </div>
                @endforelse
            </div>
            
            <!-- Paginación -->
            @if($notifications->hasPages())
            <div class="d-flex justify-content-center mt-3">
                {{ $notifications->links() }}
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal para nueva notificación -->
<div class="modal fade" id="newNotificationModal" tabindex="-1" aria-labelledby="newNotificationModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="newNotificationModalLabel">Nueva Notificación</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <form action="{{ route('admin.notifications.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="title" class="form-label">Título</label>
                        <input type="text" class="form-control" id="title" name="title" required>
                    </div>
                    <div class="mb-3">
                        <label for="type" class="form-label">Tipo</label>
                        <select class="form-select" id="type" name="type" required>
                            <option value="announcement">Anuncio</option>
                            <option value="alert">Alerta</option>
                            <option value="update">Actualización</option>
                            <option value="system">Sistema</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="message" class="form-label">Mensaje</label>
                        <textarea class="form-control" id="message" name="message" rows="4" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="recipients" class="form-label">Destinatarios</label>
                        <select class="form-select" id="recipients" name="recipients[]" multiple>
                            <option value="all">Todos los usuarios</option>
                            <option value="students">Estudiantes</option>
                            <option value="mentors">Mentores</option>
                            <option value="admins">Administradores</option>
                        </select>
                        <div class="form-text">Mantén presionada la tecla Ctrl (Windows) o Comando (Mac) para seleccionar múltiples opciones.</div>
                    </div>
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" id="send_email" name="send_email">
                        <label class="form-check-label" for="send_email">
                            Enviar por correo electrónico
                        </label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Enviar Notificación</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
function confirmDelete(notificationId) {
    if (confirm('¿Estás seguro de que deseas eliminar esta notificación?')) {
        document.getElementById('delete-form-' + notificationId).submit();
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
