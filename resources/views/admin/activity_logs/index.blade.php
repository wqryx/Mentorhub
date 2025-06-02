@extends('layouts.admin')

@section('title', 'Registros de Actividad')

@section('content')
<div class="container mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-semibold text-gray-900">Registros de Actividad</h1>
        <div class="flex space-x-2">
            <a href="{{ route('admin.activity_logs.analytics') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:outline-none focus:border-blue-700 focus:ring focus:ring-blue-200 active:bg-blue-800 disabled:opacity-25 transition">
                <i class="fas fa-chart-line mr-2"></i> Panel de Análisis
            </a>
            <button type="button" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:outline-none focus:border-green-700 focus:ring focus:ring-green-200 active:bg-green-800 disabled:opacity-25 transition" onclick="document.getElementById('exportModal').classList.remove('hidden')">
                <i class="fas fa-file-export mr-2"></i> Exportar
            </button>
        </div>
    </div>

    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <div class="p-4 border-b border-gray-200">
            <form action="{{ route('admin.activity_logs.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label for="filter_user" class="block text-sm font-medium text-gray-700 mb-1">Usuario</label>
                    <select name="user_id" id="filter_user" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                        <option value="">Todos los usuarios</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div>
                    <label for="filter_action" class="block text-sm font-medium text-gray-700 mb-1">Acción</label>
                    <select name="action" id="filter_action" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                        <option value="">Todas las acciones</option>
                        @foreach($actions as $action)
                            <option value="{{ $action }}" {{ request('action') == $action ? 'selected' : '' }}>{{ $action }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div>
                    <label for="filter_date" class="block text-sm font-medium text-gray-700 mb-1">Fecha</label>
                    <input type="date" name="date" id="filter_date" value="{{ request('date') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                </div>
                
                <div class="flex items-end">
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:outline-none focus:border-blue-700 focus:ring focus:ring-blue-200 active:bg-blue-800 disabled:opacity-25 transition">
                        <i class="fas fa-search mr-2"></i> Filtrar
                    </button>
                    <a href="{{ route('admin.activity_logs.index') }}" class="ml-2 inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:outline-none focus:border-gray-700 focus:ring focus:ring-gray-200 active:bg-gray-800 disabled:opacity-25 transition">
                        <i class="fas fa-times mr-2"></i> Limpiar
                    </a>
                </div>
            </form>
        </div>
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Usuario</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acción</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Modelo</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($logs as $log)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $log->id }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                @if($log->causer)
                                <div class="flex-shrink-0 h-10 w-10">
                                    <img class="h-10 w-10 rounded-full" src="{{ $log->causer->profile_photo_url ?? asset('images/default-avatar.png') }}" alt="{{ $log->causer->name ?? 'Usuario' }}">
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $log->causer->name ?? 'N/A' }}</div>
                                    <div class="text-sm text-gray-500">{{ $log->causer->email ?? 'N/A' }}</div>
                                </div>
                                @else
                                <div class="flex-shrink-0 h-10 w-10">
                                    <div class="h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center">
                                        <i class="fas fa-cogs text-gray-500"></i>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">Sistema</div>
                                    <div class="text-sm text-gray-500">Acción automática</div>
                                </div>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                {{ $log->description == 'created' ? 'bg-green-100 text-green-800' : '' }}
                                {{ $log->description == 'updated' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                {{ $log->description == 'deleted' ? 'bg-red-100 text-red-800' : '' }}
                                {{ !in_array($log->description, ['created', 'updated', 'deleted']) ? 'bg-blue-100 text-blue-800' : '' }}
                            ">
                                {{ $log->description }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            @if($log->subject_type)
                                {{ class_basename($log->subject_type) }}
                                @if($log->subject_id)
                                <span class="text-xs text-gray-400">#{{ $log->subject_id }}</span>
                                @endif
                            @else
                                N/A
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <div>{{ $log->created_at->format('d/m/Y H:i:s') }}</div>
                            <div class="text-xs text-gray-400">{{ $log->created_at->diffForHumans() }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <a href="{{ route('admin.activity_logs.show', $log->id) }}" class="text-blue-600 hover:text-blue-900 mr-3">
                                <i class="fas fa-eye"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                            No se encontraron registros de actividad.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="px-4 py-3 bg-gray-50 border-t border-gray-200">
            {{ $logs->withQueryString()->links() }}
        </div>
    </div>
</div>

<!-- Modal de Exportación -->
<div id="exportModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center z-50 hidden">
    <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-900">Exportar Registros de Actividad</h3>
                <button type="button" class="text-gray-400 hover:text-gray-500" onclick="document.getElementById('exportModal').classList.add('hidden')">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
        <form action="{{ route('admin.activity_logs.export') }}" method="POST">
            @csrf
            <div class="px-6 py-4">
                <div class="mb-4">
                    <label for="export_format" class="block text-sm font-medium text-gray-700 mb-1">Formato</label>
                    <select name="format" id="export_format" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" required>
                        <option value="csv">CSV</option>
                        <option value="xlsx">Excel (XLSX)</option>
                        <option value="pdf">PDF</option>
                    </select>
                </div>
                
                <div class="mb-4">
                    <label for="export_date_range" class="block text-sm font-medium text-gray-700 mb-1">Rango de Fechas</label>
                    <select name="date_range" id="export_date_range" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                        <option value="all">Todos los registros</option>
                        <option value="today">Hoy</option>
                        <option value="yesterday">Ayer</option>
                        <option value="this_week">Esta semana</option>
                        <option value="last_week">Semana pasada</option>
                        <option value="this_month">Este mes</option>
                        <option value="last_month">Mes pasado</option>
                        <option value="custom">Personalizado</option>
                    </select>
                </div>
                
                <div id="custom_date_range" class="mb-4 hidden">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="export_start_date" class="block text-sm font-medium text-gray-700 mb-1">Fecha Inicio</label>
                            <input type="date" name="start_date" id="export_start_date" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                        </div>
                        <div>
                            <label for="export_end_date" class="block text-sm font-medium text-gray-700 mb-1">Fecha Fin</label>
                            <input type="date" name="end_date" id="export_end_date" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                        </div>
                    </div>
                </div>
            </div>
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-end">
                <button type="button" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:outline-none focus:border-gray-700 focus:ring focus:ring-gray-200 active:bg-gray-800 disabled:opacity-25 transition mr-2" onclick="document.getElementById('exportModal').classList.add('hidden')">
                    Cancelar
                </button>
                <button type="submit" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:outline-none focus:border-green-700 focus:ring focus:ring-green-200 active:bg-green-800 disabled:opacity-25 transition">
                    <i class="fas fa-file-export mr-2"></i> Exportar
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const dateRangeSelect = document.getElementById('export_date_range');
        const customDateRange = document.getElementById('custom_date_range');
        
        dateRangeSelect.addEventListener('change', function() {
            if (this.value === 'custom') {
                customDateRange.classList.remove('hidden');
            } else {
                customDateRange.classList.add('hidden');
            }
        });
    });
</script>
@endsection
