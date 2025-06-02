@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/mentor-courses.css') }}">
<link rel="stylesheet" href="{{ asset('css/mentor-course-students.css') }}">
@endpush

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Estudiantes Inscritos en: {{ $course->title }}</h5>
                    <div class="card-tools">
                        <a href="{{ route('mentor.courses.show', $course->id) }}" class="btn btn-sm btn-primary">
                            <i class="fas fa-arrow-left"></i> Volver al Curso
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if($students->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Estudiante</th>
                                        <th>Email</th>
                                        <th>Fecha de inscripción</th>
                                        <th>Progreso</th>
                                        <th>Última actividad</th>
                                        <th>Completado</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($students as $student)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <img src="{{ $student->profile_image ? asset('storage/' . $student->profile_image) : asset('images/default-avatar.png') }}" class="rounded-circle mr-2" width="40" height="40" alt="{{ $student->name }}">
                                                    <div>
                                                        <div>{{ $student->name }}</div>
                                                        @if($student->pivot->is_favorite)
                                                            <span class="badge badge-warning"><i class="fas fa-star"></i> Destacado</span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>
                                            <td>{{ $student->email }}</td>
                                            <td>{{ $student->pivot->created_at->format('d/m/Y') }}</td>
                                            <td>
                                                <div class="progress" style="height: 20px;">
                                                    <div class="progress-bar {{ $student->pivot->progress >= 100 ? 'bg-success' : 'bg-primary' }}" role="progressbar" style="width: {{ $student->pivot->progress }}%;" aria-valuenow="{{ $student->pivot->progress }}" aria-valuemin="0" aria-valuemax="100">{{ $student->pivot->progress }}%</div>
                                                </div>
                                            </td>
                                            <td>{{ $student->pivot->last_activity ? $student->pivot->last_activity->diffForHumans() : 'Nunca' }}</td>
                                            <td>
                                                @if($student->pivot->completed_at)
                                                    <span class="badge badge-success">Sí - {{ $student->pivot->completed_at->format('d/m/Y') }}</span>
                                                @else
                                                    <span class="badge badge-secondary">No</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="btn-group">
                                                    <button type="button" class="btn btn-sm btn-primary dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                                        Acciones
                                                    </button>
                                                    <div class="dropdown-menu">
                                                        <a class="dropdown-item" href="#" data-toggle="modal" data-target="#messageModal" data-student-id="{{ $student->id }}" data-student-name="{{ $student->name }}">
                                                            <i class="fas fa-envelope"></i> Enviar mensaje
                                                        </a>
                                                        <a class="dropdown-item" href="#" data-toggle="modal" data-target="#scheduleSessionModal" data-student-id="{{ $student->id }}" data-student-name="{{ $student->name }}">
                                                            <i class="fas fa-calendar-plus"></i> Programar sesión
                                                        </a>
                                                        <div class="dropdown-divider"></div>
                                                        <form action="#" method="POST" class="d-inline">
                                                            @csrf
                                                            @method('PUT')
                                                            <input type="hidden" name="is_favorite" value="{{ $student->pivot->is_favorite ? 0 : 1 }}">
                                                            <button type="submit" class="dropdown-item">
                                                                <i class="fas {{ $student->pivot->is_favorite ? 'fa-star text-warning' : 'fa-star' }}"></i> 
                                                                {{ $student->pivot->is_favorite ? 'Quitar destacado' : 'Destacar estudiante' }}
                                                            </button>
                                                        </form>
                                                        <div class="dropdown-divider"></div>
                                                        <form action="#" method="POST" class="d-inline" onsubmit="return confirm('¿Estás seguro de que deseas eliminar a este estudiante del curso?');">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="dropdown-item text-danger">
                                                                <i class="fas fa-user-minus"></i> Eliminar del curso
                                                            </button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="mt-4">
                            {{ $students->links() }}
                        </div>
                    @else
                        <div class="alert alert-info">
                            <p>Aún no hay estudiantes inscritos en este curso.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal para enviar mensaje -->
<div class="modal fade" id="messageModal" tabindex="-1" role="dialog" aria-labelledby="messageModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="messageModalLabel">Enviar mensaje a <span id="studentName"></span></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="#" method="POST">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="recipient_id" id="studentId">
                    <div class="form-group">
                        <label for="subject">Asunto</label>
                        <input type="text" class="form-control" id="subject" name="subject" required>
                    </div>
                    <div class="form-group">
                        <label for="message">Mensaje</label>
                        <textarea class="form-control" id="message" name="message" rows="5" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Enviar mensaje</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal para programar sesión -->
<div class="modal fade" id="scheduleSessionModal" tabindex="-1" role="dialog" aria-labelledby="scheduleSessionModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="scheduleSessionModalLabel">Programar sesión con <span id="sessionStudentName"></span></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('mentor.sessions.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="mentee_id" id="sessionStudentId">
                    <input type="hidden" name="course_id" value="{{ $course->id }}">
                    
                    <div class="form-group">
                        <label for="title">Título de la sesión</label>
                        <input type="text" class="form-control" id="title" name="title" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="description">Descripción</label>
                        <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label for="scheduled_at">Fecha y hora</label>
                        <input type="datetime-local" class="form-control" id="scheduled_at" name="scheduled_at" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="duration">Duración (minutos)</label>
                        <input type="number" class="form-control" id="duration" name="duration" min="15" max="240" value="60" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="meeting_url">URL de la reunión (opcional)</label>
                        <input type="url" class="form-control" id="meeting_url" name="meeting_url" placeholder="https://meet.google.com/...">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Programar sesión</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        $('#messageModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            var studentId = button.data('student-id');
            var studentName = button.data('student-name');
            
            var modal = $(this);
            modal.find('#studentId').val(studentId);
            modal.find('#studentName').text(studentName);
        });
        
        $('#scheduleSessionModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            var studentId = button.data('student-id');
            var studentName = button.data('student-name');
            
            var modal = $(this);
            modal.find('#sessionStudentId').val(studentId);
            modal.find('#sessionStudentName').text(studentName);
            
            // Establecer fecha mínima como hoy
            var today = new Date();
            var dd = String(today.getDate()).padStart(2, '0');
            var mm = String(today.getMonth() + 1).padStart(2, '0');
            var yyyy = today.getFullYear();
            var hh = String(today.getHours()).padStart(2, '0');
            var min = String(today.getMinutes()).padStart(2, '0');
            
            today = yyyy + '-' + mm + '-' + dd + 'T' + hh + ':' + min;
            document.getElementById("scheduled_at").min = today;
        });
    });
</script>
@endsection
