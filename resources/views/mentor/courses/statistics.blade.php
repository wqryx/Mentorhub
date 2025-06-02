@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/mentor-courses.css') }}">
@endpush

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Estadísticas del Curso: {{ $course->title }}</h5>
                    <div class="card-tools">
                        <a href="{{ route('mentor.courses.show', $course->id) }}" class="btn btn-sm btn-primary">
                            <i class="fas fa-arrow-left"></i> Volver al Curso
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-primary shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                                Estudiantes Inscritos</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $course->students_count }}</div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-users fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-success shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                                Tasa de Finalización</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($completionRate, 1) }}%</div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-graduation-cap fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-info shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Progreso Promedio
                                            </div>
                                            <div class="row no-gutters align-items-center">
                                                <div class="col-auto">
                                                    <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800">{{ number_format($averageProgress, 1) }}%</div>
                                                </div>
                                                <div class="col">
                                                    <div class="progress progress-sm mr-2">
                                                        <div class="progress-bar bg-info" role="progressbar"
                                                            style="width: {{ $averageProgress }}%" aria-valuenow="{{ $averageProgress }}" aria-valuemin="0"
                                                            aria-valuemax="100"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-warning shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                                Duración Total</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $course->duration }} horas</div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-clock fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <div class="card shadow">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">Módulos Más Vistos</h6>
                                </div>
                                <div class="card-body">
                                    @if($popularModules->count() > 0)
                                        <div class="chart-bar">
                                            <canvas id="modulesChart"></canvas>
                                        </div>
                                        <div class="mt-4 text-center small">
                                            @foreach($popularModules as $module)
                                                <span class="mr-2">
                                                    <i class="fas fa-circle" style="color: {{ '#'.substr(md5($module->id), 0, 6) }}"></i> {{ Str::limit($module->title, 30) }}
                                                </span>
                                            @endforeach
                                        </div>
                                    @else
                                        <div class="text-center py-4">
                                            <p class="text-muted">No hay datos suficientes para mostrar estadísticas de módulos.</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 mb-4">
                            <div class="card shadow">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">Tutoriales Más Vistos</h6>
                                </div>
                                <div class="card-body">
                                    @if($popularTutorials->count() > 0)
                                        <div class="chart-pie">
                                            <canvas id="tutorialsChart"></canvas>
                                        </div>
                                        <div class="mt-4 text-center small">
                                            @foreach($popularTutorials as $tutorial)
                                                <span class="mr-2">
                                                    <i class="fas fa-circle" style="color: {{ '#'.substr(md5($tutorial->id), 0, 6) }}"></i> {{ Str::limit($tutorial->title, 30) }}
                                                </span>
                                            @endforeach
                                        </div>
                                    @else
                                        <div class="text-center py-4">
                                            <p class="text-muted">No hay datos suficientes para mostrar estadísticas de tutoriales.</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12 mb-4">
                            <div class="card shadow">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">Progreso de Estudiantes</h6>
                                </div>
                                <div class="card-body">
                                    <div class="chart-area">
                                        <canvas id="progressChart"></canvas>
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
