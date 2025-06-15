@extends('mentor.layouts.app')

@section('title', 'Detalles del Curso: ' . $course->name . ' - MentorHub')

@section('content')
<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <!-- Breadcrumb Navigation -->
    <nav class="flex mb-6" aria-label="Breadcrumb">
        <ol class="flex items-center space-x-2 text-sm">
            <li>
                <a href="{{ route('mentor.dashboard') }}" class="text-gray-400 hover:text-gray-500">
                    <i class="fas fa-home"></i>
                    <span class="sr-only">Dashboard</span>
                </a>
            </li>
            <li>
                <div class="flex items-center">
                    <i class="fas fa-chevron-right fa-xs text-gray-400"></i>
                    <a href="{{ route('mentor.courses.index') }}" class="ml-2 text-gray-500 hover:text-gray-700">Mis Cursos</a>
                </div>
            </li>
            <li>
                <div class="flex items-center">
                    <i class="fas fa-chevron-right fa-xs text-gray-400"></i>
                    <span class="ml-2 text-gray-700 truncate max-w-xs" title="{{ $course->name }}">{{ $course->name }}</span>
                </div>
            </li>
        </ol>
    </nav>

    <!-- Page Header with Actions -->
    <div class="md:flex md:items-center md:justify-between mb-8">
        <div class="flex-1 min-w-0">
            <h1 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
                <i class="fas fa-book text-blue-600 mr-2"></i>{{ $course->name }}
            </h1>
            <div class="mt-1 flex flex-col sm:flex-row sm:flex-wrap sm:mt-0 sm:space-x-6">
                <div class="mt-2 flex items-center text-sm text-gray-500">
                    <i class="fas fa-tag mr-1.5 h-5 w-5 text-gray-400"></i>
                    {{ $course->code }}
                </div>
                @if($course->speciality)
                <div class="mt-2 flex items-center text-sm text-gray-500">
                    <i class="fas fa-star mr-1.5 h-5 w-5 text-yellow-400"></i>
                    {{ $course->speciality->name }}
                </div>
                @endif
                <div class="mt-2 flex items-center text-sm text-gray-500">
                    <i class="fas fa-layer-group mr-1.5 h-5 w-5 text-blue-400"></i>
                    {{ $course->modules_count ?? 0 }} Módulos
                </div>
                <div class="mt-2 flex items-center text-sm text-gray-500">
                    <i class="fas fa-users mr-1.5 h-5 w-5 text-green-500"></i>
                    {{ $course->enrollments_count ?? 0 }} Estudiantes
                </div>
            </div>
        </div>
        <div class="mt-4 flex md:mt-0 md:ml-4">
            <a href="{{ route('mentor.courses.modules.index', $course->id) }}" 
               class="ml-3 inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                <i class="fas fa-layer-group mr-2"></i> Gestionar Cursos
            </a>
            <a href="{{ route('mentor.courses.edit', $course->id) }}" 
               class="ml-3 inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-yellow-600 hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500">
                <i class="fas fa-edit mr-2"></i> Editar Cursos
            </a>
        </div>
    </div>

    <!-- Course Details -->
    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 sm:px-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900">
                <i class="fas fa-info-circle text-blue-500 mr-2"></i>Información del Curso
            </h3>
        </div>
        <div class="border-t border-gray-200 px-4 py-5 sm:p-0">
            <dl class="sm:divide-y sm:divide-gray-200">
                <!-- Descripción -->
                <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">
                        <i class="fas fa-align-left mr-2"></i>Descripción
                    </dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        {!! $course->description ?: '<span class="text-gray-400 italic">Sin descripción</span>' !!}
                    </dd>
                </div>

                @if($course->what_will_learn)
                <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">
                        <i class="fas fa-check-circle mr-2"></i>Lo que aprenderás
                    </dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        {!! $course->what_will_learn !!}
                    </dd>
                </div>
                @endif
                @if($course->requirements)
                <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">
                        <i class="fas fa-tasks mr-2"></i>Requisitos
                    </dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        {!! $course->requirements !!}
                    </dd>
                </div>
                @endif

                @if($course->level)
                <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">
                        <i class="fas fa-signal mr-2"></i>Nivel
                    </dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        {{ $course->level }}
                    </dd>
                </div>
                @endif

                @if($course->credits)
                <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">
                        <i class="fas fa-award mr-2"></i>Créditos
                    </dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        {{ $course->credits }} créditos
                    </dd>
                </div>
                @endif

                @if($course->hours_per_week)
                <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">
                        <i class="far fa-clock mr-2"></i>Horas por Semana
                    </dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        {{ $course->hours_per_week }} horas
                    </dd>
                </div>
                @endif

                @if($course->start_date)
                <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">
                        <i class="far fa-calendar-alt mr-2"></i>Fecha de Inicio
                    </dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        {{ $course->start_date->format('d/m/Y') }}
                    </dd>
                </div>
                @endif

                @if($course->end_date)
                <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">
                        <i class="far fa-calendar-check mr-2"></i>Fecha de Finalización
                    </dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        {{ $course->end_date->format('d/m/Y') }}
                    </dd>
                </div>
                @endif

                @if($course->classroom)
                <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">
                        <i class="fas fa-chalkboard-teacher mr-2"></i>Aula/Virtual
                    </dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        {{ $course->classroom }}
                    </dd>
                </div>
                @endif

                @if($course->schedule)
                <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">
                        <i class="far fa-calendar mr-2"></i>Horario
                    </dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        {{ $course->schedule }}
                    </dd>
                </div>
                @endif

                <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">
                        <i class="fas fa-toggle-on mr-2"></i>Estado
                    </dt>
                    <dd class="mt-1 text-sm sm:mt-0 sm:col-span-2">
                        @if($course->is_active)
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                Activo
                            </span>
                        @else
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                Inactivo
                            </span>
                        @endif
                    </dd>
                </div>

                @if($course->created_at)
                <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">
                        <i class="far fa-calendar-plus mr-2"></i>Creado el
                    </dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        {{ $course->created_at->format('d/m/Y H:i') }}
                    </dd>
                </div>
                @endif

                @if($course->updated_at)
                <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">
                        <i class="fas fa-sync-alt mr-2"></i>Última actualización
                    </dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        {{ $course->updated_at->format('d/m/Y H:i') }}
                    </dd>
                </div>
                @endif
            </dl>
        </div>
    </div>

    <!-- Modules Section -->
    <div class="mt-8">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-medium text-gray-900">
                <i class="fas fa-layer-group text-blue-500 mr-2"></i>Módulos del Curso
            </h3>
            <a href="{{ route('mentor.courses.modules.create', $course->id) }}" 
               class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                <i class="fas fa-plus mr-2"></i>Nuevo Módulo
            </a>
        </div>

        @if($course->modules && $course->modules->count() > 0)
            <div class="bg-white shadow overflow-hidden sm:rounded-md">
                <ul class="divide-y divide-gray-200">
                    @foreach($course->modules as $module)
                    <li>
                        <div class="px-4 py-4 sm:px-6 hover:bg-gray-50">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <span class="text-gray-500 mr-4">#{{ $module->order }}</span>
                                    <p class="text-sm font-medium text-blue-600 truncate">
                                        {{ $module->name }}
                                    </p>
                                </div>
                                <div class="ml-2 flex-shrink-0 flex">
                                    <a href="{{ route('mentor.courses.modules.edit', [$course->id, $module->id]) }}" 
                                       class="mr-2 p-1 rounded-full hover:bg-gray-200 text-gray-500 hover:text-gray-600">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('mentor.courses.modules.destroy', [$course->id, $module->id]) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1 rounded-full hover:bg-red-100 text-red-500 hover:text-red-600" 
                                                onclick="return confirm('¿Estás seguro de eliminar este módulo? Esta acción no se puede deshacer.')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                            @if($module->description)
                            <div class="mt-2 text-sm text-gray-500">
                                {!! \Illuminate\Support\Str::limit(strip_tags($module->description), 150) !!}
                            </div>
                            @endif
                            <div class="mt-2 flex justify-between items-center">
                                <div class="flex space-x-2">
                                    @if($module->is_free)
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        Gratis
                                    </span>
                                    @endif
                                    @if($module->is_active)
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                        Activo
                                    </span>
                                    @else
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                        Inactivo
                                    </span>
                                    @endif
                                </div>
                                <div class="text-sm text-gray-500">
                                    <a href="{{ route('mentor.courses.modules.show', [$course->id, $module->id]) }}" 
                                       class="text-blue-600 hover:text-blue-800">
                                        Ver detalles <i class="fas fa-arrow-right ml-1"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </li>
                    @endforeach
                </ul>
            </div>
        @else
            <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                <div class="px-4 py-5 sm:p-6 text-center">
                    <i class="far fa-folder-open text-gray-400 text-4xl mb-3"></i>
                    <h3 class="text-lg font-medium text-gray-900 mb-1">No hay módulos</h3>
                    <p class="text-sm text-gray-500 mb-4">Aún no has creado ningún módulo para este curso.</p>
                    <a href="{{ route('mentor.courses.modules.create', $course->id) }}" 
                       class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <i class="fas fa-plus mr-2"></i>Crear Primer Módulo
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>

@push('styles')
<style>
    /* Estilos personalizados si son necesarios */
    .module-item:hover {
        background-color: #f9fafb;
    }
</style>
@endpush

@push('scripts')
<script>
    // Scripts personalizados si son necesarios
    document.addEventListener('DOMContentLoaded', function() {
        // Inicializar tooltips si es necesario
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });
</script>
@endpush
@endsection
