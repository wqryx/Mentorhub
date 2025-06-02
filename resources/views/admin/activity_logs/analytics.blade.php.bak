@extends('layouts.admin')

@section('title', 'Panel de Análisis de Actividad')

@section('styles')
<style>
    .stats-card {
        transition: all 0.3s;
    }
    .stats-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1);
    }
    .chart-container {
        position: relative;
        height: 300px;
        margin-bottom: 20px;
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h2 class="mb-0">Panel de Análisis de Actividad</h2>
                        <a href="{{ route('admin.activity_logs.index') }}" class="btn btn-outline-primary">
                            <i class="fas fa-list me-1"></i> Ver Registros
                        </a>
                    </div>
                </div>
                
                <div class="card-body">
                    <!-- Estadísticas generales -->
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <div class="card bg-primary text-white stats-card">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="card-title">Total de Registros</h6>
                                            <h2 class="mb-0">{{ number_format($stats['total_logs']) }}</h2>
                                        </div>
                                        <div>
                                            <i class="fas fa-history fa-3x opacity-50"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card bg-success text-white stats-card">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="card-title">Total de Usuarios</h6>
                                            <h2 class="mb-0">{{ number_format($stats['total_users']) }}</h2>
                                        </div>
                                        <div>
                                            <i class="fas fa-users fa-3x opacity-50"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card bg-info text-white stats-card">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="card-title">Usuarios Activos</h6>
                                            <h2 class="mb-0">{{ number_format($stats['active_users']) }}</h2>
                                        </div>
                                        <div>
                                            <i class="fas fa-user-check fa-3x opacity-50"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <!-- Gráfico de actividad por día -->
                        <div class="col-md-8">
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h5 class="mb-0">Actividad por Día (Últimos 30 días)</h5>
                                </div>
                                <div class="card-body">
                                    <div class="chart-container">
                                        <canvas id="activityByDayChart"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Gráfico de actividad por tipo -->
                        <div class="col-md-4">
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h5 class="mb-0">Actividad por Tipo</h5>
                                </div>
                                <div class="card-body">
                                    <div class="chart-container">
                                        <canvas id="activityByTypeChart"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <!-- Usuarios más activos -->
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mb-0">Usuarios Más Activos</h5>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-hover">
                                            <thead>
                                                <tr>
                                                    <th>Usuario</th>
                                                    <th>Acciones</th>
                                                    <th>% del Total</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($topUsers as $user)
                                                    <tr>
                                                        <td>
                                                            @if($user->user)
                                                                <a href="{{ route('admin.users.show', $user->user_id) }}">
                                                                    {{ $user->user->name }}
                                                                </a>
                                                            @else
                                                                <span class="text-muted">Usuario Eliminado</span>
                                                            @endif
                                                        </td>
                                                        <td>{{ number_format($user->count) }}</td>
                                                        <td>
                                                            @php
                                                                $percentage = ($stats['total_logs'] > 0) ? ($user->count / $stats['total_logs'] * 100) : 0;
                                                            @endphp
                                                            <div class="progress" style="height: 6px;">
                                                                <div class="progress-bar bg-primary" role="progressbar" 
                                                                    style="width: {{ $percentage }}%;" 
                                                                    aria-valuenow="{{ $percentage }}" 
                                                                    aria-valuemin="0" 
                                                                    aria-valuemax="100">
                                                                </div>
                                                            </div>
                                                            <span class="small">{{ number_format($percentage, 1) }}%</span>
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="3" class="text-center py-3">
                                                            <p class="text-muted mb-0">No hay datos disponibles</p>
                                                        </td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Actividad por tipo de acción -->
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mb-0">Actividad por Tipo de Acción</h5>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-hover">
                                            <thead>
                                                <tr>
                                                    <th>Acción</th>
                                                    <th>Cantidad</th>
                                                    <th>% del Total</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($activityByAction as $activity)
                                                    <tr>
                                                        <td>
                                                            <span class="badge {{ getBadgeClass($activity->action) }}">
                                                                {{ ucfirst($activity->action) }}
                                                            </span>
                                                        </td>
                                                        <td>{{ number_format($activity->count) }}</td>
                                                        <td>
                                                            @php
                                                                $percentage = ($stats['total_logs'] > 0) ? ($activity->count / $stats['total_logs'] * 100) : 0;
                                                            @endphp
                                                            <div class="progress" style="height: 6px;">
                                                                <div class="progress-bar {{ getProgressBarClass($activity->action) }}" 
                                                                    role="progressbar" 
                                                                    style="width: {{ $percentage }}%;" 
                                                                    aria-valuenow="{{ $percentage }}" 
                                                                    aria-valuemin="0" 
                                                                    aria-valuemax="100">
                                                                </div>
                                                            </div>
                                                            <span class="small">{{ number_format($percentage, 1) }}%</span>
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="3" class="text-center py-3">
                                                            <p class="text-muted mb-0">No hay datos disponibles</p>
                                                        </td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
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

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Datos para el gráfico de actividad por día
        const activityByDayData = {
            labels: @json(array_keys($dates)),
            datasets: [{
                label: 'Registros de actividad',
                data: @json(array_values($dates)),
                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1,
                tension: 0.4,
                fill: true
            }]
        };
        
        const activityByDayConfig = {
            type: 'line',
            data: activityByDayData,
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0
                        }
                    },
                    x: {
                        ticks: {
                            maxTicksLimit: 10
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            title: function(context) {
                                const date = new Date(context[0].label);
                                return date.toLocaleDateString('es-ES', { 
                                    weekday: 'long', 
                                    year: 'numeric', 
                                    month: 'long', 
                                    day: 'numeric' 
                                });
                            }
                        }
                    }
                }
            }
        };
        
        new Chart(
            document.getElementById('activityByDayChart'),
            activityByDayConfig
        );
        
        // Datos para el gráfico de actividad por tipo
        const activityByTypeData = {
            labels: @json($activityByAction->pluck('action')->map(function($item) { return ucfirst($item); })),
            datasets: [{
                data: @json($activityByAction->pluck('count')),
                backgroundColor: [
                    'rgba(54, 162, 235, 0.7)',
                    'rgba(75, 192, 192, 0.7)',
                    'rgba(255, 99, 132, 0.7)',
                    'rgba(255, 206, 86, 0.7)',
                    'rgba(153, 102, 255, 0.7)',
                    'rgba(255, 159, 64, 0.7)',
                    'rgba(199, 199, 199, 0.7)',
                    'rgba(83, 102, 255, 0.7)',
                    'rgba(40, 159, 64, 0.7)',
                    'rgba(210, 199, 199, 0.7)'
                ],
                borderColor: [
                    'rgba(54, 162, 235, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(255, 99, 132, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(153, 102, 255, 1)',
                    'rgba(255, 159, 64, 1)',
                    'rgba(199, 199, 199, 1)',
                    'rgba(83, 102, 255, 1)',
                    'rgba(40, 159, 64, 1)',
                    'rgba(210, 199, 199, 1)'
                ],
                borderWidth: 1
            }]
        };
        
        const activityByTypeConfig = {
            type: 'doughnut',
            data: activityByTypeData,
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const label = context.label || '';
                                const value = context.raw;
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const percentage = Math.round((value / total) * 100);
                                return `${label}: ${value} (${percentage}%)`;
                            }
                        }
                    }
                }
            }
        };
        
        new Chart(
            document.getElementById('activityByTypeChart'),
            activityByTypeConfig
        );
    });
</script>
@endpush

@php
function getBadgeClass($action) {
    switch ($action) {
        case 'created':
            return 'bg-success';
        case 'updated':
            return 'bg-info';
        case 'deleted':
            return 'bg-danger';
        case 'login':
            return 'bg-primary';
        case 'logout':
            return 'bg-secondary';
        default:
            return 'bg-dark';
    }
}

function getProgressBarClass($action) {
    switch ($action) {
        case 'created':
            return 'bg-success';
        case 'updated':
            return 'bg-info';
        case 'deleted':
            return 'bg-danger';
        case 'login':
            return 'bg-primary';
        case 'logout':
            return 'bg-secondary';
        default:
            return 'bg-dark';
    }
}
@endphp
