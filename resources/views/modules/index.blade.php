@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row mb-4">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('courses.index') }}">Cursos</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('courses.show', $course) }}">{{ $course->title }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Módulos</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-8">
            <h1 class="display-5 fw-bold text-primary">{{ $course->title }}</h1>
            <p class="text-muted">Módulos del curso</p>
        </div>
        @if(auth()->user()->id === $course->created_by || auth()->user()->hasRole('Admin'))
        <div class="col-md-4 text-end">
            <a href="{{ route('courses.modules.create', $course) }}" class="btn btn-primary">
                <i class="fas fa-plus-circle me-1"></i> Añadir Módulo
            </a>
        </div>
        @endif
    </div>

    @if($modules->isEmpty())
        <div class="alert alert-info" role="alert">
            <i class="fas fa-info-circle me-2"></i> Este curso aún no tiene módulos.
            @if(auth()->user()->id === $course->created_by || auth()->user()->hasRole('Admin'))
                <a href="{{ route('courses.modules.create', $course) }}" class="alert-link">Añade el primer módulo</a>.
            @endif
        </div>
    @else
        <div class="row">
            <div class="col-md-12">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title mb-3">Contenido del curso</h5>

                        <div class="list-group" id="modules-list">
                            @foreach($modules->sortBy('order') as $module)
                                <div class="list-group-item list-group-item-action d-flex justify-content-between align-items-center" data-module-id="{{ $module->id }}">
                                    <div class="ms-2 me-auto">
                                        <div class="fw-bold">
                                            <a href="{{ route('courses.modules.show', [$course, $module]) }}" class="text-decoration-none">
                                                {{ $module->name }}
                                            </a>
                                        </div>
                                        <div class="text-muted small">
                                            {{ $module->tutorials->count() }} lecciones · {{ $module->duration_hours }} horas
                                        </div>
                                    </div>
                                    <div>
                                        @if($module->is_published)
                                            <span class="badge bg-success me-2">Publicado</span>
                                        @else
                                            <span class="badge bg-secondary me-2">Borrador</span>
                                        @endif

                                        @if(auth()->user()->id === $course->created_by || auth()->user()->hasRole('Admin'))
                                            <div class="btn-group">
                                                <a href="{{ route('courses.modules.tutorials.create', [$course, $module]) }}" class="btn btn-sm btn-outline-success" title="Añadir tutorial">
                                                    <i class="fas fa-plus"></i>
                                                </a>
                                                <a href="{{ route('courses.modules.edit', [$course, $module]) }}" class="btn btn-sm btn-outline-primary" title="Editar módulo">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $module->id }}" title="Eliminar módulo">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Mostrar estadísticas si es el creador o admin -->
    @if(auth()->user()->id === $course->created_by || auth()->user()->hasRole('Admin'))
        <div class="row mt-5">
            <div class="col-md-12">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title mb-3">Estadísticas del curso</h5>
                        
                        <div class="row text-center">
                            <div class="col-md-3">
                                <div class="p-3 border rounded">
                                    <div class="h3 mb-0">{{ $course->students->count() }}</div>
                                    <div class="small text-muted">Estudiantes inscritos</div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="p-3 border rounded">
                                    <div class="h3 mb-0">{{ $modules->count() }}</div>
                                    <div class="small text-muted">Módulos</div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="p-3 border rounded">
                                    <div class="h3 mb-0">{{ $modules->flatMap->tutorials->count() }}</div>
                                    <div class="small text-muted">Tutoriales</div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="p-3 border rounded">
                                    <div class="h3 mb-0">{{ $course->duration_hours }}</div>
                                    <div class="small text-muted">Horas de contenido</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

<!-- Modales de confirmación para eliminar cada módulo -->
@foreach($modules as $module)
    <div class="modal fade" id="deleteModal{{ $module->id }}" tabindex="-1" aria-labelledby="deleteModalLabel{{ $module->id }}" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel{{ $module->id }}">Confirmar eliminación</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>¿Estás seguro de que deseas eliminar el módulo <strong>{{ $module->name }}</strong>? Esta acción no se puede deshacer y eliminará todos los tutoriales y contenidos asociados.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <form action="{{ route('courses.modules.destroy', [$course, $module]) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Eliminar permanentemente</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endforeach
@endsection

@push('scripts')
@if(auth()->user()->id === $course->created_by || auth()->user()->hasRole('Admin'))
<script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
<script>
    // Hacer la lista de módulos ordenable mediante arrastrar y soltar
    const modulesList = document.getElementById('modules-list');
    if (modulesList) {
        new Sortable(modulesList, {
            animation: 150,
            ghostClass: 'border-primary',
            handle: '.list-group-item',
            onEnd: function(evt) {
                const moduleIds = Array.from(modulesList.querySelectorAll('[data-module-id]'))
                    .map(el => el.getAttribute('data-module-id'));
                
                // Enviar la nueva ordenación al servidor
                fetch('{{ route("courses.modules.reorder", $course) }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ modules: moduleIds })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        console.log('Orden de módulos actualizado');
                    } else {
                        console.error('Error al actualizar el orden de los módulos');
                    }
                });
            }
        });
    }
</script>
@endif
@endpush
