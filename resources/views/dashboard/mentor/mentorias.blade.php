@extends('layouts.mentor')

@section('title', 'Mis Mentorías - MentorHub')

@section('content')
<div class="container-fluid">
    <!-- Encabezado -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Mis Mentorías</h1>
        <a href="{{ route('mentor.sessions.create') }}" class="btn btn-primary">
            <i class="fas fa-plus-circle me-1"></i> Nueva Sesión
        </a>
    </div>

    <!-- Mensajes de alerta -->
    @include('partials.alerts')

    <!-- Pestañas de navegación -->
    <ul class="nav nav-tabs mb-4" id="mentorTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="upcoming-tab" data-bs-toggle="tab" data-bs-target="#upcoming" type="button">
                <i class="fas fa-calendar-check me-1"></i> Próximas Sesiones
                @if($upcomingSessions->count() > 0)
                    <span class="badge bg-primary rounded-pill ms-1">{{ $upcomingSessions->count() }}</span>
                @endif
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="pending-tab" data-bs-toggle="tab" data-bs-target="#pending" type="button">
                <i class="fas fa-clock me-1"></i> Solicitudes Pendientes
                @if($pendingRequests->count() > 0)
                    <span class="badge bg-warning text-dark rounded-pill ms-1">{{ $pendingRequests->count() }}</span>
                @endif
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="history-tab" data-bs-toggle="tab" data-bs-target="#history" type="button">
                <i class="fas fa-history me-1"></i> Historial
            </button>
        </li>
    </ul>

    <!-- Contenido de las pestañas -->
    <div class="tab-content" id="mentorTabsContent">
        <!-- Pestaña de Próximas Sesiones -->
        <div class="tab-pane fade show active" id="upcoming" role="tabpanel">
            @if($upcomingSessions->isEmpty())
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    No tienes sesiones programadas en este momento.
                </div>
            @else
                <div class="row">
                    @foreach($upcomingSessions as $session)
                        <div class="col-md-6 col-lg-4 mb-4">
                            <div class="card h-100 border-left-primary">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h6 class="m-0 font-weight-bold text-primary">
                                        {{ $session->title }}
                                    </h6>
                                    <span class="badge bg-{{ $session->status === 'scheduled' ? 'primary' : 'warning' }}">
                                        {{ ucfirst($session->status) }}
                                    </span>
                                </div>
                                <div class="card-body">
                                    <div class="d-flex align-items-center mb-3">
                                        <img src="{{ $session->mentee->profile_photo_url }}" 
                                             class="rounded-circle me-3" 
                                             width="50" 
                                             height="50" 
                                             alt="{{ $session->mentee->name }}">
                                        <div>
                                            <h6 class="mb-0">{{ $session->mentee->name }}</h6>
                                            <small class="text-muted">Estudiante</small>
                                        </div>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <p class="mb-1">
                                            <i class="far fa-calendar-alt me-2 text-gray-400"></i>
                                            {{ $session->scheduled_at->format('l, d M Y - H:i') }}
                                        </p>
                                        <p class="mb-1">
                                            <i class="far fa-clock me-2 text-gray-400"></i>
                                            {{ $session->duration }} minutos
                                        </p>
                                        @if($session->meeting_url)
                                            <p class="mb-0">
                                                <i class="fas fa-video me-2 text-gray-400"></i>
                                                <a href="{{ $session->meeting_url }}" target="_blank">Unirse a la reunión</a>
                                            </p>
                                        @endif
                                    </div>
                                    
                                    @if($session->description)
                                        <div class="mb-3">
                                            <h6 class="small font-weight-bold">Descripción:</h6>
                                            <p class="small">{{ $session->description }}</p>
                                        </div>
                                    @endif
                                </div>
                                <div class="card-footer bg-white">
                                    <div class="d-flex justify-content-between">
                                        <a href="{{ route('mentor.sessions.show', $session->id) }}" 
                                           class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-eye me-1"></i> Ver detalles
                                        </a>
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-outline-secondary dropdown-toggle" 
                                                    type="button" 
                                                    id="sessionActions{{ $session->id }}" 
                                                    data-bs-toggle="dropdown" 
                                                    aria-expanded="false">
                                                Acciones
                                            </button>
                                            <ul class="dropdown-menu" aria-labelledby="sessionActions{{ $session->id }}">
                                                <li>
                                                    <a class="dropdown-item" href="{{ route('mentor.sessions.edit', $session->id) }}">
                                                        <i class="fas fa-edit me-2"></i> Editar
                                                    </a>
                                                </li>
                                                @if($session->status === 'scheduled')
                                                    <li>
                                                        <button class="dropdown-item text-success complete-session" 
                                                                data-id="{{ $session->id }}">
                                                            <i class="fas fa-check-circle me-2"></i> Marcar como completada
                                                        </button>
                                                    </li>
                                                    <li><hr class="dropdown-divider"></li>
                                                    <li>
                                                        <button class="dropdown-item text-danger cancel-session" 
                                                                data-id="{{ $session->id }}">
                                                            <i class="fas fa-times-circle me-2"></i> Cancelar sesión
                                                        </button>
                                                    </li>
                                                @endif
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Pestaña de Solicitudes Pendientes -->
        <div class="tab-pane fade" id="pending" role="tabpanel">
            @if($pendingRequests->isEmpty())
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    No tienes solicitudes de mentoría pendientes.
                </div>
            @else
                <div class="card shadow mb-4">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Estudiante</th>
                                        <th>Curso</th>
                                        <th>Fecha solicitada</th>
                                        <th>Mensaje</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($pendingRequests as $request)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <img src="{{ $request->mentee->profile_photo_url }}" 
                                                         class="rounded-circle me-2" 
                                                         width="32" 
                                                         height="32" 
                                                         alt="{{ $request->mentee->name }}">
                                                    {{ $request->mentee->name }}
                                                </div>
                                            </td>
                                            <td>{{ $request->course->title ?? 'N/A' }}</td>
                                            <td>{{ $request->scheduled_at->format('d/m/Y H:i') }}</td>
                                            <td>
                                                <small class="text-muted">
                                                    {{ Str::limit($request->message, 50) }}
                                                </small>
                                            </td>
                                            <td>
                                                <div class="btn-group btn-group-sm">
                                                    <button class="btn btn-success btn-accept" 
                                                            data-id="{{ $request->id }}">
                                                        <i class="fas fa-check"></i>
                                                    </button>
                                                    <button class="btn btn-danger btn-reject" 
                                                            data-id="{{ $request->id }}"
                                                            data-bs-toggle="modal" 
                                                            data-bs-target="#rejectModal"
                                                            data-request-id="{{ $request->id }}">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                    <a href="{{ route('mentor.sessions.show', $request->id) }}" 
                                                       class="btn btn-info text-white">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Pestaña de Historial -->
        <div class="tab-pane fade" id="history" role="tabpanel">
            @if($pastSessions->isEmpty())
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    No hay historial de sesiones anteriores.
                </div>
            @else
                <div class="card shadow mb-4">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover" id="sessionsTable">
                                <thead>
                                    <tr>
                                        <th>Estudiante</th>
                                        <th>Título</th>
                                        <th>Fecha</th>
                                        <th>Duración</th>
                                        <th>Estado</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($pastSessions as $session)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <img src="{{ $session->mentee->profile_photo_url }}" 
                                                         class="rounded-circle me-2" 
                                                         width="32" 
                                                         height="32" 
                                                         alt="{{ $session->mentee->name }}">
                                                    {{ $session->mentee->name }}
                                                </div>
                                            </td>
                                            <td>{{ $session->title }}</td>
                                            <td>{{ $session->scheduled_at->format('d/m/Y H:i') }}</td>
                                            <td>{{ $session->duration }} min</td>
                                            <td>
                                                <span class="badge bg-{{ $session->status === 'completed' ? 'success' : 'secondary' }}">
                                                    {{ ucfirst($session->status) }}
                                                </span>
                                            </td>
                                            <td>
                                                <a href="{{ route('mentor.sessions.show', $session->id) }}" 
                                                   class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                @if($session->status === 'completed' && !$session->reviews->where('author_id', auth()->id())->first())
                                                    <button class="btn btn-sm btn-outline-warning add-review"
                                                            data-bs-toggle="modal" 
                                                            data-bs-target="#reviewModal"
                                                            data-session-id="{{ $session->id }}">
                                                        <i class="fas fa-star"></i>
                                                    </button>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            {{ $pastSessions->links() }}
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal para Rechazar Solicitud -->
<div class="modal fade" id="rejectModal" tabindex="-1" aria-labelledby="rejectModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-warning text-white">
                <h5 class="modal-title" id="rejectModalLabel">Rechazar Solicitud</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <form id="rejectForm" action="{{ route('mentor.sessions.respond') }}" method="POST">
                @csrf
                <input type="hidden" name="status" value="rejected">
                <input type="hidden" name="session_id" id="rejectSessionId" value="">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="rejectMessage" class="form-label">Mensaje (opcional)</label>
                        <textarea class="form-control" id="rejectMessage" name="message" rows="3" 
                                  placeholder="¿Por qué estás rechazando esta solicitud?"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="proposedTime" class="form-label">¿Te gustaría proponer otro horario?</label>
                        <input type="datetime-local" class="form-control" id="proposedTime" 
                               name="proposed_time" min="{{ now()->addDay()->format('Y-m-d\TH:i') }}">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-warning">Enviar Respuesta</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal para Añadir Reseña -->
