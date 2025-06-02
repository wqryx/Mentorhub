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
<div class="container mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-semibold text-gray-900">Análisis de Actividad</h1>
        <a href="{{ route('admin.activity_logs.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:outline-none focus:border-gray-700 focus:ring focus:ring-gray-200 active:bg-gray-800 disabled:opacity-25 transition">
            <i class="fas fa-arrow-left mr-2"></i> Volver a Registros
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="bg-white overflow-hidden shadow-sm rounded-lg p-4 stats-card border-l-4 border-blue-500">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100 mr-4">
                    <i class="fas fa-user-clock text-blue-500 text-xl"></i>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500">Actividades Hoy</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $todayCount }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white overflow-hidden shadow-sm rounded-lg p-4 stats-card border-l-4 border-green-500">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100 mr-4">
                    <i class="fas fa-users text-green-500 text-xl"></i>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500">Usuarios Activos</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $activeUsers }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white overflow-hidden shadow-sm rounded-lg p-4 stats-card border-l-4 border-yellow-500">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-yellow-100 mr-4">
                    <i class="fas fa-chart-line text-yellow-500 text-xl"></i>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500">Actividades Esta Semana</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $weekCount }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white overflow-hidden shadow-sm rounded-lg p-4 stats-card border-l-4 border-purple-500">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-purple-100 mr-4">
                    <i class="fas fa-exclamation-triangle text-purple-500 text-xl"></i>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500">Eventos de Error</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $errorCount }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <div class="bg-white overflow-hidden shadow-sm rounded-lg p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Actividad por Día</h2>
            <div class="chart-container">
                <canvas id="activityByDayChart"></canvas>
            </div>
        </div>
        
        <div class="bg-white overflow-hidden shadow-sm rounded-lg p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Actividad por Tipo</h2>
            <div class="chart-container">
                <canvas id="activityByTypeChart"></canvas>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white overflow-hidden shadow-sm rounded-lg p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Usuarios Más Activos</h2>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Usuario</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actividades</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Última Actividad</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($topUsers as $user)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        <img class="h-10 w-10 rounded-full" src="{{ $user->profile_photo_url }}" alt="{{ $user->name }}">
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                                        <div class="text-sm text-gray-500">{{ $user->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $user->activity_count }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $user->last_activity_at->diffForHumans() }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        
        <div class="bg-white overflow-hidden shadow-sm rounded-lg p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Actividades Recientes</h2>
            <div class="overflow-y-auto" style="max-height: 400px;">
                <ul class="divide-y divide-gray-200">
                    @foreach($recentActivities as $activity)
                    <li class="py-3">
                        <div class="flex space-x-3">
                            <img class="h-6 w-6 rounded-full" src="{{ $activity->causer->profile_photo_url ?? asset('images/default-avatar.png') }}" alt="">
                            <div class="flex-1 space-y-1">
                                <div class="flex items-center justify-between">
                                    <h3 class="text-sm font-medium">{{ $activity->causer->name ?? 'Sistema' }}</h3>
                                    <p class="text-sm text-gray-500">{{ $activity->created_at->diffForHumans() }}</p>
                                </div>
                                <p class="text-sm text-gray-500">
                                    {{ $activity->description }} 
                                    <span class="font-medium">{{ $activity->subject_type ? class_basename($activity->subject_type) : '' }}</span>
                                    @if($activity->subject)
                                    <span class="text-gray-400">(ID: {{ $activity->subject_id }})</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Datos para el gráfico de actividad por día
        const activityByDayCtx = document.getElementById('activityByDayChart').getContext('2d');
        const activityByDayChart = new Chart(activityByDayCtx, {
            type: 'line',
            data: {
                labels: {!! json_encode($activityByDay->pluck('date')) !!},
                datasets: [{
                    label: 'Actividades',
                    data: {!! json_encode($activityByDay->pluck('count')) !!},
                    backgroundColor: 'rgba(59, 130, 246, 0.2)',
                    borderColor: 'rgba(59, 130, 246, 1)',
                    borderWidth: 2,
                    tension: 0.3,
                    pointBackgroundColor: 'rgba(59, 130, 246, 1)',
                    pointRadius: 4,
                    pointHoverRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0
                        }
                    }
                }
            }
        });
        
        // Datos para el gráfico de actividad por tipo
        const activityByTypeCtx = document.getElementById('activityByTypeChart').getContext('2d');
        const activityByTypeChart = new Chart(activityByTypeCtx, {
            type: 'doughnut',
            data: {
                labels: {!! json_encode($activityByType->pluck('type')) !!},
                datasets: [{
                    data: {!! json_encode($activityByType->pluck('count')) !!},
                    backgroundColor: [
                        'rgba(59, 130, 246, 0.7)',
                        'rgba(16, 185, 129, 0.7)',
                        'rgba(245, 158, 11, 0.7)',
                        'rgba(239, 68, 68, 0.7)',
                        'rgba(139, 92, 246, 0.7)',
                        'rgba(236, 72, 153, 0.7)',
                        'rgba(75, 85, 99, 0.7)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'right',
                    }
                }
            }
        });
    });
</script>
@endsection
