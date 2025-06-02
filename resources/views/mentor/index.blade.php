@extends('layouts.mentor')

@section('title', 'Dashboard de Mentor - MentorHub')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Panel de Control</h1>
        <a href="{{ route('mentor.sessions.create') }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
            <i class="fas fa-plus-circle fa-sm text-white-50"></i> Nueva Sesión
        </a>
    </div>

    @include('partials.alerts')

    <!-- Content Row -->
    <div class="row">
        <!-- Total de Sesiones Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total de Sesiones</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['total_sessions'] ?? 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Próximas Sesiones Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Próximas Sesiones</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['upcoming_sessions'] ?? 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clock fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Solicitudes Pendientes Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Solicitudes Pendientes</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['pending_requests'] ?? 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-comments fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tasa de Finalización Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Tasa de Finalización</div>
                            <div class="row no-gutters align-items-center">
                                <div class="col-auto">
                                    <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800">
                                        {{ number_format($stats['completion_rate'] ?? 0, 1) }}%
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="progress progress-sm mr-2">
                                        <div class="progress-bar bg-info" role="progressbar"
                                            style="width: {{ $stats['completion_rate'] ?? 0 }}%"
                                            aria-valuenow="{{ $stats['completion_rate'] ?? 0 }}" 
                                            aria-valuemin="0" 
                                            aria-valuemax="100"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Content Row -->
    <div class="row">
        <!-- Próximas Sesiones -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Próximas Sesiones</h6>
                    <a href="{{ route('mentor.sessions.index') }}" class="btn btn-sm btn-primary">Ver Todas</a>
                </div>
                <div class="card-body">
                    @if(isset($upcomingSessions) && $upcomingSessions->isNotEmpty())
                        <div class="list-group list-group-flush">
                            @foreach($upcomingSessions as $session)
                                <a href="{{ route('mentor.sessions.show', $session->id) }}" 
                                   class="list-group-item list-group-item-action">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1">{{ $session->title }}</h6>
                                        <small>{{ $session->scheduled_at->diffForHumans() }}</small>
                                    </div>
                                    <p class="mb-1">
                                        <i class="fas fa-user-graduate me-1"></i>{{ $session->mentee->name }}
                                        @if($session->course)
                                            <span class="ms-2"><i class="fas fa-book me-1"></i>{{ $session->course->title }}</span>
                                        @endif
                                    </p>
                                    <small>
                                        <i class="far fa-calendar-alt me-1"></i>
                                        {{ $session->scheduled_at->format('l, d M Y - H:i') }}
                                    </small>
                                </a>
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted">No tienes sesiones programadas.</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Solicitudes Recientes -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-warning">Solicitudes Recientes</h6>
                    <a href="{{ route('mentor.sessions.index') }}" class="btn btn-sm btn-warning">Ver Todas</a>
                </div>
                <div class="card-body">
                    @if(isset($pendingRequests) && $pendingRequests->isNotEmpty())
                        <div class="list-group list-group-flush">
                            @foreach($pendingRequests as $request)
                                <div class="list-group-item">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1">{{ $request->mentee->name }}</h6>
                                        <small>{{ $request->created_at->diffForHumans() }}</small>
                                    </div>
                                    <p class="mb-1">{{ $request->message ? Str::limit($request->message, 80) : 'Sin mensaje' }}</p>
                                    <div class="btn-group btn-group-sm mt-2">
                                        <form action="{{ route('mentor.sessions.respond', $request->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <input type="hidden" name="status" value="confirmed">
                                            <button type="submit" class="btn btn-sm btn-success">
                                                <i class="fas fa-check me-1"></i> Aceptar
                                            </button>
                                        </form>
                                        <button type="button" class="btn btn-sm btn-danger" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#rejectRequestModal"
                                                data-request-id="{{ $request->id }}">
                                            <i class="fas fa-times me-1"></i> Rechazar
                                        </button>
                                        <a href="{{ route('mentor.sessions.show', $request->id) }}" 
                                           class="btn btn-sm btn-info text-white">
                                            <i class="fas fa-eye me-1"></i> Ver
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted">No tienes solicitudes pendientes.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Content Row -->
    <div class="row">
        <!-- Calendario -->
        <div class="col-lg-12 mb-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Calendario de Sesiones</h6>
                    <a href="{{ route('mentor.calendar') }}" class="btn btn-sm btn-primary">Ver Calendario Completo</a>
                </div>
                <div class="card-body">
                    <div id="mentorCalendar"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal para Rechazar Solicitud -->
<div class="modal fade" id="rejectRequestModal" tabindex="-1" aria-labelledby="rejectRequestModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-warning text-white">
                <h5 class="modal-title" id="rejectRequestModalLabel">Rechazar Solicitud</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <form id="rejectRequestForm" action="" method="POST">
                @csrf
                <input type="hidden" name="status" value="rejected">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="rejectReason" class="form-label">Razón del rechazo (opcional)</label>
                        <textarea class="form-control" id="rejectReason" name="message" rows="3"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="proposedTime" class="form-label">Proponer otro horario</label>
                        <input type="datetime-local" class="form-control" id="proposedTime" name="proposed_time" min="{{ now()->format('Y-m-d\\TH:i') }}">
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
@endsection

@push('styles')
<!-- FullCalendar CSS -->
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/main.min.css" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('css/mentor-dashboard.css') }}">
@endpush

@push('scripts')
<!-- FullCalendar JS -->
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/main.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/locales-all.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Configurar el modal de rechazo
    const rejectModal = document.getElementById('rejectRequestModal');
    if (rejectModal) {
        rejectModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            const requestId = button.getAttribute('data-request-id');
            const form = document.getElementById('rejectRequestForm');
            form.action = `{{ route('mentor.sessions.respond', '') }}/${requestId}`;
        });
    }

    // Inicializar el calendario
    const calendarEl = document.getElementById('mentorCalendar');
    if (calendarEl) {
        const calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            locale: 'es',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay'
            },
            events: @json($calendarEvents ?? []),
            eventClick: function(info) {
                if (info.event.url) {
                    window.location.href = info.event.url;
                    return false;
                }
            },
            eventTimeFormat: {
                hour: '2-digit',
                minute: '2-digit',
                hour12: false
            }
        });
        calendar.render();
    }
});
</script>
@endpush
