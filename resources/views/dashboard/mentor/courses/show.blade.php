@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">{{ $course->title }}</h5>
                    <div class="card-tools">
                        <a href="{{ route('mentor.courses.index') }}" class="btn btn-sm btn-primary">
                            <i class="fas fa-arrow-left"></i> Volver a Mis Cursos
                        </a>
                        <a href="{{ route('mentor.courses.edit', $course->id) }}" class="btn btn-sm btn-warning">
                            <i class="fas fa-edit"></i> Editar Curso
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="mb-4">
                                <h4>Descripción</h4>
                                <div>{!! $course->description !!}</div>
                            </div>
                            
                            @if($course->what_will_learn)
                            <div class="mb-4">
                                <h4>Lo que aprenderás</h4>
                                <div>{!! $course->what_will_learn !!}</div>
                            </div>
                            @endif
                            
                            @if($course->requirements)
                            <div class="mb-4">
                                <h4>Requisitos previos</h4>
                                <div>{!! $course->requirements !!}</div>
                            </div>
                            @endif
                            
                            <div class="mb-4">
                                <h4>Módulos del Curso</h4>
                                @if($course->modules->count() > 0)
                                    <div class="accordion" id="accordionModules">
                                        @foreach($course->modules as $module)
                                            <div class="card">
                                                <div class="card-header" id="heading{{ $module->id }}">
                                                    <h2 class="mb-0">
                                                        <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse" data-target="#collapse{{ $module->id }}" aria-expanded="true" aria-controls="collapse{{ $module->id }}">
                                                            {{ $module->title }} 
                                                            <span class="badge badge-primary ml-2">{{ $module->tutorials->count() }} tutoriales</span>
                                                        </button>
                                                    </h2>
                                                </div>

                                                <div id="collapse{{ $module->id }}" class="collapse" aria-labelledby="heading{{ $module->id }}" data-parent="#accordionModules">
                                                    <div class="card-body">
                                                        <p>{{ $module->description }}</p>
                                                        
                                                        @if($module->tutorials->count() > 0)
                                                            <ul class="list-group">
                                                                @foreach($module->tutorials as $tutorial)
                                                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                                                        {{ $tutorial->title }}
                                                                        <span class="badge badge-info badge-pill">{{ $tutorial->duration }} min</span>
                                                                    </li>
                                                                @endforeach
                                                            </ul>
                                                        @else
                                                            <p class="text-muted">No hay tutoriales en este módulo.</p>
                                                        @endif
                                                        
                                                        <div class="mt-3">
                                                            <a href="#" class="btn btn-sm btn-outline-primary">Editar Módulo</a>
                                                            <a href="#" class="btn btn-sm btn-outline-success">Añadir Tutorial</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="alert alert-info">
                                        <p>Este curso aún no tiene módulos. Añade módulos para organizar el contenido del curso.</p>
                                        <a href="#" class="btn btn-primary mt-2">Añadir Módulo</a>
                                    </div>
                                @endif
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="card mb-4">
                                <div class="card-body text-center">
                                    @if($course->image)
                                        <img src="{{ asset('storage/' . $course->image) }}" class="img-fluid rounded mb-3" alt="{{ $course->title }}">
                                    @else
                                        <img src="{{ asset('images/course-placeholder.jpg') }}" class="img-fluid rounded mb-3" alt="{{ $course->title }}">
                                    @endif
                                    
                                    <div class="d-flex justify-content-between mb-3">
                                        <span class="badge badge-{{ $course->is_published ? 'success' : 'secondary' }} p-2">
                                            {{ $course->is_published ? 'Publicado' : 'Borrador' }}
                                        </span>
                                        
                                        @if($course->is_featured)
                                            <span class="badge badge-warning p-2">Destacado</span>
                                        @endif
                                        
                                        <span class="badge badge-info p-2">
                                            @switch($course->level)
                                                @case('beginner')
                                                    Principiante
                                                    @break
                                                @case('intermediate')
                                                    Intermedio
                                                    @break
                                                @case('advanced')
                                                    Avanzado
                                                    @break
                                                @default
                                                    {{ $course->level }}
                                            @endswitch
                                        </span>
                                    </div>
                                    
                                    <div class="text-left">
                                        <p><strong>Precio:</strong> 
                                            @if($course->price > 0)
                                                {{ number_format($course->price, 2) }} €
                                            @else
                                                <span class="text-success">Gratis</span>
                                            @endif
                                        </p>
                                        <p><strong>Duración:</strong> {{ $course->duration }} horas</p>
                                        <p><strong>Especialidad:</strong> {{ $course->speciality->name ?? 'No especificada' }}</p>
                                        <p><strong>Estudiantes:</strong> {{ $course->students_count ?? 0 }}</p>
                                        <p><strong>Creado:</strong> {{ $course->created_at->format('d/m/Y') }}</p>
                                        <p><strong>Última actualización:</strong> {{ $course->updated_at->format('d/m/Y') }}</p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="card">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0">Acciones</h6>
                                </div>
                                <div class="card-body">
                                    <div class="d-grid gap-2">
                                        <a href="{{ route('mentor.courses.edit', $course->id) }}" class="btn btn-warning mb-2">
                                            <i class="fas fa-edit"></i> Editar curso
                                        </a>
                                        
                                        <a href="{{ route('mentor.courses.students', $course->id) }}" class="btn btn-info mb-2">
                                            <i class="fas fa-users"></i> Ver estudiantes
                                        </a>
                                        
                                        <a href="{{ route('mentor.courses.statistics', $course->id) }}" class="btn btn-primary mb-2">
                                            <i class="fas fa-chart-bar"></i> Estadísticas
                                        </a>
                                        
                                        <form action="{{ route('mentor.courses.duplicate', $course->id) }}" method="POST" class="mb-2">
                                            @csrf
                                            <button type="submit" class="btn btn-secondary btn-block">
                                                <i class="fas fa-copy"></i> Duplicar curso
                                            </button>
                                        </form>
                                        
                                        <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#deleteCourseModal">
                                            <i class="fas fa-trash"></i> Eliminar curso
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de confirmación para eliminar curso -->
<div class="modal fade" id="deleteCourseModal" tabindex="-1" role="dialog" aria-labelledby="deleteCourseModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteCourseModalLabel">Confirmar eliminación</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>¿Estás seguro de que deseas eliminar este curso? Esta acción no se puede deshacer.</p>
                <p><strong>{{ $course->title }}</strong></p>
                
                @if($course->students_count > 0)
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle"></i> Este curso tiene {{ $course->students_count }} estudiantes inscritos. Al eliminarlo, también se eliminarán todas las inscripciones y el progreso de los estudiantes.
                    </div>
                @endif
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <form action="{{ route('mentor.courses.destroy', $course->id) }}" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Eliminar</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
