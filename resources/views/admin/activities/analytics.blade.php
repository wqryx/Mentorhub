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
                        <a href="{{ route('admin.activities.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-1"></i> Volver a Registros
                        </a>
                    </div>
                </div>
                
                <div class="card-body">
                    <!-- Estadísticas generales -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="card border-left-primary shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                                Total Registros</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['total'] }}</div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-3">
                            <div class="card border-left-success shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                                Usuarios Activos</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['active_users'] }}</div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-users fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-3">
                            <div class="card border-left-info shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                                Hoy</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['today'] }}</div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-calendar-day fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-3">
                            <div class="card border-left-warning shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                                Esta Semana</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['this_week'] }}</div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-calendar-week fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Gráficos -->
                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <div class="card shadow">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">Actividad por Día</h6>
                                </div>
                                <div class="card-body">
                                    <div class="chart-area">
                                        <canvas id="activityByDayChart"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6 mb-4">
                            <div class="card shadow">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">Actividad por Tipo</h6>
                                </div>
                                <div class="card-body">
                                    <div class="chart-pie">
                                        <canvas id="activityByTypeChart"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <div class="card shadow">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">Usuarios Más Activos</h6>
                                </div>
                                <div class="card-body">
                                    <div class="chart-bar">
                                        <canvas id="topUsersChart"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6 mb-4">
                            <div class="card shadow">
                                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                                    <h6 class="m-0 font-weight-bold text-primary">Mantenimiento</h6>
                                </div>
                                <div class="card-body">
                                    <p class="mb-3">Actualmente hay <strong>{{ $stats['total'] }}</strong> registros de actividad en la base de datos.</p>
                                    
                                    <form action="{{ route('admin.activities.prune') }}" method="POST" class="mb-4">
                                        @csrf
                                        <div class="mb-3">
                                            <label for="days" class="form-label">Eliminar registros más antiguos que:</label>
                                            <div class="input-group">
                                                <input type="number" class="form-control" id="days" name="days" value="30" min="1" max="365">
                                                <span class="input-group-text">días</span>
                                                <button type="submit" class="btn btn-danger" onclick="return confirm('¿Estás seguro de que deseas eliminar los registros antiguos? Esta acción no se puede deshacer.')">
                                                    <i class="fas fa-trash-alt me-1"></i> Eliminar
                                                </button>
                                            </div>
                                            <div class="form-text">Esta acción eliminará permanentemente los registros antiguos.</div>
                                        </div>
                                    </form>
                                    
                                    <hr>
                                    
                                    <h6 class="font-weight-bold">Configuración de Registro</h6>
                                    <form action="{{ route('admin.activities.settings') }}" method="POST">
                                        @csrf
                                        <div class="mb-3 form-check">
                                            <input type="checkbox" class="form-check-input" id="log_login" name="log_login" {{ $settings['log_login'] ?? false ? 'checked' : '' }}>
                                            <label class="form-check-label" for="log_login">Registrar inicios de sesión</label>
                                        </div>
                                        <div class="mb-3 form-check">
                                            <input type="checkbox" class="form-check-input" id="log_model_events" name="log_model_events" {{ $settings['log_model_events'] ?? false ? 'checked' : '' }}>
                                            <label class="form-check-label" for="log_model_events">Registrar eventos de modelos (crear, actualizar, eliminar)</label>
                                        </div>
                                        <div class="mb-3">
                                            <label for="retention_days" class="form-label">Días de retención de registros:</label>
                                            <input type="number" class="form-control" id="retention_days" name="retention_days" value="{{ $settings['retention_days'] ?? 90 }}" min="1" max="365">
                                        </div>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-save me-1"></i> Guardar Configuración
                                        </button>
                                    </form>
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
    // Datos para los gráficos
    const activityByDay = {
        labels: {!! json_encode($dates) !!},
        datasets: [{
            label: 'Actividades',
            data: {!! json_encode($activityByDay) !!},
            backgroundColor: 'rgba(78, 115, 223, 0.05)',
            borderColor: 'rgba(78, 115, 223, 1)',
            pointBackgroundColor: 'rgba(78, 115, 223, 1)',
            pointBorderColor: '#fff',
            pointHoverBackgroundColor: '#fff',
            pointHoverBorderColor: 'rgba(78, 115, 223, 1)',
            borderWidth: 2,
            fill: true
        }]
    };
    
    const activityByType = {
        labels: {!! json_encode($activityByAction->pluck('action')->toArray()) !!},
        datasets: [{
            data: {!! json_encode($activityByAction->pluck('count')->toArray()) !!},
            backgroundColor: [
                '#4e73df', '#1cc88a', '#36b9cc', '#f6c23e', '#e74a3b',
                '#5a5c69', '#858796', '#6f42c1', '#20c9a6', '#fd7e14'
            ],
            hoverBackgroundColor: [
                '#2e59d9', '#17a673', '#2c9faf', '#dda20a', '#be2617',
                '#484a54', '#717384', '#5d37a2', '#169b80', '#dc6502'
            ],
            hoverBorderColor: "rgba(234, 236, 244, 1)",
        }]
    };
    
    const topUsers = {
        labels: {!! json_encode($topUsers->pluck('user.name')->toArray()) !!},
        datasets: [{
            label: 'Actividades',
            data: {!! json_encode($topUsers->pluck('count')->toArray()) !!},
            backgroundColor: '#36b9cc',
            borderColor: '#2c9faf',
            borderWidth: 1
        }]
    };
    
    // Configuración de los gráficos
    window.addEventListener('DOMContentLoaded', (event) => {
        // Gráfico de actividad por día
        new Chart(document.getElementById('activityByDayChart'), {
            type: 'line',
            data: activityByDay,
            options: {
                maintainAspectRatio: false,
                scales: {
                    x: {
                        grid: {
                            display: false
                        }
                    },
                    y: {
                        beginAtZero: true,
                        precision: 0
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });
        
        // Gráfico de actividad por tipo
        new Chart(document.getElementById('activityByTypeChart'), {
            type: 'doughnut',
            data: activityByType,
            options: {
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                },
                cutout: '70%'
            }
        });
        
        // Gráfico de usuarios más activos
        new Chart(document.getElementById('topUsersChart'), {
            type: 'bar',
            data: topUsers,
            options: {
                maintainAspectRatio: false,
                scales: {
                    x: {
                        grid: {
                            display: false
                        }
                    },
                    y: {
                        beginAtZero: true,
                        precision: 0
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });
    });
</script>
@endsection
