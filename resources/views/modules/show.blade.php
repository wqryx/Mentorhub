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
                    <li class="breadcrumb-item"><a href="{{ route('courses.modules.index', $course) }}">Módulos</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $module->name }}</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-8">
            <h1 class="display-5 fw-bold text-primary">{{ $module->name }}</h1>
            <p class="text-muted">
                <span class="me-2"><i class="fas fa-book me-1"></i> Módulo {{ $module->order }} de {{ $course->modules->count() }}</span>
                <span class="me-2"><i class="fas fa-clock me-1"></i> {{ $module->duration_hours }} horas</span>
                @if($module->is_published)
                    <span class="badge bg-success"><i class="fas fa-check-circle me-1"></i> Publicado</span>
                @else
                    <span class="badge bg-secondary"><i class="fas fa-clock me-1"></i> Borrador</span>
                @endif
            </p>
        </div>
        <div class="col-md-4 text-end">
            @if(auth()->user()->id === $course->created_by || auth()->user()->hasRole('Admin'))
                <div class="btn-group" role="group">
                    <a href="{{ route('courses.modules.tutorials.create', [$course, $module]) }}" class="btn btn-outline-success">
                        <i class="fas fa-plus-circle me-1"></i> Añadir Tutorial
                    </a>
                    <a href="{{ route('courses.modules.edit', [$course, $module]) }}" class="btn btn-outline-primary">
                        <i class="fas fa-edit me-1"></i> Editar
                    </a>
                    <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteModal">
                        <i class="fas fa-trash me-1"></i> Eliminar
                    </button>
                </div>
            @endif
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-8">
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <h5 class="card-title border-bottom pb-3 mb-3">Descripción del módulo</h5>
                    <p class="card-text">{{ $module->description }}</p>
                </div>
            </div>

            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title border-bottom pb-3 mb-3">Contenido del módulo</h5>
                    
                    @if($tutorials->isEmpty())
                        <p class="text-muted">Este módulo aún no tiene tutoriales.
                        @if(auth()->user()->id === $course->created_by || auth()->user()->hasRole('Admin'))
                            <a href="{{ route('courses.modules.tutorials.create', [$course, $module]) }}">Añade el primer tutorial</a>.
                        @endif
                        </p>
                    @else
                        <div class="list-group" id="tutorials-list">
                            @foreach($tutorials as $tutorial)
                                <div class="list-group-item list-group-item-action d-flex justify-content-between align-items-center" data-tutorial-id="{{ $tutorial->id }}">
                                    <div class="ms-2 me-auto">
                                        <div class="fw-bold">
                                            <a href="{{ route('courses.modules.tutorials.show', [$course, $module, $tutorial]) }}" class="text-decoration-none">
                                                <i class="fas fa-{{ $tutorial->video_url ? 'video' : 'file-alt' }} me-2 text-primary"></i>
                                                {{ $tutorial->title }}
                                            </a>
                                            @if($tutorial->is_premium)
                                                <span class="badge bg-warning ms-2">Premium</span>
                                            @endif
                                        </div>
                                        <div class="text-muted small">
                                            {{ $tutorial->duration_minutes }} minutos · 
                                            {{ $tutorial->contents->count() }} contenidos
                                        </div>
                                    </div>
                                    <div>
                                        @if($tutorial->is_published)
                                            <span class="badge bg-success me-2">Publicado</span>
                                        @else
                                            <span class="badge bg-secondary me-2">Borrador</span>
                                        @endif

                                        @if(auth()->user()->id === $course->created_by || auth()->user()->hasRole('Admin'))
                                            <div class="btn-group">
                                                <a href="{{ route('courses.modules.tutorials.contents.create', [$course, $module, $tutorial]) }}" class="btn btn-sm btn-outline-success" title="Añadir contenido">
                                                    <i class="fas fa-plus"></i>
                                                </a>
                                                <a href="{{ route('courses.modules.tutorials.edit', [$course, $module, $tutorial]) }}" class="btn btn-sm btn-outline-primary" title="Editar tutorial">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteTutorialModal{{ $tutorial->id }}" title="Eliminar tutorial">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm mb-4">
                <div class="position-relative">
                    @if($module->image)
                        <img src="{{ asset('storage/' . $module->image) }}" class="card-img-top" alt="{{ $module->name }}">
                    @else
                        <img src="{{ asset('images/default-module.jpg') }}" class="card-img-top" alt="{{ $module->name }}">
                    @endif
                </div>
                
                <div class="card-body">
                    <h5 class="card-title">Navegación del módulo</h5>
                    
                    <div class="d-grid gap-2 mb-3">
                        @if($prevModule)
                            <a href="{{ route('courses.modules.show', [$course, $prevModule]) }}" class="btn btn-outline-primary">
                                <i class="fas fa-arrow-left me-1"></i> Módulo anterior: {{ Str::limit($prevModule->name, 20) }}
                            </a>
                        @else
                            <button class="btn btn-outline-secondary" disabled>
                                <i class="fas fa-arrow-left me-1"></i> No hay módulo anterior
                            </button>
                        @endif
                        
                        @if($nextModule)
                            <a href="{{ route('courses.modules.show', [$course, $nextModule]) }}" class="btn btn-outline-primary">
                                Módulo siguiente: {{ Str::limit($nextModule->name, 20) }} <i class="fas fa-arrow-right ms-1"></i>
                            </a>
                        @else
                            <button class="btn btn-outline-secondary" disabled>
                                No hay módulo siguiente <i class="fas fa-arrow-right ms-1"></i>
                            </button>
                        @endif
                    </div>

                    <div class="list-group">
                        <a href="{{ route('courses.show', $course) }}" class="list-group-item list-group-item-action">
                            <i class="fas fa-chevron-left me-2"></i> Volver al curso
                        </a>
                        
                        @if(!$tutorials->isEmpty())
                            <a href="{{ route('courses.modules.tutorials.show', [$course, $module, $tutorials->first()]) }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                <span><i class="fas fa-play-circle me-2"></i> Comenzar este módulo</span>
                                <i class="fas fa-chevron-right"></i>
                            </a>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Información del curso -->
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title border-bottom pb-2 mb-3">Sobre el curso</h5>
                    
                    <h6 class="card-subtitle mb-2">{{ $course->title }}</h6>
                    <p class="card-text small">{{ Str::limit($course->description, 150) }}</p>
                    
                    <ul class="list-group list-group-flush mb-3">
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <span><i class="fas fa-book me-2"></i> Módulos</span>
                            <span>{{ $course->modules->count() }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <span><i class="fas fa-video me-2"></i> Tutoriales</span>
                            <span>{{ $course->modules->flatMap->tutorials->count() }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <span><i class="fas fa-clock me-2"></i> Duración total</span>
                            <span>{{ $course->duration_hours }} horas</span>
                        </li>
                    </ul>
                    
                    <a href="{{ route('courses.show', $course) }}" class="btn btn-outline-primary w-100">
                        Ver detalles del curso
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de confirmación para eliminar el módulo -->
@if(auth()->user()->id === $course->created_by || auth()->user()->hasRole('Admin'))
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Confirmar eliminación</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>¿Estás seguro de que deseas eliminar este módulo? Esta acción no se puede deshacer y eliminará todos los tutoriales y contenidos asociados.</p>
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

<!-- Modales de confirmación para eliminar cada tutorial -->
@foreach($tutorials as $tutorial)
<div class="modal fade" id="deleteTutorialModal{{ $tutorial->id }}" tabindex="-1" aria-labelledby="deleteTutorialModalLabel{{ $tutorial->id }}" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteTutorialModalLabel{{ $tutorial->id }}">Confirmar eliminación</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>¿Estás seguro de que deseas eliminar el tutorial <strong>{{ $tutorial->title }}</strong>? Esta acción no se puede deshacer y eliminará todos los contenidos asociados.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <form action="{{ route('courses.modules.tutorials.destroy', [$course, $module, $tutorial]) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Eliminar permanentemente</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endforeach
@endif
@endsection

@push('scripts')
@if(auth()->user()->id === $course->created_by || auth()->user()->hasRole('Admin'))
<script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
<script>
    // Hacer la lista de tutoriales ordenable mediante arrastrar y soltar
    const tutorialsList = document.getElementById('tutorials-list');
    if (tutorialsList) {
        new Sortable(tutorialsList, {
            animation: 150,
            ghostClass: 'border-primary',
            handle: '.list-group-item',
            onEnd: function(evt) {
                const tutorialIds = Array.from(tutorialsList.querySelectorAll('[data-tutorial-id]'))
                    .map(el => el.getAttribute('data-tutorial-id'));
                
                // Enviar la nueva ordenación al servidor
                fetch('{{ route("courses.modules.tutorials.reorder", [$course, $module]) }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ tutorials: tutorialIds })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        console.log('Orden de tutoriales actualizado');
                    } else {
                        console.error('Error al actualizar el orden de los tutoriales');
                    }
                });
            }
        });
    }
</script>
@endif
@endpush
