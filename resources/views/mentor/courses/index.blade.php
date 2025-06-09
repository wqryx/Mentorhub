@extends('mentor.layouts.app')

@section('title', 'Mis Cursos - MentorHub')

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
@endpush

@section('content')
<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
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

    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Mis Cursos</h1>
            <p class="mt-1 text-sm text-gray-500">Gestiona tus cursos y contenidos</p>
        </div>
        <a href="{{ route('mentor.courses.create') }}" 
           class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
            <i class="fas fa-plus mr-2"></i> Nuevo Curso
        </a>
    </div>

    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        @if(isset($courses) && $courses->isEmpty())
            <div class="p-12 text-center">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">No hay cursos</h3>
                <p class="mt-1 text-sm text-gray-500">Comienza creando tu primer curso.</p>
                <div class="mt-6">
                    <a href="{{ route('mentor.courses.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <i class="fas fa-plus -ml-1 mr-2"></i>
                        Nuevo Curso
                    </a>
                </div>
            </div>
        @elseif(isset($courses))
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Título</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Código</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estudiantes</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                            <th scope="col" class="relative px-6 py-3">
                                <span class="sr-only">Acciones</span>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($courses as $course)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10 bg-blue-100 rounded-md flex items-center justify-center">
                                            <i class="fas fa-book text-blue-600"></i>
                                        </div>
                                        <div class="ml-4">
                                            <a href="{{ route('mentor.courses.show', $course->id) }}" class="text-sm font-medium text-blue-600 hover:text-blue-800">
                                                {{ $course->name }}
                                            </a>
                                            <div class="text-sm text-gray-500">
                                                {{ $course->modules_count ?? (isset($course->modules) ? $course->modules->count() : 0) }} módulos
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $course->code }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $course->students_count ?? (isset($course->enrollments) ? $course->enrollments->count() : 0) }} estudiantes
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($course->is_active)
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                            Activo
                                        </span>
                                    @else
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                            Inactivo
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex justify-end space-x-2">
                                        <a href="{{ route('mentor.courses.show', $course->id) }}" 
                                           class="text-blue-600 hover:text-blue-900 p-1 rounded-md hover:bg-blue-100 transition-colors" 
                                           title="Ver detalles"
                                           data-bs-toggle="tooltip">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('mentor.courses.edit', $course->id) }}" 
                                           class="text-yellow-600 hover:text-yellow-900 p-1 rounded-md hover:bg-yellow-100 transition-colors" 
                                           title="Editar"
                                           data-bs-toggle="tooltip">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="{{ route('mentor.courses.students', $course->id) }}" 
                                           class="text-purple-600 hover:text-purple-900 p-1 rounded-md hover:bg-purple-100 transition-colors" 
                                           title="Estudiantes"
                                           data-bs-toggle="tooltip">
                                            <i class="fas fa-users"></i>
                                        </a>
                                        <a href="{{ route('mentor.courses.statistics', $course->id) }}" 
                                           class="text-green-600 hover:text-green-900 p-1 rounded-md hover:bg-green-100 transition-colors" 
                                           title="Estadísticas"
                                           data-bs-toggle="tooltip">
                                            <i class="fas fa-chart-bar"></i>
                                        </a>
                                        <form action="{{ route('mentor.courses.destroy', $course->id) }}" method="POST" 
                                              onsubmit="return confirm('¿Estás seguro de que deseas eliminar este curso? Esta acción no se puede deshacer.');" 
                                              class="inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="text-red-600 hover:text-red-900 p-1 rounded-md hover:bg-red-100 transition-colors" 
                                                    title="Eliminar"
                                                    data-bs-toggle="tooltip">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @if($courses->hasPages())
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $courses->links() }}
                </div>
            @endif
        @else
            <div class="p-6 text-center text-gray-500">
                Cargando cursos...
            </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Inicialización de tooltips si se usa Bootstrap
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof bootstrap !== 'undefined') {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        }
    });
</script>
@endpush
