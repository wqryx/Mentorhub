@extends('mentor.layouts.app')

@section('title', 'Crear Nuevo Curso - MentorHub')

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
                    <span class="ml-2 text-gray-700">Nuevo Curso</span>
                </div>
            </li>
        </ol>
    </nav>

    <!-- Page Header -->
    <div class="md:flex md:items-center md:justify-between mb-8">
        <div class="flex-1 min-w-0">
            <h1 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
                <i class="fas fa-plus-circle text-blue-600 mr-2"></i>Crear Nuevo Curso
            </h1>
        </div>
    </div>

    <!-- Form Section -->
    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <form action="{{ route('mentor.courses.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="px-4 py-5 sm:p-6">
                <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                    <!-- Course Name -->
                    <div class="sm:col-span-4">
                        <label for="name" class="block text-sm font-medium text-gray-700">
                            Nombre del Curso <span class="text-red-500">*</span>
                        </label>
                        <div class="mt-1">
                            <input type="text" name="name" id="name" required
                                class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md"
                                value="{{ old('name') }}" placeholder="Ej: Desarrollo Web con Laravel">
                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Course Code -->
                    <div class="sm:col-span-2">
                        <label for="code" class="block text-sm font-medium text-gray-700">
                            Código del Curso <span class="text-red-500">*</span>
                        </label>
                        <div class="mt-1">
                            <input type="text" name="code" id="code" required
                                class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md"
                                value="{{ old('code') }}" placeholder="Ej: DW-LARAVEL-01">
                            @error('code')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Description -->
                    <div class="sm:col-span-6">
                        <label for="description" class="block text-sm font-medium text-gray-700">
                            Descripción del Curso
                        </label>
                        <div class="mt-1">
                            <textarea id="description" name="description" rows="3"
                                class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border border-gray-300 rounded-md"
                                placeholder="Describe el contenido y objetivos del curso">{{ old('description') }}</textarea>
                            @error('description')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Speciality -->
                    <div class="sm:col-span-3">
                        <label for="speciality_id" class="block text-sm font-medium text-gray-700">
                            Especialidad
                        </label>
                        <div class="mt-1">
                            <select id="speciality_id" name="speciality_id"
                                class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                <option value="">Seleccionar especialidad</option>
                                @foreach($specialities as $id => $name)
                                    <option value="{{ $id }}" {{ old('speciality_id') == $id ? 'selected' : '' }}>{{ $name }}</option>
                                @endforeach
                            </select>
                            @error('speciality_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Level -->
                    <div class="sm:col-span-3">
                        <label for="level" class="block text-sm font-medium text-gray-700">
                            Nivel <span class="text-red-500">*</span>
                        </label>
                        <div class="mt-1">
                            <select id="level" name="level" required
                                class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                <option value="Principiante" {{ old('level') == 'Principiante' ? 'selected' : '' }}>Principiante</option>
                                <option value="Intermedio" {{ old('level') == 'Intermedio' ? 'selected' : '' }}>Intermedio</option>
                                <option value="Avanzado" {{ old('level') == 'Avanzado' ? 'selected' : '' }}>Avanzado</option>
                            </select>
                            @error('level')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Credits -->
                    <div class="sm:col-span-2">
                        <label for="credits" class="block text-sm font-medium text-gray-700">
                            Créditos
                        </label>
                        <div class="mt-1">
                            <input type="number" name="credits" id="credits" min="0"
                                class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md"
                                value="{{ old('credits') }}" placeholder="Ej: 5">
                            @error('credits')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Hours per Week -->
                    <div class="sm:col-span-2">
                        <label for="hours_per_week" class="block text-sm font-medium text-gray-700">
                            Horas por Semana
                        </label>
                        <div class="mt-1">
                            <input type="number" name="hours_per_week" id="hours_per_week" min="0"
                                class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md"
                                value="{{ old('hours_per_week') }}" placeholder="Ej: 10">
                            @error('hours_per_week')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Start Date -->
                    <div class="sm:col-span-3">
                        <label for="start_date" class="block text-sm font-medium text-gray-700">
                            Fecha de Inicio <span class="text-red-500">*</span>
                        </label>
                        <div class="mt-1">
                            <input type="date" name="start_date" id="start_date" required
                                class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md"
                                value="{{ old('start_date') }}">
                            @error('start_date')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- End Date -->
                    <div class="sm:col-span-3">
                        <label for="end_date" class="block text-sm font-medium text-gray-700">
                            Fecha de Finalización
                        </label>
                        <div class="mt-1">
                            <input type="date" name="end_date" id="end_date"
                                class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md"
                                value="{{ old('end_date') }}">
                            @error('end_date')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Classroom -->
                    <div class="sm:col-span-3">
                        <label for="classroom" class="block text-sm font-medium text-gray-700">
                            Aula/Virtual
                        </label>
                        <div class="mt-1">
                            <input type="text" name="classroom" id="classroom"
                                class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md"
                                value="{{ old('classroom') }}" placeholder="Ej: Aula 101 o Enlace Zoom">
                            @error('classroom')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Schedule -->
                    <div class="sm:col-span-3">
                        <label for="schedule" class="block text-sm font-medium text-gray-700">
                            Horario
                        </label>
                        <div class="mt-1">
                            <input type="text" name="schedule" id="schedule"
                                class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md"
                                value="{{ old('schedule') }}" placeholder="Ej: Lunes y Miércoles 18:00-20:00">
                            @error('schedule')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Course Image -->
                    <div class="sm:col-span-6">
                        <label class="block text-sm font-medium text-gray-700">
                            Imagen del Curso
                        </label>
                        <div class="mt-1 flex items-center">
                            <span class="inline-block h-12 w-12 rounded-full overflow-hidden bg-gray-100">
                                <svg class="h-full w-full text-gray-300" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M24 20.993V24H0v-2.996A14.977 14.977 0 0112.004 15c4.904 0 9.26 2.354 11.996 5.993zM16.002 8.999a4 4 0 11-8 0 4 4 0 018 0z" />
                                </svg>
                            </span>
                            <label for="course_image" class="ml-5">
                                <div class="py-2 px-3 border border-gray-300 rounded-md shadow-sm text-sm leading-4 font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 cursor-pointer">
                                    Cambiar
                                </div>
                                <input id="course_image" name="course_image" type="file" class="sr-only" accept="image/*">
                            </label>
                        </div>
                        @error('course_image')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Active Status -->
                    <div class="sm:col-span-6">
                        <div class="flex items-start">
                            <div class="flex items-center h-5">
                                <input id="is_active" name="is_active" type="checkbox" value="1"
                                    class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300 rounded"
                                    {{ old('is_active', true) ? 'checked' : '' }}>
                            </div>
                            <div class="ml-3 text-sm">
                                <label for="is_active" class="font-medium text-gray-700">Curso activo</label>
                                <p class="text-gray-500">Los cursos inactivos no serán visibles para los estudiantes.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="mt-8 pt-5 border-t border-gray-200 flex justify-end">
                    <a href="{{ route('mentor.courses.index') }}"
                        class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Cancelar
                    </a>
                    <button type="submit"
                        class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <i class="fas fa-save mr-2"></i>Guardar Curso
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    // Actualizar vista previa de la imagen seleccionada
    document.getElementById('course_image').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const preview = document.createElement('img');
                preview.src = e.target.result;
                preview.className = 'h-12 w-12 rounded-full object-cover';
                document.querySelector('.bg-gray-100').innerHTML = '';
                document.querySelector('.bg-gray-100').appendChild(preview);
            }
            reader.readAsDataURL(file);
        }
    });

    // Validar que la fecha de fin sea posterior a la de inicio
    document.getElementById('start_date').addEventListener('change', function() {
        const endDate = document.getElementById('end_date');
        if (endDate.value && new Date(endDate.value) < new Date(this.value)) {
            endDate.value = '';
            alert('La fecha de finalización debe ser posterior a la fecha de inicio');
        }
    });
</script>
@endpush
@endsection
