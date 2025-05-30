@extends('layouts.dashboard.student')

@section('dashboard-content')
<div class="container-fluid">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <div>
            <h1 class="h2">Progreso del Curso</h1>
            <h4 class="text-primary">{{ $course->title }}</h4>
        </div>
        <div class="btn-toolbar mb-2 mb-md-0">
            <div class="btn-group me-2">
                <a href="{{ route('courses.show', $course) }}" class="btn btn-sm btn-outline-primary">
                    <i class="fas fa-eye me-1"></i> Ver curso
                </a>
                <a href="{{ route('student.courses') }}" class="btn btn-sm btn-outline-secondary">
                    <i class="fas fa-arrow-left me-1"></i> Volver a mis cursos
                </a>
            </div>
        </div>
    </div>

    <!-- Resumen del progreso -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h5 class="card-title">Resumen de progreso</h5>
                            <div class="mb-3">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <span>Progreso total</span>
                                    <span>{{ $enrollment->progress }}%</span>
                                </div>
                                <div class="progress" style="height: 20px;">
                                    <div class="progress-bar bg-success" role="progressbar" style="width: {{ $enrollment->progress }}%;" aria-valuenow="{{ $enrollment->progress }}" aria-valuemin="0" aria-valuemax="100">{{ $enrollment->progress }}%</div>
                                </div>
                            </div>
                            
                            <div class="row text-center mt-4">
                                <div class="col-4">
                                    <h4 class="mb-0">{{ $moduleProgress['completed'] }}</h4>
                                    <p class="text-muted">Módulos<br>completados</p>
                                </div>
                                <div class="col-4">
                                    <h4 class="mb-0">{{ $tutorialProgress['completed'] }}</h4>
                                    <p class="text-muted">Tutoriales<br>completados</p>
                                </div>
                                <div class="col-4">
                                    <h4 class="mb-0">{{ $quizProgress['passed'] }}</h4>
                                    <p class="text-muted">Quizzes<br>aprobados</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="card h-100 bg-light">
                                <div class="card-body">
                                    <h5 class="card-title">Información del curso</h5>
                                    <ul class="list-group list-group-flush">
                                        <li class="list-group-item bg-transparent px-0">
                                            <i class="fas fa-book me-2 text-primary"></i> {{ $course->modules->count() }} módulos
                                        </li>
                                        <li class="list-group-item bg-transparent px-0">
                                            <i class="fas fa-video me-2 text-primary"></i> {{ $course->modules->flatMap->tutorials->count() }} tutoriales
                                        </li>
                                        <li class="list-group-item bg-transparent px-0">
                                            <i class="fas fa-clock me-2 text-primary"></i> Duración total: {{ $course->duration_hours }} horas
                                        </li>
                                        <li class="list-group-item bg-transparent px-0">
                                            <i class="fas fa-graduation-cap me-2 text-primary"></i> Nivel: 
                                            @if($course->level == 'beginner')
                                                Principiante
                                            @elseif($course->level == 'intermediate')
                                                Intermedio
                                            @elseif($course->level == 'advanced')
                                                Avanzado
                                            @endif
                                        </li>
                                        <li class="list-group-item bg-transparent px-0">
                                            <i class="fas fa-calendar-alt me-2 text-primary"></i> Inscrito el: {{ $enrollment->created_at->format('d/m/Y') }}
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Progreso por módulo -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h5 class="card-title mb-0">Progreso por módulo</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th style="width: 5%;">Orden</th>
                                    <th style="width: 45%;">Módulo</th>
                                    <th style="width: 15%;">Tutoriales</th>
                                    <th style="width: 15%;">Completados</th>
                                    <th style="width: 15%;">Progreso</th>
                                    <th style="width: 5%;">Acción</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($course->modules->sortBy('order') as $module)
                                    <tr>
                                        <td>{{ $module->order }}</td>
                                        <td>
                                            <strong>{{ $module->name }}</strong>
                                            @if($enrollment->currentTutorial && $enrollment->currentTutorial->module_id == $module->id)
                                                <span class="badge bg-info ms-2">Actual</span>
                                            @endif
                                        </td>
                                        <td>{{ $module->tutorials->count() }}</td>
                                        <td>{{ $moduleCompletionCount[$module->id] ?? 0 }}</td>
                                        <td>
                                            <div class="progress" style="height: 8px;">
                                                @php
                                                    $moduleProgress = $module->tutorials->count() > 0 
                                                        ? round(($moduleCompletionCount[$module->id] ?? 0) / $module->tutorials->count() * 100) 
                                                        : 0;
                                                @endphp
                                                <div class="progress-bar bg-success" role="progressbar" 
                                                    style="width: {{ $moduleProgress }}%;" 
                                                    aria-valuenow="{{ $moduleProgress }}" 
                                                    aria-valuemin="0" 
                                                    aria-valuemax="100">
                                                </div>
                                            </div>
                                            <small class="text-muted">{{ $moduleProgress }}%</small>
                                        </td>
                                        <td>
                                            <a href="#" class="btn btn-sm btn-outline-primary" data-bs-toggle="collapse" data-bs-target="#collapse{{ $module->id }}" aria-expanded="false" aria-controls="collapse{{ $module->id }}">
                                                <i class="fas fa-chevron-down"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    <tr class="collapse" id="collapse{{ $module->id }}">
                                        <td colspan="6" class="p-0">
                                            <div class="p-3 bg-light">
                                                <h6 class="mb-3">Tutoriales en este módulo</h6>
                                                <div class="table-responsive">
                                                    <table class="table table-sm table-bordered mb-0">
                                                        <thead>
                                                            <tr>
                                                                <th style="width: 5%;">Orden</th>
                                                                <th style="width: 55%;">Tutorial</th>
                                                                <th style="width: 15%;">Duración</th>
                                                                <th style="width: 15%;">Estado</th>
                                                                <th style="width: 10%;">Acción</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach($module->tutorials->sortBy('order') as $tutorial)
                                                                <tr>
                                                                    <td>{{ $tutorial->order }}</td>
                                                                    <td>
                                                                        {{ $tutorial->title }}
                                                                        @if($tutorial->is_premium)
                                                                            <span class="badge bg-warning ms-1">Premium</span>
                                                                        @endif
                                                                        @if($enrollment->currentTutorial && $enrollment->currentTutorial->id == $tutorial->id)
                                                                            <span class="badge bg-info ms-1">Actual</span>
                                                                        @endif
                                                                    </td>
                                                                    <td>{{ $tutorial->duration_minutes }} min</td>
                                                                    <td>
                                                                        @if(isset($tutorialCompletionStatus[$tutorial->id]) && $tutorialCompletionStatus[$tutorial->id])
                                                                            <span class="badge bg-success">Completado</span>
                                                                        @elseif(isset($tutorialStartedStatus[$tutorial->id]) && $tutorialStartedStatus[$tutorial->id])
                                                                            <span class="badge bg-primary">En progreso</span>
                                                                        @else
                                                                            <span class="badge bg-secondary">No iniciado</span>
                                                                        @endif
                                                                    </td>
                                                                    <td>
                                                                        <a href="{{ route('courses.modules.tutorials.show', [$course, $module, $tutorial]) }}" class="btn btn-sm btn-primary">
                                                                            @if(isset($tutorialCompletionStatus[$tutorial->id]) && $tutorialCompletionStatus[$tutorial->id])
                                                                                <i class="fas fa-redo"></i>
                                                                            @elseif(isset($tutorialStartedStatus[$tutorial->id]) && $tutorialStartedStatus[$tutorial->id])
                                                                                <i class="fas fa-play"></i>
                                                                            @else
                                                                                <i class="fas fa-play"></i>
                                                                            @endif
                                                                        </a>
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Actividad reciente -->
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h5 class="card-title mb-0">Actividad reciente</h5>
                </div>
                <div class="card-body">
                    @if($recentActivities->isEmpty())
                        <div class="alert alert-info mb-0" role="alert">
                            <i class="fas fa-info-circle me-2"></i> No hay actividad reciente para este curso.
                        </div>
                    @else
                        <div class="timeline">
                            @foreach($recentActivities as $activity)
                                <div class="timeline-item">
                                    <div class="timeline-marker 
                                        @if($activity->action_type == 'completed_tutorial') bg-success
                                        @elseif($activity->action_type == 'started_tutorial') bg-primary
                                        @elseif($activity->action_type == 'passed_quiz') bg-warning
                                        @elseif($activity->action_type == 'completed_module') bg-success
                                        @elseif($activity->action_type == 'completed_course') bg-success
                                        @else bg-info @endif
                                    ">
                                        @if($activity->action_type == 'completed_tutorial')
                                            <i class="fas fa-check"></i>
                                        @elseif($activity->action_type == 'started_tutorial')
                                            <i class="fas fa-play"></i>
                                        @elseif($activity->action_type == 'passed_quiz')
                                            <i class="fas fa-question"></i>
                                        @elseif($activity->action_type == 'completed_module')
                                            <i class="fas fa-book"></i>
                                        @elseif($activity->action_type == 'completed_course')
                                            <i class="fas fa-graduation-cap"></i>
                                        @else
                                            <i class="fas fa-info"></i>
                                        @endif
                                    </div>
                                    <div class="timeline-content">
                                        <div class="timeline-title">
                                            @if($activity->action_type == 'completed_tutorial')
                                                Completaste el tutorial "{{ $activity->actionable->title }}"
                                            @elseif($activity->action_type == 'started_tutorial')
                                                Comenzaste el tutorial "{{ $activity->actionable->title }}"
                                            @elseif($activity->action_type == 'passed_quiz')
                                                Aprobaste el quiz "{{ $activity->actionable->title }}"
                                            @elseif($activity->action_type == 'completed_module')
                                                Completaste el módulo "{{ $activity->actionable->name }}"
                                            @elseif($activity->action_type == 'completed_course')
                                                ¡Completaste el curso!
                                            @else
                                                {{ $activity->action_type }}
                                            @endif
                                        </div>
                                        <div class="timeline-subtitle">{{ $activity->created_at->format('d/m/Y H:i') }}</div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    /* Estilos para la línea de tiempo de actividades */
    .timeline {
        position: relative;
        padding-left: 40px;
    }
    
    .timeline-item {
        position: relative;
        margin-bottom: 25px;
    }
    
    .timeline-item:last-child {
        margin-bottom: 0;
    }
    
    .timeline:before {
        content: '';
        position: absolute;
        top: 0;
        left: 15px;
        height: 100%;
        width: 2px;
        background-color: #e9ecef;
    }
    
    .timeline-marker {
        position: absolute;
        left: -40px;
        width: 30px;
        height: 30px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        z-index: 1;
    }
    
    .timeline-content {
        background-color: #f8f9fa;
        padding: 15px;
        border-radius: 5px;
    }
    
    .timeline-title {
        font-weight: 600;
        margin-bottom: 5px;
    }
    
    .timeline-subtitle {
        font-size: 0.8rem;
        color: #6c757d;
    }
</style>
@endpush
