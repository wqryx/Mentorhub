@extends('layouts.mentor')

@section('title', 'Perfil de Estudiante - ' . $student->name)

@push('styles')
<link rel="stylesheet" href="{{ asset('css/mentor-student-profile.css') }}">
@endpush

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Perfil de Estudiante</h1>
        <div>
            <a href="{{ route('mentor.students') }}" class="btn btn-sm btn-secondary">
                <i class="fas fa-arrow-left mr-1"></i> Volver a la lista
            </a>
            <a href="{{ route('mentor.sessions.create', ['mentee_id' => $student->id]) }}" class="btn btn-sm btn-primary">
                <i class="fas fa-calendar-plus mr-1"></i> Programar Sesión
            </a>
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    @endif

    <div class="row">
        <!-- Columna de información del perfil -->
        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Información del Estudiante</h6>
                </div>
                <div class="card-body">
                    <div class="text-center mb-4">
                        @if($student->profile && $student->profile->avatar)
                            <img src="{{ Storage::url($student->profile->avatar) }}" alt="{{ $student->name }}" class="img-profile rounded-circle" style="width: 150px; height: 150px; object-fit: cover;">
                        @else
                            <div class="avatar-placeholder rounded-circle bg-primary text-white d-flex align-items-center justify-content-center mx-auto" style="width: 150px; height: 150px;">
                                <span style="font-size: 3rem;">{{ strtoupper(substr($student->name, 0, 1)) }}</span>
                            </div>
                        @endif
                        <h4 class="mt-3">{{ $student->name }}</h4>
                        <p class="text-muted">Estudiante desde {{ $student->created_at->format('d M, Y') }}</p>
                        
                        <div class="d-flex justify-content-center mt-3">
                            <a href="mailto:{{ $student->email }}" class="btn btn-circle btn-info mx-1" title="Enviar Email">
                                <i class="fas fa-envelope"></i>
                            </a>
                            @if($student->profile && $student->profile->linkedin_url)
                            <a href="{{ $student->profile->linkedin_url }}" target="_blank" class="btn btn-circle btn-primary mx-1" title="LinkedIn">
                                <i class="fab fa-linkedin-in"></i>
                            </a>
                            @endif
                            @if($student->profile && $student->profile->github_url)
                            <a href="{{ $student->profile->github_url }}" target="_blank" class="btn btn-circle btn-dark mx-1" title="GitHub">
                                <i class="fab fa-github"></i>
                            </a>
                            @endif
                        </div>
                    </div>
                    
                    <hr>
                    
                    <div class="info-group mb-3">
                        <h6 class="font-weight-bold">Email:</h6>
                        <p>{{ $student->email }}</p>
                    </div>
                    
                    @if($student->profile)
                        @if($student->profile->phone)
                        <div class="info-group mb-3">
                            <h6 class="font-weight-bold">Teléfono:</h6>
                            <p>{{ $student->profile->phone }}</p>
                        </div>
                        @endif
                        
                        @if($student->profile->timezone)
                        <div class="info-group mb-3">
                            <h6 class="font-weight-bold">Zona Horaria:</h6>
                            <p>{{ $student->profile->timezone }}</p>
                        </div>
                        @endif
                        
                        @if($student->profile->bio)
                        <div class="info-group mb-3">
                            <h6 class="font-weight-bold">Biografía:</h6>
                            <p>{{ $student->profile->bio }}</p>
                        </div>
                        @endif
                    @endif
                    
                    <div class="info-group mb-3">
                        <h6 class="font-weight-bold">Última Actividad:</h6>
                        <p>{{ $student->last_activity_at ? $student->last_activity_at->format('d M, Y H:i') : 'Sin actividad registrada' }}</p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Columna de cursos y sesiones -->
        <div class="col-lg-8">
            <!-- Cursos inscritos -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Cursos Inscritos</h6>
                </div>
                <div class="card-body">
                    @if($student->enrollments->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-bordered" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Curso</th>
                                    <th>Progreso</th>
                                    <th>Fecha de Inscripción</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($student->enrollments as $enrollment)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if($enrollment->course->image)
                                                <img src="{{ Storage::url($enrollment->course->image) }}" alt="{{ $enrollment->course->title }}" class="mr-2" style="width: 40px; height: 40px; object-fit: cover;">
                                            @else
                                                <div class="bg-primary text-white d-flex align-items-center justify-content-center mr-2" style="width: 40px; height: 40px;">
                                                    <i class="fas fa-book"></i>
                                                </div>
                                            @endif
                                            <div>
                                                <a href="{{ route('mentor.courses.show', $enrollment->course->id) }}" class="font-weight-bold">{{ $enrollment->course->title }}</a>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="progress">
                                            <div class="progress-bar bg-success" role="progressbar" style="width: {{ $enrollment->progress }}%" aria-valuenow="{{ $enrollment->progress }}" aria-valuemin="0" aria-valuemax="100">{{ $enrollment->progress }}%</div>
                                        </div>
                                    </td>
                                    <td>{{ $enrollment->created_at->format('d M, Y') }}</td>
                                    <td>
                                        <a href="{{ route('mentor.courses.show', $enrollment->course->id) }}" class="btn btn-sm btn-primary" title="Ver Curso">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="text-center py-3">
                        <p class="text-muted">Este estudiante no está inscrito en ningún curso.</p>
                    </div>
                    @endif
                </div>
            </div>
            
            <!-- Sesiones de mentoría -->
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Sesiones de Mentoría</h6>
                    <a href="{{ route('mentor.sessions.create', ['mentee_id' => $student->id]) }}" class="btn btn-sm btn-primary">
                        <i class="fas fa-plus mr-1"></i> Nueva Sesión
                    </a>
                </div>
                <div class="card-body">
                    @if($sessions->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-bordered" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Título</th>
                                    <th>Fecha</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($sessions as $session)
                                <tr>
                                    <td>{{ $session->title }}</td>
                                    <td>{{ $session->scheduled_at->format('d M, Y H:i') }}</td>
                                    <td>
                                        @if($session->status == 'scheduled')
                                            <span class="badge badge-primary">Programada</span>
                                        @elseif($session->status == 'completed')
                                            <span class="badge badge-success">Completada</span>
                                        @elseif($session->status == 'cancelled')
                                            <span class="badge badge-danger">Cancelada</span>
                                        @elseif($session->status == 'pending')
                                            <span class="badge badge-warning">Pendiente</span>
                                        @else
                                            <span class="badge badge-secondary">{{ ucfirst($session->status) }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('mentor.sessions.show', $session->id) }}" class="btn btn-sm btn-primary" title="Ver Detalles">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-3">
                        {{ $sessions->links() }}
                    </div>
                    @else
                    <div class="text-center py-3">
                        <p class="text-muted">No hay sesiones de mentoría registradas con este estudiante.</p>
                        <a href="{{ route('mentor.sessions.create', ['mentee_id' => $student->id]) }}" class="btn btn-primary">
                            <i class="fas fa-calendar-plus mr-1"></i> Programar Primera Sesión
                        </a>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
