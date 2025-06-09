@extends('mentor.layouts.app')

@section('title', $module->title . ' - ' . $course->name . ' - MentorHub')

@section('content')
<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <!-- Encabezado -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <nav class="flex" aria-label="Breadcrumb">
                <ol class="flex items-center space-x-2">
                    <li>
                        <div>
                            <a href="{{ route('mentor.courses.index') }}" class="text-gray-400 hover:text-gray-500">
                                <i class="fas fa-home"></i>
                                <span class="sr-only">Inicio</span>
                            </a>
                        </div>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <svg class="flex-shrink-0 h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                            </svg>
                            <a href="{{ route('mentor.courses.show', $course->id) }}" class="ml-2 text-sm font-medium text-gray-500 hover:text-gray-700">
                                {{ $course->name }}
                            </a>
                        </div>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <svg class="flex-shrink-0 h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                            </svg>
                            <a href="{{ route('mentor.courses.modules.index', $course->id) }}" class="ml-2 text-sm font-medium text-gray-500 hover:text-gray-700">
                                Módulos
                            </a>
                        </div>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <svg class="flex-shrink-0 h-5 w-5 text-gray-700" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                            </svg>
                            <span class="ml-2 text-sm font-medium text-gray-500">{{ $module->title }}</span>
                        </div>
                    </li>
                </ol>
            </nav>
            <h1 class="mt-2 text-2xl font-bold text-gray-900">{{ $module->title }}</h1>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('mentor.courses.modules.edit', ['course' => $course->id, 'module' => $module->id]) }}" 
               class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                <i class="fas fa-edit mr-2"></i> Editar
            </a>
            <a href="{{ route('mentor.courses.modules.index', $course->id) }}" 
               class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700">
                <i class="fas fa-arrow-left mr-2"></i> Volver a Módulos
            </a>
        </div>
    </div>

    <!-- Contenido Principal -->
    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
            <h3 class="text-lg leading-6 font-medium text-gray-900">
                Información del Módulo
            </h3>
            <p class="mt-1 max-w-2xl text-sm text-gray-500">
                Detalles y configuración del módulo.
            </p>
        </div>
        
        <div class="px-4 py-5 sm:p-6">
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                <div class="col-span-1">
                    <h4 class="text-sm font-medium text-gray-500">Título del Módulo</h4>
                    <p class="mt-1 text-sm text-gray-900">{{ $module->title }}</p>
                </div>
                <div class="col-span-1">
                    <h4 class="text-sm font-medium text-gray-500">Orden</h4>
                    <p class="mt-1 text-sm text-gray-900">{{ $module->order ?? 'No definido' }}</p>
                </div>
                <div class="col-span-1">
                    <h4 class="text-sm font-medium text-gray-500">Estado</h4>
                    @if($module->is_active)
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                            Activo
                        </span>
                    @else
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                            Inactivo
                        </span>
                    @endif
                </div>
                <div class="col-span-1">
                    <h4 class="text-sm font-medium text-gray-500">Fecha de creación</h4>
                    <p class="mt-1 text-sm text-gray-900">{{ $module->created_at->format('d/m/Y H:i') }}</p>
                </div>
                <div class="col-span-2">
                    <h4 class="text-sm font-medium text-gray-500">Descripción</h4>
                    <div class="mt-1 text-sm text-gray-900 prose max-w-none">
                        {!! $module->description ?? '<p class="text-gray-400">No hay descripción disponible</p>' !!}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Sección de Tutoriales -->
    <div class="mt-8">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-lg font-medium text-gray-900">Tutoriales del Módulo</h2>
            <a href="#" 
               class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700"
               onclick="alert('Funcionalidad para agregar tutoriales no implementada aún.');">
                <i class="fas fa-plus mr-2"></i> Nuevo Tutorial
            </a>
        </div>

        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            @if($module->tutorials->isEmpty())
                <div class="p-6 text-center text-gray-500">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">No hay tutoriales</h3>
                    <p class="mt-1 text-sm text-gray-500">Comienza creando tu primer tutorial para este módulo.</p>
                </div>
            @else
                <ul class="divide-y divide-gray-200">
                    @foreach($module->tutorials as $tutorial)
                        <li class="px-6 py-4 hover:bg-gray-50">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10 bg-blue-100 rounded-full flex items-center justify-center">
                                        <i class="fas fa-play text-blue-600"></i>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $tutorial->title }}</div>
                                        <div class="text-sm text-gray-500">
                                            {{ $tutorial->description ? \Illuminate\Support\Str::limit(strip_tags($tutorial->description), 100) : 'Sin descripción' }}
                                        </div>
                                    </div>
                                </div>
                                <div class="flex space-x-2">
                                    <button class="text-blue-600 hover:text-blue-900" onclick="alert('Ver tutorial no implementado');">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="text-yellow-600 hover:text-yellow-900" onclick="alert('Editar tutorial no implementado');">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <form action="#" method="POST" class="inline" onsubmit="alert('Eliminar tutorial no implementado'); return false;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Inicializar tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });
</script>
@endpush