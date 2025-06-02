@extends('layouts.mentor')

@section('title', 'Mis Estudiantes')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/mentor-students.css') }}">
@endpush

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Mis Estudiantes</h1>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    @endif

    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">Estudiantes Asignados</h6>
            <div class="dropdown no-arrow">
                <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in" aria-labelledby="dropdownMenuLink">
                    <div class="dropdown-header">Opciones:</div>
                    <a class="dropdown-item" href="#"><i class="fas fa-file-export fa-sm fa-fw mr-2 text-gray-400"></i>Exportar Lista</a>
                </div>
            </div>
        </div>
        <div class="card-body">
            @if($students->count() > 0)
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Email</th>
                            <th>Sesiones Pendientes</th>
                            <th>Sesiones Completadas</th>
                            <th>Última Actividad</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($students as $student)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar mr-2">
                                        @if($student->profile && $student->profile->avatar)
                                            <img src="{{ Storage::url($student->profile->avatar) }}" alt="{{ $student->name }}" class="rounded-circle" width="40">
                                        @else
                                            <div class="avatar-placeholder rounded-circle bg-primary text-white d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                                {{ strtoupper(substr($student->name, 0, 1)) }}
                                            </div>
                                        @endif
                                    </div>
                                    <div>
                                        <a href="{{ route('mentor.students.show', $student->id) }}" class="font-weight-bold text-primary">{{ $student->name }}</a>
                                        <div class="small text-muted">Desde {{ $student->created_at->format('M Y') }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $student->email }}</td>
                            <td>
                                <span class="badge badge-info">{{ $student->upcoming_sessions }}</span>
                            </td>
                            <td>
                                <span class="badge badge-success">{{ $student->completed_sessions }}</span>
                            </td>
                            <td>{{ $student->last_activity_at ? $student->last_activity_at->diffForHumans() : 'Nunca' }}</td>
                            <td>
                                <div class="btn-group">
                                    <a href="{{ route('mentor.students.show', $student->id) }}" class="btn btn-sm btn-primary" title="Ver Detalles">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('mentor.sessions.create', ['mentee_id' => $student->id]) }}" class="btn btn-sm btn-success" title="Programar Sesión">
                                        <i class="fas fa-calendar-plus"></i>
                                    </a>
                                    <a href="mailto:{{ $student->email }}" class="btn btn-sm btn-info" title="Enviar Email">
                                        <i class="fas fa-envelope"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-3">
                {{ $students->links() }}
            </div>
            @else
            <div class="text-center py-4">
                <img src="{{ asset('img/illustrations/empty-students.svg') }}" alt="No hay estudiantes" class="img-fluid mb-3" style="max-height: 200px;">
                <h5 class="text-muted">Aún no tienes estudiantes asignados</h5>
                <p class="text-muted">Cuando se te asignen estudiantes, aparecerán en esta lista.</p>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        $('#dataTable').DataTable({
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json"
            },
            "pageLength": 10,
            "ordering": true,
            "info": true,
            "autoWidth": false,
            "responsive": true,
            "dom": '<"top"f>rt<"bottom"ip><"clear">'
        });
    });
</script>
@endsection
