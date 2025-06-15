@extends('mentor.layouts.app')

@section('title', 'Estadísticas del Curso: ' . ($course->name ?? 'N/A') . ' - MentorHub')

@push('styles')
<style>
    :root {
        --primary-color: #4f46e5;
        --primary-light: #818cf8;
        --secondary-color: #10b981;
        --warning-color: #f59e0b;
        --danger-color: #ef4444;
        --dark-color: #1f2937;
        --light-bg: #f9fafb;
    }
    
    body {
        background-color: var(--light-bg);
        color: #374151;
    }
    
    .stat-card {
        transition: all 0.3s cubic-bezier(.25,.8,.25,1);
        border: none;
        border-radius: 12px;
        overflow: hidden;
        height: 100%;
        background: white;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
    }
    
    .stat-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05), 0 4px 6px -2px rgba(0, 0, 0, 0.03);
    }
    
    .stat-icon {
        font-size: 2rem;
        opacity: 0.8;
        background: rgba(79, 70, 229, 0.1);
        width: 60px;
        height: 60px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--primary-color);
    }
    
    .stat-value {
        font-size: 1.75rem;
        font-weight: 700;
        color: var(--dark-color);
        line-height: 1.2;
    }
    
    .stat-label {
        color: #6b7280;
        font-size: 0.875rem;
        margin-top: 0.25rem;
    }
    
    .chart-container {
        position: relative;
        height: 300px;
        min-height: 300px;
    }
    
    .card {
        border: none;
        border-radius: 12px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
        margin-bottom: 1.5rem;
        background: white;
    }
    
    .card-header {
        background-color: white;
        border-bottom: 1px solid rgba(0,0,0,0.05);
        padding: 1.25rem 1.5rem;
        border-top-left-radius: 12px !important;
        border-top-right-radius: 12px !important;
    }
    
    .card-title {
        font-weight: 600;
        color: var(--dark-color);
        margin: 0;
        display: flex;
        align-items: center;
    }
    
    .card-title i {
        margin-right: 0.5rem;
        color: var(--primary-color);
    }
    
    .progress {
        height: 8px;
        border-radius: 4px;
        background-color: #f1f5f9;
        overflow: visible;
    }
    
    .progress-bar {
        border-radius: 4px;
        position: relative;
        overflow: visible;
    }
    
    .progress-bar::after {
        content: attr(aria-valuenow) "%";
        position: absolute;
        right: -25px;
        top: 50%;
        transform: translateY(-50%);
        font-size: 0.7rem;
        font-weight: 600;
        color: #4a5568;
    }
    
    .legend-item {
        display: inline-flex;
        align-items: center;
        margin-right: 1.25rem;
        margin-bottom: 0.5rem;
    }
    
    .legend-color {
        width: 12px;
        height: 12px;
        border-radius: 3px;
        display: inline-block;
        margin-right: 0.5rem;
    }
    
    .table th {
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.7rem;
        letter-spacing: 0.05em;
        color: #6b7280;
        border-top: none;
        padding: 0.75rem 1.5rem;
    }
    
    .table td {
        padding: 1rem 1.5rem;
        vertical-align: middle;
        border-color: #f3f4f6;
    }
    
    .badge {
        font-weight: 500;
        padding: 0.35em 0.65em;
        font-size: 0.75em;
        border-radius: 6px;
    }
    
    .avatar {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background: var(--primary-light);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        font-size: 0.875rem;
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-4 py-5">
    <!-- Header -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-5">
        <div class="mb-3 mb-md-0">
            <div class="d-flex align-items-center">
                <a href="{{ route('mentor.courses.show', $course->id) }}" class="btn btn-icon btn-light rounded-circle me-3">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <div>
                    <h1 class="h3 mb-1">Estadísticas del Curso</h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb p-0 m-0">
                            <li class="breadcrumb-item"><a href="{{ route('mentor.dashboard') }}" class="text-muted">Inicio</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('mentor.courses.index') }}" class="text-muted">Mis Cursos</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('mentor.courses.show', $course->id) }}" class="text-muted">{{ Str::limit($course->name, 20) }}</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Estadísticas</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
        <div class="d-flex flex-wrap gap-2">
            <div class="dropdown">
                <button class="btn btn-outline-gray-300 d-flex align-items-center" type="button" id="filterDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="far fa-calendar-alt me-2"></i>
                    <span>Últimos 30 días</span>
                    <i class="fas fa-chevron-down ms-2"></i>
                </button>
                <ul class="dropdown-menu dropdown-menu-end shadow-sm" aria-labelledby="filterDropdown" style="min-width: 200px;">
                    <li><h6 class="dropdown-header">Rango de fechas</h6></li>
                    <li><a class="dropdown-item active" href="#">Últimos 7 días</a></li>
                    <li><a class="dropdown-item" href="#">Últimos 30 días</a></li>
                    <li><a class="dropdown-item" href="#">Últimos 90 días</a></li>
                    <li><a class="dropdown-item" href="#">Este mes</a></li>
                    <li><a class="dropdown-item" href="#">Mes pasado</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item" href="#">Personalizado</a></li>
                </ul>
            </div>
            <button class="btn btn-primary d-flex align-items-center">
                <i class="fas fa-download me-2"></i>
                <span>Exportar</span>
            </button>
        </div>
    </div>

    <!-- Tarjetas de Resumen -->
    <div class="row g-4 mb-5">
        <div class="col-xl-3 col-md-6">
            <div class="stat-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="text-uppercase text-muted small fw-bold mb-2">Estudiantes Totales</h6>
                            <h2 class="stat-value mb-0">{{ $course->students_count ?? 0 }}</h2>
                            <div class="mt-2">
                                <span class="badge bg-success bg-opacity-10 text-success">
                                    <i class="fas fa-arrow-up me-1"></i>12% desde el mes pasado
                                </span>
                            </div>
                        </div>
                        <div class="stat-icon">
                            <i class="fas fa-user-graduate"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6">
            <div class="stat-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="text-uppercase text-muted small fw-bold mb-2">Tasa de Finalización</h6>
                            <h2 class="stat-value mb-0">{{ $completionRate }}<small class="text-muted fs-6">%</small></h2>
                            <div class="mt-2">
                                <span class="badge bg-success bg-opacity-10 text-success">
                                    <i class="fas fa-arrow-up me-1"></i>5% desde el mes pasado
                                </span>
                            </div>
                            <h3 class="mb-0 fw-bold">{{ $course->duration }} <small class="text-muted">horas</small></h3>
                        </div>
                        <div class="bg-warning bg-opacity-10 p-3 rounded-3">
                            <i class="fas fa-clock text-warning stat-icon"></i>
                        </div>
                    </div>
                    <div class="mt-3">
                        <span class="text-muted small">
                            {{ $course->modules->count() }} módulos
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Estudiantes Recientes y Actividad -->
    <div class="row g-4">
        <!-- Estudiantes Recientes -->
        <div class="col-lg-6">
            <div class="card h-100">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-user-graduate"></i>Estudiantes Recientes
                        </h5>
                        <a href="{{ route('mentor.courses.students', $course->id) }}" class="btn btn-sm btn-outline-primary d-flex align-items-center">
                            <span>Ver todos</span>
                            <i class="fas fa-arrow-right ms-2"></i>
                        </a>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="card-body p-0">
                    @if($recentStudents->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead>
                                    <tr>
                                        <th class="ps-4">Estudiante</th>
                                        <th>Progreso</th>
                                        <th class="pe-4 text-end">Última actividad</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentStudents as $student)
                                        <tr class="border-top">
                                            <td class="ps-4">
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar me-3" 
                                                         style="background-color: {{ '#' . substr(md5($student->email), 0, 6) }}; color: white;">
                                                        {{ strtoupper(substr($student->name, 0, 1)) }}
                                                    </div>
                                                    <div class="text-truncate">
                                                        <h6 class="mb-0 text-truncate" style="max-width: 150px;" 
                                                            data-bs-toggle="tooltip" title="{{ $student->name }}">
                                                            {{ $student->name }}
                                                        </h6>
                                                        <small class="text-muted text-truncate d-block" style="max-width: 150px;" 
                                                               data-bs-toggle="tooltip" title="{{ $student->email }}">
                                                            {{ $student->email }}
                                                        </small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="progress progress-thin flex-grow-1 me-2">
                                                        <div class="progress-bar bg-primary" role="progressbar" 
                                                             style="width: {{ $student->progress }}%" 
                                                             aria-valuenow="{{ $student->progress }}" 
                                                             aria-valuemin="0" 
                                                             aria-valuemax="100">
                                                        </div>
                                                    </div>
                                                    <span class="small fw-medium" style="min-width: 40px;">
                                                        {{ $student->progress }}%
                                                    </span>
                                                    <small class="text-muted mt-1">{{ $student->pivot->progress }}%</small>
                                                </div>
                                            </td>
                                            <td class="text-end pe-3">
                                                <div class="d-flex flex-column">
                                                    @if($student->pivot->last_activity)
                                                        <span class="small fw-semibold">{{ $student->pivot->last_activity->diffForHumans() }}</span>
                                                        <span class="small text-muted">{{ $student->pivot->last_activity->format('d M, Y') }}</span>
                                                    @else
                                                        <span class="text-muted small">Nunca</span>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="card-footer bg-white border-top-0 pt-0">
                            <a href="{{ route('mentor.courses.students', $course->id) }}" class="btn btn-link btn-sm text-decoration-none">
                                Ver todos los estudiantes <i class="fas fa-arrow-right ms-1"></i>
                            </a>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <div class="mb-3">
                                <i class="fas fa-user-graduate fa-3x text-muted opacity-25"></i>
                            </div>
                            <h5 class="text-muted">No hay estudiantes recientes</h5>
                            <p class="text-muted small">Aún no hay estudiantes inscritos en los últimos días.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Actividad Reciente -->
        <div class="col-lg-6">
            <div class="card h-100">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-history"></i>Actividad Reciente
                        </h5>
                        <div class="dropdown">
                            <button class="btn btn-sm btn-outline-gray-300 d-flex align-items-center" type="button" id="activityFilter" data-bs-toggle="dropdown" aria-expanded="false">
                                <span>Todas</span>
                                <i class="fas fa-chevron-down ms-2"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end shadow-sm" aria-labelledby="activityFilter" style="min-width: 160px;">
                                <li><h6 class="dropdown-header">Filtrar por</h6></li>
                                <li><a class="dropdown-item active" href="#">Todas las actividades</a></li>
                                <li><a class="dropdown-item" href="#">Lecciones completadas</a></li>
                                <li><a class="dropdown-item" href="#">Exámenes realizados</a></li>
                                <li><a class="dropdown-item" href="#">Comentarios</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="card-body p-0">
                    @if($recentActivities->count() > 0)
                        <div class="list-group list-group-flush">
                            @foreach($recentActivities as $activity)
                                <div class="list-group-item border-0 py-3 px-4">
                                    <div class="d-flex align-items-start">
                                        <div class="flex-shrink-0 me-3">
                                            @if($activity->type === 'lesson_completed' || $activity->description === 'Lección completada')
                                                <div class="bg-primary bg-opacity-10 text-primary rounded-circle p-2" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                                                    <i class="fas fa-check-circle"></i>
                                                </div>
                                            @elseif($activity->type === 'quiz_completed' || str_contains(strtolower($activity->description), 'examen'))
                                                <div class="bg-success bg-opacity-10 text-success rounded-circle p-2" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                                                    <i class="fas fa-tasks"></i>
                                                </div>
                                            @else
                                                <div class="bg-info bg-opacity-10 text-info rounded-circle p-2" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                                                    <i class="fas fa-comment"></i>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="flex-grow-1">
                                            <div class="d-flex justify-content-between align-items-start">
                                                <h6 class="mb-1 fw-medium">
                                                    @if($activity->type === 'lesson_completed' || $activity->description === 'Lección completada')
                                                        Lección completada
                                                    @elseif($activity->type === 'quiz_completed' || str_contains(strtolower($activity->description), 'examen'))
                                                        Examen completado
                                                    @else
                                                        Nueva actividad
                                                    @endif
                                                </h6>
                                                <small class="text-muted" data-bs-toggle="tooltip" title="{{ $activity->created_at->format('d M Y, H:i') }}">
                                                    {{ $activity->created_at->diffForHumans() }}
                                                </small>
                                            </div>
                                            <p class="mb-1 small">
                                                <span class="fw-medium">{{ $activity->causer?->name ?? 'Usuario' }}</span>
                                                {{ $activity->description }}
                                            </p>
                                            @if($activity->properties->has('url'))
                                                <a href="{{ $activity->properties->get('url') }}" class="btn btn-sm btn-outline-primary mt-1 d-inline-flex align-items-center">
                                                    <span>Ver detalles</span>
                                                    <i class="fas fa-external-link-alt ms-1"></i>
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                @if(!$loop->last)
                                    <div class="border-bottom mx-4"></div>
                                @endif
                            @endforeach
                        </div>
                        <div class="card-footer bg-white border-top-0 pt-0">
                            <a href="#" class="btn btn-link btn-sm text-decoration-none d-inline-flex align-items-center">
                                <span>Ver todo el historial</span>
                                <i class="fas fa-arrow-right ms-1"></i>
                            </a>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <div class="mb-3">
                                <i class="fas fa-history fa-3x text-muted opacity-25"></i>
                            </div>
                            <h5 class="text-muted">No hay actividad reciente</h5>
                            <p class="text-muted small">Aún no hay actividad registrada en este curso.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Gráficos Principales -->
    <div class="row g-4 mb-4">
        <!-- Gráfico de Módulos Más Vistos -->
        <div class="col-lg-6">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-white border-bottom-0 pt-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="fas fa-layer-group text-primary me-2"></i>Módulos Más Vistos
                        </h5>
                        <div class="dropdown">
                            <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" id="moduleFilter" data-bs-toggle="dropdown" aria-expanded="false">
                                Últimos 30 días
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="moduleFilter">
                                <li><a class="dropdown-item" href="#">Últimos 7 días</a></li>
                                <li><a class="dropdown-item active" href="#">Últimos 30 días</a></li>
                                <li><a class="dropdown-item" href="#">Últimos 90 días</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    @if($popularModules->count() > 0)
                        <div class="chart-container">
                            <canvas id="modulesChart"></canvas>
                        </div>
                        <div class="mt-4">
                            <div class="row g-2">
                                @foreach($popularModules as $module)
                                <div class="col-12 col-sm-6">
                                    <div class="d-flex align-items-center p-2 bg-light rounded">
                                        <span class="legend-color" style="background-color: #{{ substr(md5($module->id), 0, 6) }}"></span>
                                        <div class="ms-2">
                                            <div class="small text-truncate" style="max-width: 150px;" data-bs-toggle="tooltip" title="{{ $module->title }}">
                                                {{ $module->title }}
                                            </div>
                                            <div class="small text-muted">
                                                {{ $module->views_count }} visualizaciones
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <div class="mb-3">
                                <i class="fas fa-chart-pie fa-3x text-muted opacity-25"></i>
                            </div>
                            <h5 class="text-muted">No hay datos disponibles</h5>
                            <p class="text-muted small">Aún no hay suficiente información para mostrar este gráfico.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Gráfico de Tutoriales Populares -->
        <div class="col-lg-6">
            <div class="card h-100">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-play-circle"></i>Tutoriales Populares
                        </h5>
                        <div class="dropdown">
                            <button class="btn btn-sm btn-outline-gray-300 d-flex align-items-center" type="button" id="tutorialFilter" data-bs-toggle="dropdown" aria-expanded="false">
                                <span>Últimos 30 días</span>
                                <i class="fas fa-chevron-down ms-2"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end shadow-sm" aria-labelledby="tutorialFilter" style="min-width: 160px;">
                                <li><h6 class="dropdown-header">Rango de tiempo</h6></li>
                                <li><a class="dropdown-item" href="#">Últimos 7 días</a></li>
                                <li><a class="dropdown-item active" href="#">Últimos 30 días</a></li>
                                <li><a class="dropdown-item" href="#">Últimos 90 días</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    @if($popularTutorials->count() > 0)
                        <div class="chart-container">
                            <canvas id="tutorialsChart"></canvas>
                        </div>
                        <div class="mt-3">
                            <div class="table-responsive">
                                <table class="table table-sm table-hover">
                                    <tbody>
                                        @foreach($popularTutorials as $tutorial)
                                        <tr>
                                            <td class="align-middle" style="width: 20px">
                                                <span class="legend-color d-inline-block" style="background-color: #{{ substr(md5($tutorial->id), 0, 6) }}"></span>
                                            </td>
                                            <td class="text-truncate" style="max-width: 200px;" data-bs-toggle="tooltip" title="{{ $tutorial->title }}">
                                                {{ $tutorial->title }}
                                            </td>
                                            <td class="text-end text-nowrap">
                                                <span class="badge bg-light text-dark">
                                                    {{ $tutorial->views_count }} <i class="fas fa-eye ms-1"></i>
                                                </span>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <div class="mb-3">
                                <i class="fas fa-chart-pie fa-3x text-muted opacity-25"></i>
                            </div>
                            <h5 class="text-muted">No hay datos disponibles</h5>
                            <p class="text-muted small">Aún no hay suficientes datos para mostrar las estadísticas de tutoriales.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Gráfico de Progreso de Estudiantes -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center">
                        <div class="mb-2 mb-md-0">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-chart-line"></i>Progreso de Estudiantes
                            </h5>
                            <p class="text-muted small mb-0 mt-1">Evolución del progreso promedio de los estudiantes</p>
                        </div>
                        <div class="d-flex align-items-center">
                            <div class="dropdown me-3">
                                <button class="btn btn-sm btn-outline-gray-300 d-flex align-items-center" type="button" id="progressFilter" data-bs-toggle="dropdown" aria-expanded="false">
                                    <span>Últimos 30 días</span>
                                    <i class="fas fa-chevron-down ms-2"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end shadow-sm" aria-labelledby="progressFilter" style="min-width: 160px;">
                                    <li><h6 class="dropdown-header">Rango de tiempo</h6></li>
                                    <li><a class="dropdown-item" href="#">Últimos 7 días</a></li>
                                    <li><a class="dropdown-item active" href="#">Últimos 30 días</a></li>
                                    <li><a class="dropdown-item" href="#">Últimos 90 días</a></li>
                                </ul>
                            </div>
                            <div class="btn-group" role="group" aria-label="Tipo de visualización">
                                <input type="radio" class="btn-check" name="chartType" id="chartLine" autocomplete="off" checked>
                                <label class="btn btn-sm btn-outline-gray-300" for="chartLine" title="Ver como línea">
                                    <i class="fas fa-chart-line"></i>
                                </label>
                                <input type="radio" class="btn-check" name="chartType" id="chartBar" autocomplete="off">
                                <label class="btn btn-sm btn-outline-gray-300" for="chartBar" title="Ver como barras">
                                    <i class="fas fa-chart-bar"></i>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
                        <div class="d-flex align-items-center mb-2 mb-md-0">
                            <span class="legend-color me-2" style="background-color: #4f46e5;"></span>
                            <span class="small text-muted">Progreso promedio</span>
                            <span class="badge bg-primary bg-opacity-10 text-primary ms-3">
                                <i class="fas fa-arrow-up me-1"></i> 12% desde el mes pasado
                            </span>
                        </div>
                        <div class="d-flex align-items-center">
                            <div class="d-flex align-items-center me-3">
                                <span class="legend-color me-2" style="background-color: #10b981;"></span>
                                <span class="small text-muted">Completados</span>
                            </div>
                            <div class="d-flex align-items-center">
                                <span class="legend-color me-2" style="background-color: #f59e0b;"></span>
                                <span class="small text-muted">En progreso</span>
                            </div>
                        </div>
                    </div>
                    <div class="chart-container" style="height: 320px;">
                        <canvas id="progressChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    /* Estilos mejorados para tarjetas */
    .card {
        border: none;
        border-radius: 12px;
        box-shadow: 0 0.125rem 0.5rem rgba(0, 0, 0, 0.05);
        transition: all 0.3s ease;
        overflow: hidden;
        margin-bottom: 1.5rem;
    }
    
    .card:hover {
        transform: translateY(-3px);
        box-shadow: 0 0.5rem 1.5rem rgba(0, 0, 0, 0.08);
    }
    
    .card-header {
        background-color: #fff;
        border-bottom: 1px solid rgba(0,0,0,0.05);
        padding: 1.25rem 1.5rem;
    }
    
    .card-body {
        padding: 1.5rem;
    }
    
    /* Estilos para las tarjetas de estadísticas */
    .stat-card {
        border-left: 4px solid;
        transition: all 0.3s ease;
        border-radius: 10px;
        overflow: hidden;
        background: #fff;
    }
    
    .stat-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 0.5rem 1.5rem rgba(0, 0, 0, 0.08) !important;
    }
    
    .stat-card .card-body {
        padding: 1.5rem;
    }
    
    .stat-card .stat-icon {
        font-size: 1.75rem;
        opacity: 0.9;
        margin-bottom: 0.75rem;
    }
    
    .stat-card .stat-value {
        font-size: 1.75rem;
        font-weight: 700;
        line-height: 1.2;
        margin: 0.5rem 0;
    }
    
    .stat-card .stat-label {
        color: #6c757d;
        font-size: 0.875rem;
        margin-bottom: 0;
    }
    
    /* Mejoras en las tablas */
    .table {
        margin-bottom: 0;
        font-size: 0.875rem;
    }
    
    .table th {
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.7rem;
        letter-spacing: 0.5px;
        color: #6c757d;
        background-color: #f8f9fa;
        padding: 0.75rem 1.25rem;
        border-bottom: 1px solid #e9ecef;
    }
    
    .table td {
        vertical-align: middle;
        padding: 1rem 1.25rem;
        border-color: #f1f5f9;
    }
    
    .table-hover > tbody > tr:hover {
        background-color: rgba(79, 70, 229, 0.03);
    }
    
    .table > :not(:last-child) > :last-child > * {
        border-bottom-color: #f1f5f9;
    }
    
    /* Mejoras en los gráficos */
    .chart-container {
        position: relative;
        min-height: 300px;
        height: 100%;
    }
    
    /* Mejoras en los botones */
    .btn-outline-secondary {
        border-color: #e9ecef;
    }
    
    .btn-outline-secondary:hover {
        background-color: #f8f9fa;
    }
    
    /* Ajustes de espaciado */
    .mb-section {
        margin-bottom: 2rem;
    }
    /* Estilos para las tarjetas de estadísticas */
    .stat-card {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
        border-radius: 0.5rem;
        border: none;
        overflow: hidden;
    }
    
    .stat-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 0.5rem 1.5rem rgba(0, 0, 0, 0.08) !important;
    }
    
    .stat-icon {
        font-size: 1.5rem;
    }
    
    /* Estilos para los gráficos */
    .chart-container {
        position: relative;
        height: 250px;
        width: 100%;
    }
    
    /* Estilos para la línea de tiempo de actividad */
    .activity-item {
        transition: background-color 0.2s ease;
    }
    
    .activity-item:hover {
        background-color: #f8f9fa;
    }
    
    .activity-icon .avatar-sm {
        width: 36px;
        height: 36px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
    }
    
    .activity-icon .avatar-title {
        font-size: 1rem;
    }
    
    /* Estilos para la tabla de estudiantes */
    .table th {
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.7rem;
        letter-spacing: 0.5px;
        color: #6c757d;
        background-color: #f8f9fa;
        border-bottom-width: 1px;
    }
    
    .table td {
        vertical-align: middle;
        padding: 1rem 0.75rem;
    }
    
    /* Estilos para los indicadores de estado */
    .legend-color {
        display: inline-block;
        width: 12px;
        height: 12px;
        border-radius: 2px;
        margin-right: 8px;
        vertical-align: middle;
    }
    
    /* Estilos para los badges */
    .badge {
        font-weight: 500;
        letter-spacing: 0.3px;
        padding: 0.35em 0.65em;
    }
    
    /* Estilos para los avatares */
    .avatar {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 600;
        font-size: 0.875rem;
        text-transform: uppercase;
    }
    
    /* Estilos para los botones de acción */
    .btn-sm {
        padding: 0.25rem 0.75rem;
        font-size: 0.75rem;
        line-height: 1.5;
        border-radius: 0.25rem;
    }
    
    .btn-outline-gray-300 {
        border-color: #e2e8f0;
        color: #4a5568;
        background-color: transparent;
    }
    
    .btn-outline-gray-300:hover {
        background-color: #f8fafc;
        border-color: #cbd5e0;
        color: #2d3748;
    }
    
    .btn-check:checked + .btn-outline-gray-300,
    .btn-check:active + .btn-outline-gray-300,
    .btn-outline-gray-300:active,
    .btn-outline-gray-300.active {
        background-color: #4f46e5;
        border-color: #4f46e5;
        color: white;
    }
    
    /* Ajustes responsivos */
    @media (max-width: 992px) {
        .card {
            margin-bottom: 1.25rem;
        }
        
        .stat-card .stat-value {
            font-size: 1.5rem;
        }
        
        .stat-card .stat-icon {
            font-size: 1.5rem;
        }
    }
    
    @media (max-width: 768px) {
        .chart-container {
            height: 250px;
        }
        
        .table-responsive {
            font-size: 0.8125rem;
            border-radius: 0.5rem;
            border: 1px solid #f1f5f9;
        }
        
        .table th, 
        .table td {
            padding: 0.75rem;
        }
        
        .btn-group > .btn {
            padding: 0.25rem 0.5rem;
            font-size: 0.75rem;
        }
    }
    
    @media (max-width: 576px) {
        .card-header {
            flex-direction: column;
            align-items: flex-start;
        }
        
        .card-header .d-flex {
            width: 100%;
            margin-top: 0.75rem;
        }
        
        .dropdown-menu {
            position: absolute !important;
            width: 100%;
        }
    }
    
    /* Estilos para los botones de filtro */
    .btn-outline-secondary {
        color: #6c757d;
        border-color: #dee2e6;
    }
    
    .btn-outline-secondary:hover {
        background-color: #f8f9fa;
        border-color: #dee2e6;
        color: #6c757d;
    }
    
    .btn-outline-secondary.dropdown-toggle:focus {
        box-shadow: 0 0 0 0.2rem rgba(108, 117, 125, 0.1);
    }
    
    /* Mejoras en los tooltips */
    .tooltip {
        font-size: 0.8rem;
    }
    
    /* Estilos para las barras de progreso */
    .progress {
        height: 6px;
        border-radius: 3px;
        background-color: #f1f5f9;
        overflow: visible;
    }
    
    .progress-bar {
        position: relative;
        border-radius: 3px;
        transition: width 0.6s ease;
    }
    
    .progress-bar::after {
        content: attr(aria-valuenow) "%";
        position: absolute;
        right: -30px;
        top: 50%;
        transform: translateY(-50%);
        font-size: 0.7rem;
        font-weight: 600;
        color: #4a5568;
    }
    
    /* Estilos para los dropdowns */
    .dropdown-menu {
        border: none;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        border-radius: 8px;
        padding: 0.5rem 0;
        margin-top: 0.5rem;
    }
    
    .dropdown-item {
        padding: 0.5rem 1.25rem;
        font-size: 0.8125rem;
        color: #4a5568;
        transition: all 0.2s;
    }
    
    .dropdown-item:hover, 
    .dropdown-item:focus {
        background-color: #f8fafc;
        color: #2d3748;
    }
    
    .dropdown-item.active, 
    .dropdown-item:active {
        background-color: #4f46e5;
        color: white;
    }
    
    .dropdown-header {
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: #718096;
        padding: 0.5rem 1.25rem 0.25rem;
    }