<div class="modal fade" id="reviewModal" tabindex="-1" aria-labelledby="reviewModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="reviewModalLabel">Añadir Reseña</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <form id="reviewForm" action="{{ route('mentor.sessions.review') }}" method="POST">
                @csrf
                <input type="hidden" name="session_id" id="reviewSessionId" value="">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="rating" class="form-label">Calificación</label>
                        <div class="rating-stars">
                            <input type="hidden" name="rating" id="ratingValue" value="5">
                            <div class="stars">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="fas fa-star star" data-rating="{{ $i }}"></i>
                                @endfor
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="comment" class="form-label">Comentario</label>
                        <textarea class="form-control" id="comment" name="comment" rows="3" 
                                  placeholder="¿Cómo fue la sesión?" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Guardar Reseña</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal para Confirmar Cancelación -->
<div class="modal fade" id="cancelModal" tabindex="-1" aria-labelledby="cancelModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="cancelModalLabel">Confirmar Cancelación</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <form id="cancelForm" action="{{ route('mentor.sessions.update-status') }}" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" name="status" value="cancelled">
                <input type="hidden" name="session_id" id="cancelSessionId" value="">
                <div class="modal-body">
                    <p>¿Estás seguro de que deseas cancelar esta sesión?</p>
                    <div class="mb-3">
                        <label for="cancelReason" class="form-label">Razón de la cancelación (opcional)</label>
                        <textarea class="form-control" id="cancelReason" name="notes" rows="3" 
                                 placeholder="Proporciona una razón para la cancelación"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-danger">Confirmar Cancelación</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .rating-stars {
        font-size: 1.5rem;
    }
    .rating-stars .star {
        color: #ddd;
        cursor: pointer;
        transition: color 0.2s;
    }
    .rating-stars .star.selected,
    .rating-stars .star.hovered {
        color: #ffc107;
    }
    .card {
        transition: transform 0.2s, box-shadow 0.2s;
    }
    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
    }
    .nav-tabs .nav-link {
        color: #6c757d;
        font-weight: 500;
    }
    .nav-tabs .nav-link.active {
        color: #4e73df;
        font-weight: 600;
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Inicializar DataTable para el historial
        if (document.getElementById('sessionsTable')) {
            new simpleDatatables.DataTable("#sessionsTable", {
                searchable: true,
                fixedHeight: true,
                perPage: 10,
                labels: {
                    placeholder: "Buscar...",
                    perPage: "{select} registros por página",
                    noRows: "No se encontraron registros",
                    info: "Mostrando {start} a {end} de {rows} registros"
                }
            });
        }

        // Manejar el envío del formulario de cancelación
        const cancelSessionButtons = document.querySelectorAll('.cancel-session');
        cancelSessionButtons.forEach(button => {
            button.addEventListener('click', function() {
                const sessionId = this.getAttribute('data-id');
                document.getElementById('cancelSessionId').value = sessionId;
                const modal = new bootstrap.Modal(document.getElementById('cancelModal'));
                modal.show();
            });
        });

        // Manejar el envío del formulario de completar sesión
        const completeSessionButtons = document.querySelectorAll('.complete-session');
        completeSessionButtons.forEach(button => {
            button.addEventListener('click', function() {
                if (confirm('¿Marcar esta sesión como completada?')) {
                    const sessionId = this.getAttribute('data-id');
                    fetch(`/mentor/sessions/${sessionId}/status`, {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            status: 'completed'
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            window.location.reload();
                        } else {
                            alert('Error al actualizar el estado de la sesión');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Error al procesar la solicitud');
                    });
                }
            });
        });

        // Manejar el modal de rechazo
        const rejectButtons = document.querySelectorAll('.btn-reject');
        rejectButtons.forEach(button => {
            button.addEventListener('click', function() {
                const requestId = this.getAttribute('data-request-id');
                document.getElementById('rejectSessionId').value = requestId;
            });
        });

        // Manejar el envío del formulario de aceptar solicitud
        const acceptButtons = document.querySelectorAll('.btn-accept');
        acceptButtons.forEach(button => {
            button.addEventListener('click', function() {
                if (confirm('¿Aceptar esta solicitud de mentoría?')) {
                    const requestId = this.getAttribute('data-id');
                    fetch(`/mentor/sessions/${requestId}/respond`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            status: 'accepted'
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            window.location.reload();
                        } else {
                            alert('Error al procesar la solicitud');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Error al procesar la solicitud');
                    });
                }
            });
        });

        // Calificación con estrellas
        const stars = document.querySelectorAll('.star');
        if (stars.length > 0) {
            stars.forEach(star => {
                star.addEventListener('click', function() {
                    const rating = this.getAttribute('data-rating');
                    document.getElementById('ratingValue').value = rating;
                    
                    // Actualizar la visualización de estrellas
                    stars.forEach(s => {
                        if (parseInt(s.getAttribute('data-rating')) <= rating) {
                            s.classList.add('selected');
                        } else {
                            s.classList.remove('selected');
                        }
                    });
                });

                // Efecto hover
                star.addEventListener('mouseover', function() {
                    const rating = this.getAttribute('data-rating');
                    stars.forEach(s => {
                        if (parseInt(s.getAttribute('data-rating')) <= rating) {
                            s.classList.add('hovered');
                        } else {
                            s.classList.remove('hovered');
                        }
                    });
                });

                star.addEventListener('mouseout', function() {
                    stars.forEach(s => s.classList.remove('hovered'));
                });
            });
        }

        // Manejar el modal de reseña
        const reviewButtons = document.querySelectorAll('.add-review');
        reviewButtons.forEach(button => {
            button.addEventListener('click', function() {
                const sessionId = this.getAttribute('data-session-id');
                document.getElementById('reviewSessionId').value = sessionId;
            });
        });
    });
</script>
@endpush