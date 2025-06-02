@extends('layouts.mentor')

@section('title', 'Detalles de Mentoría - MentorHub')

@section('content')
<div class="container-fluid">
    <!-- Encabezado -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">{{ $mentoria->title }}</h1>
        <div>
            <a href="{{ route('mentor.mentorias.edit', $mentoria->id) }}" class="btn btn-primary me-2">
                <i class="fas fa-edit me-1"></i> Editar
            </a>
            <a href="{{ route('mentor.mentorias.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-1"></i> Volver a Mentorías
            </a>
        </div>
    </div>

    <!-- Mensajes de alerta -->
    @include('partials.alerts')

    <div class="row">
        <!-- Información de la mentoría -->
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Información de la Mentoría</h6>
                    <span class="badge bg-{{ $mentoria->is_active ? 'success' : 'secondary' }}">
                        {{ $mentoria->is_active ? 'Activa' : 'Inactiva' }}
                    </span>
                </div>
                <div class="card-body">
                    <div class="mb-4">
                        <h5 class="font-weight-bold">Descripción</h5>
                        <p>{{ $mentoria->description }}</p>
                    </div>
                    
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h5 class="font-weight-bold">Categoría</h5>
                            <p>{{ ucfirst($mentoria->category) }}</p>
                        </div>
                        <div class="col-md-6">
                            <h5 class="font-weight-bold">Duración</h5>
                            <p>{{ $mentoria->duration }} minutos</p>
                        </div>
                    </div>
                    
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h5 class="font-weight-bold">Precio</h5>
                            <p>{{ $mentoria->price > 0 ? number_format($mentoria->price, 2) . ' €' : 'Gratuita' }}</p>
                        </div>
                        <div class="col-md-6">
                            <h5 class="font-weight-bold">Fecha de creación</h5>
                            <p>{{ $mentoria->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <h5 class="font-weight-bold">Disponibilidad</h5>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Día</th>
                                        <th>Horario</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($mentoria->availability as $day => $hours)
                                        <tr>
                                            <td>
                                                @switch($day)
                                                    @case(1) Lunes @break
                                                    @case(2) Martes @break
                                                    @case(3) Miércoles @break
                                                    @case(4) Jueves @break
                                                    @case(5) Viernes @break
                                                    @case(6) Sábado @break
                                                    @case(7) Domingo @break
                                                @endswitch
                                            </td>
                                            <td>{{ $hours['start'] }} - {{ $hours['end'] }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="2" class="text-center">No hay horarios disponibles</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Estadísticas y acciones -->
        <div class="col-lg-4">
            <!-- Estadísticas -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Estadísticas</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-6 mb-3">
                            <div class="card border-left-primary h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                                Sesiones
                                            </div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $mentoria->sessions_count }}</div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-calendar fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-6 mb-3">
                            <div class="card border-left-success h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                                Completadas
                                            </div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $mentoria->completed_sessions_count }}</div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-6 mb-3">
                            <div class="card border-left-info h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                                Estudiantes
                                            </div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $mentoria->students_count }}</div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-users fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-6 mb-3">
                            <div class="card border-left-warning h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                                Valoración
                                            </div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                {{ number_format($mentoria->average_rating, 1) }}
                                                <small class="text-gray-500">/5</small>
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-star fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Acciones -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Acciones</h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('mentor.sessions.create', ['mentoria_id' => $mentoria->id]) }}" class="btn btn-primary">
                            <i class="fas fa-plus-circle me-1"></i> Crear Sesión
                        </a>
                        
                        <button type="button" class="btn btn-outline-primary" id="toggleStatusBtn" data-id="{{ $mentoria->id }}" data-status="{{ $mentoria->is_active }}">
                            <i class="fas {{ $mentoria->is_active ? 'fa-eye-slash' : 'fa-eye' }} me-1"></i>
                            {{ $mentoria->is_active ? 'Desactivar Mentoría' : 'Activar Mentoría' }}
                        </button>
                        
                        <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteModal">
                            <i class="fas fa-trash-alt me-1"></i> Eliminar Mentoría
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Sesiones de mentoría -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Sesiones de Mentoría</h6>
        </div>
        <div class="card-body">
            @if($sessions->isEmpty())
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    No hay sesiones programadas para esta mentoría.
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-bordered" id="sessionsTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Estudiante</th>
                                <th>Fecha</th>
                                <th>Duración</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($sessions as $session)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img src="{{ $session->mentee->profile_photo_url }}" 
                                                 class="rounded-circle me-2" 
                                                 width="40" 
                                                 height="40" 
                                                 alt="{{ $session->mentee->name }}">
                                            <div>
                                                {{ $session->mentee->name }}
                                                <br>
                                                <small class="text-muted">{{ $session->mentee->email }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $session->scheduled_at->format('d/m/Y H:i') }}</td>
                                    <td>{{ $session->duration }} min</td>
                                    <td>
                                        <span class="badge bg-{{ 
                                            $session->status === 'completed' ? 'success' : 
                                            ($session->status === 'scheduled' ? 'primary' : 
                                            ($session->status === 'pending' ? 'warning' : 'danger')) 
                                        }}">
                                            {{ ucfirst($session->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('mentor.sessions.show', $session->id) }}" class="btn btn-sm btn-primary">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal de confirmación de eliminación -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Confirmar Eliminación</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>¿Estás seguro de que deseas eliminar esta mentoría?</p>
                <p class="text-danger">Esta acción no se puede deshacer y eliminará todas las sesiones asociadas.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <form action="{{ route('mentor.mentorias.destroy', $mentoria->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Eliminar</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Inicializar DataTable
        $('#sessionsTable').DataTable({
            language: {
                url: '//cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json'
            },
            order: [[1, 'desc']]
        });
        
        // Toggle status button
        $('#toggleStatusBtn').on('click', function() {
            const mentoriaId = $(this).data('id');
            const currentStatus = $(this).data('status');
            
            $.ajax({
                url: `/mentor/mentorias/${mentoriaId}/toggle-status`,
                type: 'POST',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    is_active: !currentStatus
                },
                success: function(response) {
                    window.location.reload();
                },
                error: function(error) {
                    console.error('Error:', error);
                    alert('Ha ocurrido un error al cambiar el estado de la mentoría.');
                }
            });
        });
    });
</script>
@endsection