</style>
@endpush

@push('scripts')
<script>
    // Inicializar tooltips
    document.addEventListener('DOMContentLoaded', function() {
        // Inicializar tooltips de Bootstrap
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
        
        // Configuración de gráficos (ejemplo con Chart.js)
        if (typeof Chart !== 'undefined') {
            // Gráfico de módulos más vistos
            var modulesCtx = document.getElementById('modulesChart');
            if (modulesCtx) {
                new Chart(modulesCtx, {
                    type: 'bar',
                    data: {
                        labels: {!! json_encode($popularModules->pluck('title')->map(function($title) { return Str::limit($title, 20); })) !!},
                        datasets: [{
                            label: 'Visualizaciones',
                            data: {!! json_encode($popularModules->pluck('views_count')) !!},
                            backgroundColor: {!! json_encode($popularModules->map(function($module) { 
                                return '#' . substr(md5($module->id), 0, 6); 
                            })) !!},
                            borderWidth: 0,
                            borderRadius: 4,
                            barThickness: 'flex',
                            maxBarThickness: 30,
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false
                            },
                            tooltip: {
                                backgroundColor: '#fff',
                                titleColor: '#495057',
                                bodyColor: '#6c757d',
                                borderColor: 'rgba(0,0,0,0.1)',
                                borderWidth: 1,
                                padding: 12,
                                boxShadow: '0 0.5rem 1rem rgba(0, 0, 0, 0.1)',
                                callbacks: {
                                    label: function(context) {
                                        return context.parsed.y + ' visualizaciones';
                                    }
                                }
                            }
                        },
                        scales: {
                            x: {
                                grid: {
                                    display: false
                                },
                                ticks: {
                                    maxRotation: 45,
                                    minRotation: 45
                                }
                            },
                            y: {
                                beginAtZero: true,
                                grid: {
                                    borderDash: [3, 3],
                                    drawBorder: false
                                },
                                ticks: {
                                    precision: 0
                                }
                            }
                        }
                    }
                });
            }
            
            // Gráfico de tutoriales más vistos
            var tutorialsCtx = document.getElementById('tutorialsChart');
            if (tutorialsCtx) {
                new Chart(tutorialsCtx, {
                    type: 'doughnut',
                    data: {
                        labels: {!! json_encode($popularTutorials->pluck('title')->map(function($title) { return Str::limit($title, 15); })) !!},
                        datasets: [{
                            data: {!! json_encode($popularTutorials->pluck('views_count')) !!},
                            backgroundColor: {!! json_encode($popularTutorials->map(function($tutorial) { 
                                return '#' + md5($tutorial->id).substring(0, 6); 
                            })) !!},
                            borderWidth: 0,
                            borderRadius: 4,
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        cutout: '70%',
                        plugins: {
                            legend: {
                                display: false
                            },
                            tooltip: {
                                backgroundColor: '#fff',
                                titleColor: '#495057',
                                bodyColor: '#6c757d',
                                borderColor: 'rgba(0,0,0,0.1)',
                                borderWidth: 1,
                                padding: 12,
                                boxShadow: '0 0.5rem 1rem rgba(0, 0, 0, 0.1)',
                                callbacks: {
                                    label: function(context) {
                                        var label = context.label || '';
                                        var value = context.raw || 0;
                                        var total = context.dataset.data.reduce((a, b) => a + b, 0);
                                        var percentage = Math.round((value / total) * 100);
                                        return `${label}: ${value} visualizaciones (${percentage}%)`;
                                    }
                                }
                            }
                        }
                    }
                });
            }
            
            // Gráfico de progreso de estudiantes
            var progressCtx = document.getElementById('progressChart');
            if (progressCtx) {
                // Datos de ejemplo para el gráfico de progreso
                var labels = [];
                var today = new Date();
                for (var i = 6; i >= 0; i--) {
                    var date = new Date();
                    date.setDate(today.getDate() - i);
                    labels.push(date.toLocaleDateString('es-ES', { month: 'short', day: 'numeric' }));
                }
                
                new Chart(progressCtx, {
                    type: 'line',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Estudiantes activos',
                            data: [5, 8, 12, 15, 18, 20, 25],
                            borderColor: '#4e73df',
                            backgroundColor: 'rgba(78, 115, 223, 0.05)',
                            fill: true,
                            tension: 0.4,
                            pointBackgroundColor: '#fff',
                            pointBorderColor: '#4e73df',
                            pointBorderWidth: 2,
                            pointRadius: 4,
                            pointHoverRadius: 6,
                            pointHoverBackgroundColor: '#fff',
                            pointHoverBorderColor: '#4e73df',
                            pointHoverBorderWidth: 2,
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false
                            },
                            tooltip: {
                                backgroundColor: '#fff',
                                titleColor: '#495057',
                                bodyColor: '#6c757d',
                                borderColor: 'rgba(0,0,0,0.1)',
                                borderWidth: 1,
                                padding: 12,
                                boxShadow: '0 0.5rem 1rem rgba(0, 0, 0, 0.1)',
                                callbacks: {
                                    label: function(context) {
                                        return context.parsed.y + ' estudiantes activos';
                                    }
                                }
                            }
                        },
                        scales: {
                            x: {
                                grid: {
                                    display: false
                                }
                            },
                            y: {
                                beginAtZero: true,
                                grid: {
                                    borderDash: [3, 3],
                                    drawBorder: false
                                },
                                ticks: {
                                    precision: 0
                                }
                            }
                        }
                    }
                });
            }
        }
        
        // Manejar cambios en los filtros
        document.querySelectorAll('.dropdown-item').forEach(item => {
            item.addEventListener('click', function(e) {
                e.preventDefault();
                const parent = this.closest('.dropdown');
                const button = parent.querySelector('.dropdown-toggle');
                button.textContent = this.textContent;
                
                // Aquí podrías agregar lógica para actualizar los datos según el filtro seleccionado
                console.log('Filtro cambiado:', this.textContent);
            });
        });
    });
