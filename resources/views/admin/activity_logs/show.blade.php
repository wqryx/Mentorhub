@extends('layouts.admin')

@section('title', 'Detalle de Registro de Actividad')

@section('content')
<div class="container mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-semibold text-gray-900">Detalle de Registro de Actividad #{{ $log->id }}</h1>
        <a href="{{ route('admin.activity_logs.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:outline-none focus:border-gray-700 focus:ring focus:ring-gray-200 active:bg-gray-800 disabled:opacity-25 transition">
            <i class="fas fa-arrow-left mr-2"></i> Volver a la Lista
        </a>
    </div>

    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Información General</h2>
                    <div class="bg-gray-50 rounded-lg p-4 space-y-3">
                        <div class="flex justify-between">
                            <span class="text-sm font-medium text-gray-500">ID:</span>
                            <span class="text-sm text-gray-900">{{ $log->id }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm font-medium text-gray-500">Acción:</span>
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                {{ $log->description == 'created' ? 'bg-green-100 text-green-800' : '' }}
                                {{ $log->description == 'updated' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                {{ $log->description == 'deleted' ? 'bg-red-100 text-red-800' : '' }}
                                {{ !in_array($log->description, ['created', 'updated', 'deleted']) ? 'bg-blue-100 text-blue-800' : '' }}
                            ">
                                {{ $log->description }}
                            </span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm font-medium text-gray-500">Fecha:</span>
                            <span class="text-sm text-gray-900">{{ $log->created_at->format('d/m/Y H:i:s') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm font-medium text-gray-500">Tiempo transcurrido:</span>
                            <span class="text-sm text-gray-900">{{ $log->created_at->diffForHumans() }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm font-medium text-gray-500">IP:</span>
                            <span class="text-sm text-gray-900">{{ $log->properties['ip'] ?? 'N/A' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm font-medium text-gray-500">Navegador:</span>
                            <span class="text-sm text-gray-900">{{ $log->properties['user_agent'] ?? 'N/A' }}</span>
                        </div>
                    </div>
                </div>
                
                <div>
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Usuario</h2>
                    @if($log->causer)
                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="flex items-center mb-4">
                            <div class="flex-shrink-0 h-12 w-12">
                                <img class="h-12 w-12 rounded-full" src="{{ $log->causer->profile_photo_url ?? asset('images/default-avatar.png') }}" alt="{{ $log->causer->name }}">
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-medium text-gray-900">{{ $log->causer->name }}</h3>
                                <p class="text-sm text-gray-500">{{ $log->causer->email }}</p>
                            </div>
                        </div>
                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <span class="text-sm font-medium text-gray-500">ID:</span>
                                <span class="text-sm text-gray-900">{{ $log->causer->id }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-sm font-medium text-gray-500">Rol:</span>
                                <span class="text-sm text-gray-900">{{ $log->causer->roles->pluck('name')->implode(', ') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-sm font-medium text-gray-500">Fecha de registro:</span>
                                <span class="text-sm text-gray-900">{{ $log->causer->created_at->format('d/m/Y') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-sm font-medium text-gray-500">Estado:</span>
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $log->causer->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $log->causer->is_active ? 'Activo' : 'Inactivo' }}
                                </span>
                            </div>
                        </div>
                        <div class="mt-4">
                            <a href="{{ route('admin.users.show', $log->causer->id) }}" class="text-blue-600 hover:text-blue-900 text-sm font-medium">
                                <i class="fas fa-external-link-alt mr-1"></i> Ver perfil completo
                            </a>
                        </div>
                    </div>
                    @else
                    <div class="bg-gray-50 rounded-lg p-4 flex items-center justify-center h-full">
                        <div class="text-center">
                            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-gray-200 mb-4">
                                <i class="fas fa-cogs text-gray-500 text-xl"></i>
                            </div>
                            <h3 class="text-lg font-medium text-gray-900">Sistema</h3>
                            <p class="text-sm text-gray-500">Acción automática del sistema</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
            
            <div class="mt-8">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Modelo Afectado</h2>
                @if($log->subject_type)
                <div class="bg-gray-50 rounded-lg p-4 space-y-3">
                    <div class="flex justify-between">
                        <span class="text-sm font-medium text-gray-500">Tipo:</span>
                        <span class="text-sm text-gray-900">{{ class_basename($log->subject_type) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm font-medium text-gray-500">ID:</span>
                        <span class="text-sm text-gray-900">{{ $log->subject_id }}</span>
                    </div>
                    @if($log->subject)
                    <div class="flex justify-between">
                        <span class="text-sm font-medium text-gray-500">Nombre:</span>
                        <span class="text-sm text-gray-900">{{ $log->subject->name ?? $log->subject->title ?? 'N/A' }}</span>
                    </div>
                    @endif
                </div>
                @else
                <div class="bg-gray-50 rounded-lg p-4 flex items-center justify-center">
                    <p class="text-gray-500">No hay información del modelo afectado</p>
                </div>
                @endif
            </div>
            
            <div class="mt-8">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Cambios Realizados</h2>
                @if(isset($log->properties['attributes']) || isset($log->properties['old']))
                <div class="bg-gray-50 rounded-lg p-4">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Campo</th>
                                    @if(isset($log->properties['old']))
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Valor Anterior</th>
                                    @endif
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Valor Nuevo</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @if($log->description == 'updated' && isset($log->properties['old']) && isset($log->properties['attributes']))
                                    @foreach($log->properties['attributes'] as $key => $value)
                                        @if($key != 'updated_at' && $key != 'id' && array_key_exists($key, $log->properties['old']) && $log->properties['old'][$key] != $value)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $key }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                @if(is_array($log->properties['old'][$key]))
                                                    <pre class="text-xs">{{ json_encode($log->properties['old'][$key], JSON_PRETTY_PRINT) }}</pre>
                                                @else
                                                    {{ $log->properties['old'][$key] }}
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                @if(is_array($value))
                                                    <pre class="text-xs">{{ json_encode($value, JSON_PRETTY_PRINT) }}</pre>
                                                @else
                                                    {{ $value }}
                                                @endif
                                            </td>
                                        </tr>
                                        @endif
                                    @endforeach
                                @elseif($log->description == 'created' && isset($log->properties['attributes']))
                                    @foreach($log->properties['attributes'] as $key => $value)
                                        @if($key != 'created_at' && $key != 'updated_at' && $key != 'id')
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $key }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500" colspan="2">
                                                @if(is_array($value))
                                                    <pre class="text-xs">{{ json_encode($value, JSON_PRETTY_PRINT) }}</pre>
                                                @else
                                                    {{ $value }}
                                                @endif
                                            </td>
                                        </tr>
                                        @endif
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
                @else
                <div class="bg-gray-50 rounded-lg p-4 flex items-center justify-center">
                    <p class="text-gray-500">No hay información detallada sobre los cambios realizados</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
