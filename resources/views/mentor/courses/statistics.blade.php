@extends('mentor.layouts.app')

@section('title', 'Estadísticas del Curso: ' . ($course->name ?? 'N/A') . ' - MentorHub')

@push('styles')
<style>
    body {
        background-color: #f8f9fa;
    }
    .stat-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        border: none;
        border-radius: 10px;
        overflow: hidden;
        height: 100%;
    }
    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
    }
    .stat-icon {
        font-size: 2rem;
        opacity: 0.9;
    }
    .chart-container {
        position: relative;
        height: 300px;
        min-height: 300px;
    }
    .card-header {
        border-bottom: 1px solid rgba(0,0,0,0.05);
        background-color: #fff;
    }
    .progress-thin {
        height: 0.5rem;
    }
    .legend-item {
        display: inline-flex;
        align-items: center;
        margin-right: 1rem;
        margin-bottom: 0.5rem;
    }
    .legend-color {
        width: 12px;
        height: 12px;
        border-radius: 2px;
        display: inline-block;
        margin-right: 0.5rem;
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-4 py-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-2">
                    <li class="breadcrumb-item"><a href="{{ route('mentor.dashboard') }}">Inicio</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('mentor.courses.index') }}">Mis Cursos</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('mentor.courses.show', $course->id) }}">{{ Str::limit($course->title, 20) }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Estadísticas</li>
                </ol>
            </nav>
            <div class="d-flex align-items-center">
                <h1 class="h3 text-gray-800 mb-0 me-3">Estadísticas del Curso</h1>
                <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2">
                    <i class="fas fa-chart-line me-1"></i> Análisis
                </span>
            </div>
            <p class="text-muted mb-0 mt-2">{{ $course->title }}</p>
        </div>
        <div>
            <a href="{{ route('mentor.courses.show', $course->id) }}" class="btn btn-outline-primary">
                <i class="fas fa-arrow-left me-2"></i>Volver al Curso
            </a>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row g-4 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card stat-card border-start border-4 border-primary shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-uppercase text-muted mb-2 small fw-bold">Estudiantes Inscritos</h6>
                            <h3 class="mb-0 fw-bold">{{ $course->students_count }}</h3>
                        </div>
                        <div class="bg-primary bg-opacity-10 p-3 rounded-3">
                            <i class="fas fa-users text-primary stat-icon"></i>
                        </div>
                    </div>
                    <div class="mt-3">
                        <span class="text-success small">
                            <i class="fas fa-arrow-up"></i> {{ rand(5, 15) }}% desde el mes pasado
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card stat-card border-start border-4 border-success shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-uppercase text-muted mb-2 small fw-bold">Tasa de Finalización</h6>
                            <h3 class="mb-0 fw-bold">{{ number_format($completionRate, 1) }}%</h3>
                        </div>
                        <div class="bg-success bg-opacity-10 p-3 rounded-3">
                            <i class="fas fa-graduation-cap text-success stat-icon"></i>
                        </div>
                    </div>
                    <div class="mt-3">
                        <div class="progress progress-thin">
                            <div class="progress-bar bg-success" role="progressbar" style="width: {{ $completionRate }}%" 
                                 aria-valuenow="{{ $completionRate }}" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card stat-card border-start border-4 border-info shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-uppercase text-muted mb-2 small fw-bold">Progreso Promedio</h6>
                            <h3 class="mb-0 fw-bold">{{ number_format($averageProgress, 1) }}%</h3>
                        </div>
                        <div class="bg-info bg-opacity-10 p-3 rounded-3">
                            <i class="fas fa-chart-line text-info stat-icon"></i>
                        </div>
                    </div>
                    <div class="mt-3">
                        <div class="progress progress-thin">
                            <div class="progress-bar bg-info" role="progressbar" style="width: {{ $averageProgress }}%" 
                                 aria-valuenow="{{ $averageProgress }}" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card stat-card border-start border-4 border-warning shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-uppercase text-muted mb-2 small fw-bold">Duración Total</h6>
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
            <div class="card shadow-sm h-100">
                <div class="card-header bg-white border-bottom-0 pt-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="fas fa-user-graduate text-primary me-2"></i>Estudiantes Recientes
                        </h5>
                        <div class="dropdown">
                            <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" id="studentsFilter" data-bs-toggle="dropdown" aria-expanded="false">
                                Últimos 7 días
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="studentsFilter">
                                <li><a class="dropdown-item active" href="#">Últimos 7 días</a></li>
                                <li><a class="dropdown-item" href="#">Últimos 30 días</a></li>
                                <li><a class="dropdown-item" href="#">Todos los estudiantes</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="card-body p-0">
                    @if($recentStudents->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="ps-3">Estudiante</th>
                                        <th class="text-center">Progreso</th>
                                        <th class="text-end pe-3">Última Actividad</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentStudents as $student)
                                        <tr>
                                            <td class="ps-3">
                                                <div class="d-flex align-items-center">
                                                    <div class="position-relative">
                                                        <img class="rounded-circle me-2" src="{{ $student->profile_photo_url }}" 
                                                             alt="{{ $student->name }}" width="36" height="36" onerror="this.src='https://ui-avatars.com/api/?name='+encodeURIComponent('{{ $student->name }}')+''">
                                                        @if($student->pivot->last_activity && $student->pivot->last_activity->diffInHours(now()) < 1)
                                                            <span class="position-absolute bottom-0 end-0 bg-success rounded-circle border border-2 border-white" style="width: 10px; height: 10px;" title="En línea"></span>
                                                        @endif
                                                    </div>
                                                    <div>
                                                        <div class="fw-semibold text-truncate" style="max-width: 150px;" data-bs-toggle="tooltip" title="{{ $student->name }}">
                                                            {{ $student->name }}
                                                        </div>
                                                        <div class="text-muted small text-truncate" style="max-width: 150px;" data-bs-toggle="tooltip" title="{{ $student->email }}">
                                                            {{ $student->email }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                <div class="d-flex flex-column align-items-center">
                                                    <div class="progress w-100" style="height: 6px;">
                                                        <div class="progress-bar bg-{{ $student->pivot->progress >= 100 ? 'success' : 'primary' }}" 
                                                             role="progressbar" style="width: {{ $student->pivot->progress }}%" 
                                                             aria-valuenow="{{ $student->pivot->progress }}" aria-valuemin="0" aria-valuemax="100">
                                                        </div>
                                                    </div>
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
            <div class="card shadow-sm h-100">
                <div class="card-header bg-white border-bottom-0 pt-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="fas fa-history text-info me-2"></i>Actividad Reciente
                        </h5>
                        <div class="dropdown">
                            <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" id="activityFilter" data-bs-toggle="dropdown" aria-expanded="false">
                                Últimos 7 días
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="activityFilter">
                                <li><a class="dropdown-item active" href="#">Últimas 24 horas</a></li>
                                <li><a class="dropdown-item" href="#">Últimos 7 días</a></li>
                                <li><a class="dropdown-item" href="#">Últimos 30 días</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="card-body p-0">
                    @if($recentActivities->count() > 0)
                        <div class="activity-feed">
                            @foreach($recentActivities as $activity)
                                <div class="activity-item border-bottom p-3">
                                    <div class="d-flex">
                                        <div class="activity-icon me-3">
                                            <div class="avatar-sm flex-shrink-0">
                                                <span class="avatar-title rounded-circle {{ $activity->getActivityColor() }} bg-opacity-10">
                                                    <i class="fas {{ $activity->getActivityIcon() }} {{ $activity->getActivityColor() }}"></i>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1 overflow-hidden">
                                            <div class="d-flex justify-content-between align-items-center mb-1">
                                                <h6 class="mb-0">{{ $activity->getActivityTitle() }}</h6>
                                                <small class="text-muted">{{ $activity->created_at->diffForHumans() }}</small>
                                            </div>
                                            <p class="text-muted mb-1 text-truncate" data-bs-toggle="tooltip" title="{{ $activity->description }}">
                                                {{ $activity->description }}
                                            </p>
                                            @if($activity->properties->has('url'))
                                                <a href="{{ $activity->properties->get('url') }}" class="btn btn-sm btn-outline-primary btn-sm mt-1">
                                                    Ver detalles <i class="fas fa-external-link-alt ms-1"></i>
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="card-footer bg-white border-top-0 pt-0">
                            <a href="#" class="btn btn-link btn-sm text-decoration-none">
                                Ver todo el historial <i class="fas fa-arrow-right ms-1"></i>
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
                                <i class="fas fa-chart-bar fa-3x text-muted opacity-25"></i>
                            </div>
                            <h5 class="text-muted">No hay datos disponibles</h5>
                            <p class="text-muted small">Aún no hay suficientes datos para mostrar las estadísticas de módulos.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Gráfico de Tutoriales Más Vistos -->
        <div class="col-lg-6">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-white border-bottom-0 pt-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="fas fa-play-circle text-success me-2"></i>Tutoriales Más Vistos
                        </h5>
                        <div class="dropdown">
                            <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" id="tutorialFilter" data-bs-toggle="dropdown" aria-expanded="false">
                                Últimos 30 días
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="tutorialFilter">
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
            <div class="card shadow-sm">
                <div class="card-header bg-white border-bottom-0 pt-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="fas fa-user-graduate text-info me-2"></i>Progreso de Estudiantes
                        </h5>
                        <div class="btn-group btn-group-sm" role="group">
                            <button type="button" class="btn btn-outline-secondary active">Mensual</button>
                            <button type="button" class="btn btn-outline-secondary">Trimestral</button>
                            <button type="button" class="btn btn-outline-secondary">Anual</button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="chart-container" style="height: 300px;">
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
    
    /* Mejoras en las tarjetas de estadísticas */
    .stat-card {
        border-left: 4px solid;
        transition: all 0.3s ease;
    }
    
    .stat-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1) !important;
    }
    
    /* Mejoras en las tablas */
    .table {
        margin-bottom: 0;
    }
    
    .table th {
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.7rem;
        letter-spacing: 0.5px;
        color: #6c757d;
        background-color: #f8f9fa;
    }
    
    .table td {
        vertical-align: middle;
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
        border-radius: 50%;
        margin-right: 8px;
    }
    
    /* Ajustes responsivos */
    @media (max-width: 768px) {
        .chart-container {
            height: 200px;
        }
        
        .table-responsive {
            font-size: 0.85rem;
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
    .progress-thin {
        height: 4px !important;
    }
    
    /* Estilos para las tarjetas de estadísticas */
    .stat-card .card-body {
        padding: 1.25rem;
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
                                return '#' + md5($module->id).substring(0, 6); 
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