</script>
@endpush
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    $(document).ready(function() {
        // Configuración para los gráficos
        const chartOptions = {
            responsive: true,
            maintainAspectRatio: false
        };
        
        // Datos para el gráfico de módulos
        @if($popularModules->count() > 0)
        const modulesData = {
            labels: [
                @foreach($popularModules as $module)
                    "{{ Str::limit($module->title, 20) }}",
                @endforeach
            ],
            datasets: [{
                label: 'Vistas',
                backgroundColor: [
                    @foreach($popularModules as $module)
                        "{{ '#'.substr(md5($module->id), 0, 6) }}",
                    @endforeach
                ],
                data: [
                    @foreach($popularModules as $module)
                        {{ $module->views_count }},
                    @endforeach
                ]
            }]
        };
        
        const modulesCtx = document.getElementById('modulesChart').getContext('2d');
        new Chart(modulesCtx, {
            type: 'bar',
            data: modulesData,
            options: chartOptions
        });
        @endif
        
        // Datos para el gráfico de tutoriales
        @if($popularTutorials->count() > 0)
        const tutorialsData = {
            labels: [
                @foreach($popularTutorials as $tutorial)
                    "{{ Str::limit($tutorial->title, 20) }}",
                @endforeach
            ],
            datasets: [{
                label: 'Vistas',
                backgroundColor: [
                    @foreach($popularTutorials as $tutorial)
                        "{{ '#'.substr(md5($tutorial->id), 0, 6) }}",
                    @endforeach
                ],
                data: [
                    @foreach($popularTutorials as $tutorial)
                        {{ $tutorial->views_count }},
                    @endforeach
                ]
            }]
        };
        
        const tutorialsCtx = document.getElementById('tutorialsChart').getContext('2d');
        new Chart(tutorialsCtx, {
            type: 'doughnut',
            data: tutorialsData,
            options: chartOptions
        });
        @endif
        
        // Datos simulados para el gráfico de progreso (en una aplicación real, estos datos vendrían de la base de datos)
        const progressData = {
            labels: ['0-20%', '21-40%', '41-60%', '61-80%', '81-99%', '100%'],
            datasets: [{
                label: 'Número de estudiantes',
                backgroundColor: 'rgba(78, 115, 223, 0.5)',
                borderColor: 'rgba(78, 115, 223, 1)',
                pointBackgroundColor: 'rgba(78, 115, 223, 1)',
                pointBorderColor: '#fff',
                pointHoverBackgroundColor: '#fff',
                pointHoverBorderColor: 'rgba(78, 115, 223, 1)',
                data: [3, 5, 8, 12, 7, 10]
            }]
        };
        
        const progressCtx = document.getElementById('progressChart').getContext('2d');
        new Chart(progressCtx, {
            type: 'line',
            data: progressData,
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        precision: 0
                    }
                }
            }
        });
    });
</script>
@endsection
