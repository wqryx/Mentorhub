@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Sesiones de Mentoría</h5>
                    <div class="card-tools">
                        <a href="{{ route('mentor.sessions.create') }}" class="btn btn-sm btn-primary">
                            <i class="fas fa-plus-circle"></i> Nueva Sesión
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <ul class="nav nav-tabs mb-4" id="sessionsTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <a class="nav-link active" id="upcoming-tab" data-toggle="tab" href="#upcoming" role="tab" aria-controls="upcoming" aria-selected="true">
                                Próximas <span class="badge badge-primary">{{ $upcomingSessions->count() }}</span>
                            </a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="pending-tab" data-toggle="tab" href="#pending" role="tab" aria-controls="pending" aria-selected="false">
                                Pendientes <span class="badge badge-warning">{{ $pendingRequests->count() }}</span>
                            </a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="past-tab" data-toggle="tab" href="#past" role="tab" aria-controls="past" aria-selected="false">
                                Historial <span class="badge badge-secondary">{{ $pastSessions->total() }}</span>
                            </a>
                        </li>
                    </ul>
                    
                    <div class="tab-content" id="sessionsTabContent">
                        <!-- Próximas sesiones -->
                        <div class="tab-pane fade show active" id="upcoming" role="tabpanel" aria-labelledby="upcoming-tab">
                            @if($upcomingSessions->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>Estudiante</th>
                                                <th>Fecha y Hora</th>
                                                <th>Duración</th>
                                                <th>Curso</th>
                                                <th>Estado</th>
                                                <th>Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($upcomingSessions as $session)
                                                <tr>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <img src="{{ $session->mentee->profile_image ? asset('storage/' . $session->mentee->profile_image) : asset('images/default-avatar.png') }}" class="rounded-circle mr-2" width="40" height="40" alt="{{ $session->mentee->name }}">
                                                            <div>{{ $session->mentee->name }}</div>
                                                        </div>
                                                    </td>
                                                    <td>{{ $session->scheduled_at->format('d/m/Y H:i') }}</td>
                                                    <td>{{ $session->duration }} min</td>
                                                    <td>{{ $session->course ? $session->course->title : 'N/A' }}</td>
                                                    <td>
                                                        <span class="badge badge-success">Confirmada</span>
                                                    </td>
                                                    <td>
                                                        <div class="btn-group">
                                                            <a href="{{ route('mentor.sessions.show', $session->id) }}" class="btn btn-sm btn-info">
                                                                <i class="fas fa-eye"></i>
                                                            </a>
                                                            <a href="{{ route('mentor.sessions.edit', $session->id) }}" class="btn btn-sm btn-warning">
                                                                <i class="fas fa-edit"></i>
                                                            </a>
                                                            <button type="button" class="btn btn-sm btn-success" data-toggle="modal" data-target="#startSessionModal" data-session-id="{{ $session->id }}" data-session-title="{{ $session->title }}" data-student-name="{{ $session->mentee->name }}">
                                                                <i class="fas fa-play"></i>
                                                            </button>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="alert alert-info">
                                    <p>No tienes sesiones programadas próximamente.</p>
                                    <a href="{{ route('mentor.sessions.create') }}" class="btn btn-primary mt-2">Programar Nueva Sesión</a>
                                </div>
                            @endif
                        </div>
                        
                        <!-- Solicitudes pendientes -->
                        <div class="tab-pane fade" id="pending" role="tabpanel" aria-labelledby="pending-tab">
                            @if($pendingRequests->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>Estudiante</th>
                                                <th>Fecha Solicitada</th>
                                                <th>Duración</th>
                                                <th>Curso</th>
                                                <th>Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($pendingRequests as $request)
                                                <tr>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <img src="{{ $request->mentee->profile_image ? asset('storage/' . $request->mentee->profile_image) : asset('images/default-avatar.png') }}" class="rounded-circle mr-2" width="40" height="40" alt="{{ $request->mentee->name }}">
                                                            <div>{{ $request->mentee->name }}</div>
                                                        </div>
                                                    </td>
                                                    <td>{{ $request->scheduled_at->format('d/m/Y H:i') }}</td>
                                                    <td>{{ $request->duration }} min</td>
                                                    <td>{{ $request->course ? $request->course->title : 'N/A' }}</td>
                                                    <td>
                                                        <div class="btn-group">
                                                            <a href="{{ route('mentor.sessions.show', $request->id) }}" class="btn btn-sm btn-info">
                                                                <i class="fas fa-eye"></i>
                                                            </a>
                                                            <button type="button" class="btn btn-sm btn-success" data-toggle="modal" data-target="#acceptRequestModal" data-request-id="{{ $request->id }}" data-student-name="{{ $request->mentee->name }}">
                                                                <i class="fas fa-check"></i>
                                                            </button>
                                                            <button type="button" class="btn btn-sm btn-danger" data-toggle="modal" data-target="#rejectRequestModal" data-request-id="{{ $request->id }}" data-student-name="{{ $request->mentee->name }}">
                                                                <i class="fas fa-times"></i>
                                                            </button>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="alert alert-info">
                                    <p>No tienes solicitudes de mentoría pendientes.</p>
                                </div>
                            @endif
                        </div>
                        
                        <!-- Historial de sesiones -->
                        <div class="tab-pane fade" id="past" role="tabpanel" aria-labelledby="past-tab">
                            @if($pastSessions->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>Estudiante</th>
                                                <th>Fecha</th>
                                                <th>Duración</th>
                                                <th>Curso</th>
                                                <th>Estado</th>
                                                <th>Valoración</th>
                                                <th>Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($pastSessions as $session)
                                                <tr>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <img src="{{ $session->mentee->profile_image ? asset('storage/' . $session->mentee->profile_image) : asset('images/default-avatar.png') }}" class="rounded-circle mr-2" width="40" height="40" alt="{{ $session->mentee->name }}">
                                                            <div>{{ $session->mentee->name }}</div>
                                                        </div>
                                                    </td>
                                                    <td>{{ $session->scheduled_at->format('d/m/Y H:i') }}</td>
                                                    <td>{{ $session->duration }} min</td>
                                                    <td>{{ $session->course ? $session->course->title : 'N/A' }}</td>
                                                    <td>
                                                        <span class="badge badge-secondary">Completada</span>
                                                    </td>
                                                    <td>
                                                        @php
                                                            $review = $session->reviews()->where('is_mentor_review', false)->first();
                                                        @endphp
                                                        
                                                        @if($review)
                                                            <div class="text-warning">
                                                                @for($i = 1; $i <= 5; $i++)
                                                                    <i class="fas fa-star{{ $i <= $review->rating ? '' : '-o' }}"></i>
                                                                @endfor
                                                                <span class="text-muted">({{ $review->rating }})</span>
                                                            </div>
                                                        @else
                                                            <span class="text-muted">Sin valoración</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <div class="btn-group">
                                                            <a href="{{ route('mentor.sessions.show', $session->id) }}" class="btn btn-sm btn-info">
                                                                <i class="fas fa-eye"></i>
                                                            </a>
                                                            @if(!$session->reviews()->where('is_mentor_review', true)->exists())
                                                                <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#reviewSessionModal" data-session-id="{{ $session->id }}" data-student-name="{{ $session->mentee->name }}">
                                                                    <i class="fas fa-star"></i>
                                                                </button>
                                                            @endif
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                
                                <div class="mt-4">
                                    {{ $pastSessions->links() }}
                                </div>
                            @else
                                <div class="alert alert-info">
                                    <p>No tienes sesiones de mentoría completadas.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal para iniciar sesión -->
<div class="modal fade" id="startSessionModal" tabindex="-1" role="dialog" aria-labelledby="startSessionModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="startSessionModalLabel">Iniciar Sesión</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Estás a punto de iniciar una sesión de mentoría con <strong id="sessionStudentName"></strong>.</p>
                
                <div class="form-group">
                    <label for="meetingUrl">URL de la reunión</label>
                    <input type="url" class="form-control" id="meetingUrl" placeholder="https://meet.google.com/...">
                    <small class="form-text text-muted">Puedes usar Google Meet, Zoom, Microsoft Teams, etc.</small>
                </div>
                
                <div class="form-group">
                    <label for="sessionNotes">Notas para la sesión</label>
                    <textarea class="form-control" id="sessionNotes" rows="3" placeholder="Temas a tratar, objetivos, etc."></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <form action="#" method="POST">
                    @csrf
                    <input type="hidden" id="startSessionId" name="session_id">
                    <input type="hidden" id="startMeetingUrl" name="meeting_url">
                    <input type="hidden" id="startSessionNotes" name="notes">
                    
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success">Iniciar Sesión</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal para aceptar solicitud -->
<div class="modal fade" id="acceptRequestModal" tabindex="-1" role="dialog" aria-labelledby="acceptRequestModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="acceptRequestModalLabel">Aceptar Solicitud</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="#" method="POST">
                @csrf
                <input type="hidden" id="acceptRequestId" name="request_id">
                <input type="hidden" name="status" value="confirmed">
                
                <div class="modal-body">
                    <p>Estás a punto de aceptar la solicitud de mentoría de <strong id="acceptStudentName"></strong>.</p>
                    
                    <div class="form-group">
                        <label for="acceptMessage">Mensaje para el estudiante (opcional)</label>
                        <textarea class="form-control" id="acceptMessage" name="message" rows="3" placeholder="Estoy encantado de poder ayudarte..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success">Aceptar Solicitud</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal para rechazar solicitud -->
<div class="modal fade" id="rejectRequestModal" tabindex="-1" role="dialog" aria-labelledby="rejectRequestModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="rejectRequestModalLabel">Rechazar Solicitud</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="#" method="POST">
                @csrf
                <input type="hidden" id="rejectRequestId" name="request_id">
                <input type="hidden" name="status" value="rejected">
                
                <div class="modal-body">
                    <p>Estás a punto de rechazar la solicitud de mentoría de <strong id="rejectStudentName"></strong>.</p>
                    
                    <div class="form-group">
                        <label for="proposedTime">Proponer fecha alternativa</label>
                        <input type="datetime-local" class="form-control" id="proposedTime" name="proposed_time" required>
                        <small class="form-text text-muted">Propón una fecha y hora alternativa para la sesión.</small>
                    </div>
                    
                    <div class="form-group">
                        <label for="rejectMessage">Mensaje para el estudiante</label>
                        <textarea class="form-control" id="rejectMessage" name="message" rows="3" placeholder="Lo siento, no puedo en ese horario pero te propongo..." required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-danger">Rechazar y Proponer</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal para valorar sesión -->
<div class="modal fade" id="reviewSessionModal" tabindex="-1" role="dialog" aria-labelledby="reviewSessionModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="reviewSessionModalLabel">Valorar Sesión</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="#" method="POST">
                @csrf
                <input type="hidden" id="reviewSessionId" name="session_id">
                
                <div class="modal-body">
                    <p>Valora tu sesión de mentoría con <strong id="reviewStudentName"></strong>.</p>
                    
                    <div class="form-group">
                        <label>Puntuación</label>
                        <div class="rating">
                            <div class="d-flex justify-content-center">
                                <div class="rate">
                                    <input type="radio" id="star5" name="rating" value="5" />
                                    <label for="star5" title="5 estrellas">5 stars</label>
                                    <input type="radio" id="star4" name="rating" value="4" />
                                    <label for="star4" title="4 estrellas">4 stars</label>
                                    <input type="radio" id="star3" name="rating" value="3" />
                                    <label for="star3" title="3 estrellas">3 stars</label>
                                    <input type="radio" id="star2" name="rating" value="2" />
                                    <label for="star2" title="2 estrellas">2 stars</label>
                                    <input type="radio" id="star1" name="rating" value="1" />
                                    <label for="star1" title="1 estrella">1 star</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="reviewComment">Comentario</label>
                        <textarea class="form-control" id="reviewComment" name="comment" rows="4" placeholder="Comparte tu experiencia con el estudiante..." required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Enviar Valoración</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .rating {
        margin-bottom: 20px;
    }
    
    .rate {
        display: inline-block;
        border: 0;
    }
    
    .rate > input {
        display: none;
    }
    
    .rate > label {
        float: right;
        color: #ddd;
        font-size: 30px;
    }
    
    .rate > label:before {
        display: inline-block;
        content: "\f005";
        font-family: "Font Awesome 5 Free";
        font-weight: 900;
        padding: 0 5px;
    }
    
    .rate > input:checked ~ label,
    .rate > input:hover ~ label {
        color: #ffc107;
    }
</style>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Iniciar sesión
        $('#startSessionModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            var sessionId = button.data('session-id');
            var sessionTitle = button.data('session-title');
            var studentName = button.data('student-name');
            
            var modal = $(this);
            modal.find('#startSessionId').val(sessionId);
            modal.find('#sessionStudentName').text(studentName);
            
            // Actualizar campos al enviar
            modal.find('form').on('submit', function() {
                $('#startMeetingUrl').val($('#meetingUrl').val());
                $('#startSessionNotes').val($('#sessionNotes').val());
            });
        });
        
        // Aceptar solicitud
        $('#acceptRequestModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            var requestId = button.data('request-id');
            var studentName = button.data('student-name');
            
            var modal = $(this);
            modal.find('#acceptRequestId').val(requestId);
            modal.find('#acceptStudentName').text(studentName);
        });
        
        // Rechazar solicitud
        $('#rejectRequestModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            var requestId = button.data('request-id');
            var studentName = button.data('student-name');
            
            var modal = $(this);
            modal.find('#rejectRequestId').val(requestId);
            modal.find('#rejectStudentName').text(studentName);
            
            // Establecer fecha mínima como hoy
            var today = new Date();
            var dd = String(today.getDate()).padStart(2, '0');
            var mm = String(today.getMonth() + 1).padStart(2, '0');
            var yyyy = today.getFullYear();
            var hh = String(today.getHours()).padStart(2, '0');
            var min = String(today.getMinutes()).padStart(2, '0');
            
            today = yyyy + '-' + mm + '-' + dd + 'T' + hh + ':' + min;
            document.getElementById("proposedTime").min = today;
        });
        
        // Valorar sesión
        $('#reviewSessionModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            var sessionId = button.data('session-id');
            var studentName = button.data('student-name');
            
            var modal = $(this);
            modal.find('#reviewSessionId').val(sessionId);
            modal.find('#reviewStudentName').text(studentName);
        });
    });
</script>
@endsection
