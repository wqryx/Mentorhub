@extends('mentor.layouts.app')

@section('title', 'Mis Cursos')

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
@endpush

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
    <!-- Header -->
    <div class="md:flex md:items-center md:justify-between mb-6">
        <div class="flex-1 min-w-0">
            <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
                Mis Cursos
            </h2>
        </div>
        <div class="mt-4 md:mt-0 md:ml-4">
            <a href="{{ route('mentor.courses.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                <i class="fas fa-plus -ml-1 mr-2"></i>
                Nuevo Curso
            </a>
        </div>
    </div>

    <!-- Success Message -->
    @if(session('success'))
    <div x-data="{ show: true }" 
         x-show="show" 
         x-init="setTimeout(() => show = false, 5000)"
         class="mb-6 rounded-md bg-green-50 p-4">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                </svg>
            </div>
            <div class="ml-3">
                <p class="text-sm font-medium text-green-800">
                    {{ session('success') }}
                </p>
            </div>
            <div class="ml-auto pl-3">
                <div class="-mx-1.5 -my-1.5">
                    <button @click="show = false" class="inline-flex rounded-md p-1.5 text-green-500 hover:bg-green-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-600">
                        <span class="sr-only">Cerrar</span>
                        <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Main Content -->
    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <!-- Card Header -->
        <div class="px-4 py-5 sm:px-6 border-b border-gray-200 sm:flex sm:items-center sm:justify-between">
            <div>
                <h3 class="text-lg leading-6 font-medium text-gray-900">
                    Cursos Disponibles
                </h3>
                <p class="mt-1 max-w-2xl text-sm text-gray-500">
                    Gestiona tus cursos y contenidos
                </p>
            </div>
            <div class="mt-4 sm:mt-0">
                <div class="relative inline-block text-left" x-data="{ open: false }">
                    <button @click="open = !open" type="button" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500" id="options-menu" aria-expanded="true" aria-haspopup="true">
                        Filtrar
                        <svg class="-mr-1 ml-2 h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </button>
                    <div x-show="open" @click.away="open = false" class="origin-top-right absolute right-0 mt-2 w-56 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 focus:outline-none z-10" role="menu" aria-orientation="vertical" aria-labelledby="options-menu">
                        <div class="py-1" role="none">
                            <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900" role="menuitem">
                                <i class="fas fa-sort-alpha-down mr-2 text-gray-400"></i> Ordenar por nombre
                            </a>
                            <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900" role="menuitem">
                                <i class="fas fa-calendar-alt mr-2 text-gray-400"></i> Ordenar por fecha
                            </a>
                            <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900" role="menuitem">
                                <i class="fas fa-users mr-2 text-gray-400"></i> Ordenar por estudiantes
                            </a>
                            <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900" role="menuitem">
                                <i class="fas fa-file-export mr-2 text-gray-400"></i> Exportar Lista
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Course List -->
        @if(isset($courses) && $courses->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 p-4">
            @foreach($courses as $course)
            <div class="bg-white border rounded-lg shadow-sm overflow-hidden hover:shadow-md transition-shadow duration-300">
                <div class="relative h-40 bg-blue-50">
                    @if($course->image)
                    <img src="{{ asset('storage/' . $course->image) }}" alt="{{ $course->title }}" class="w-full h-full object-cover">
                    @else
                    <div class="w-full h-full flex items-center justify-center">
                        <i class="fas fa-book-open text-blue-300 text-5xl"></i>
                    </div>
                    @endif
                    <div class="absolute top-2 right-2">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $course->is_published ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                            {{ $course->is_published ? 'Publicado' : 'Borrador' }}
                        </span>
                    </div>
                </div>
                <div class="p-4">
                    <h3 class="text-lg font-bold text-gray-900 mb-2">{{ $course->title }}</h3>
                    <p class="text-sm text-gray-600 mb-4 line-clamp-2">
                        {{ Str::limit(strip_tags($course->description), 100) }}
                    </p>
                    <div class="flex items-center justify-between text-sm text-gray-500 mb-4">
                        <div class="flex items-center">
                            <i class="fas fa-layer-group mr-1"></i>
                            <span>{{ $course->modules_count ?? $course->modules->count() }} módulos</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-users mr-1"></i>
                            <span>{{ $course->students_count ?? $course->students->count() }} estudiantes</span>
                        </div>
                    </div>
                    <div class="flex flex-wrap gap-2">
                        <a href="{{ route('mentor.courses.show', $course->id) }}" 
                           class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                           data-tippy-content="Ver detalles del curso">
                            <i class="fas fa-eye mr-1"></i> Ver
                        </a>
                        <a href="{{ route('mentor.courses.edit', $course->id) }}" 
                           class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-white bg-yellow-500 hover:bg-yellow-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-400"
                           data-tippy-content="Editar curso">
                            <i class="fas fa-edit mr-1"></i> Editar
                        </a>
                        <a href="{{ route('mentor.courses.students', $course->id) }}" 
                           class="inline-flex items-center px-3 py-1.5 border border-gray-300 text-xs font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                           data-tippy-content="Ver estudiantes inscritos">
                            <i class="fas fa-users mr-1"></i> Estudiantes
                        </a>
                        <a href="{{ route('mentor.courses.statistics', $course->id) }}" 
                           class="inline-flex items-center px-3 py-1.5 border border-gray-300 text-xs font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                           data-tippy-content="Ver estadísticas del curso">
                            <i class="fas fa-chart-bar mr-1"></i> Stats
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="px-4 py-5 sm:px-6 border-t border-gray-200">
            {{ $courses->links() }}
        </div>
        
        @else
        <div class="text-center py-12">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900">No hay cursos</h3>
            <p class="mt-1 text-sm text-gray-500">
                Comienza a crear cursos para tus estudiantes.
            </p>
            <div class="mt-6">
                <a href="{{ route('mentor.courses.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <i class="fas fa-plus -ml-1 mr-2"></i>
                    Nuevo Curso
                </a>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://unpkg.com/@popperjs/core@2"></script>
<script src="https://unpkg.com/tippy.js@6"></script>
<script>
    document.addEventListener('alpine:init', () => {
        // Initialize Tippy.js tooltips
        tippy('[data-tippy-content]', {
            placement: 'top',
            animation: 'scale',
            arrow: true,
            theme: 'light',
        });
    });
</script>
@endpush
