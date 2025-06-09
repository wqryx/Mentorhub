@extends('mentor.layouts.app')

@section('title', 'Editar Curso - MentorHub')

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<style>
    .image-preview {
        max-width: 200px;
        max-height: 200px;
        margin-top: 10px;
    }
</style>
@endpush

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
                    <a href="{{ route('mentor.courses.show', $course->id) }}" class="ml-2 text-gray-500 hover:text-gray-700">{{ Str::limit($course->name, 20) }}</a>
                </div>
            </li>
            <li>
                <div class="flex items-center">
                    <i class="fas fa-chevron-right fa-xs text-gray-400"></i>
                    <span class="ml-2 text-gray-700">Editar</span>
                </div>
            </li>
        </ol>
    </nav>

    <!-- Page Header -->
    <div class="md:flex md:items-center md:justify-between mb-8">
        <div class="flex-1 min-w-0">
            <h1 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
                <i class="fas fa-edit text-blue-600 mr-2"></i>Editar Curso: {{ $course->name }}
            </h1>
        </div>
    </div>

    <!-- Form Section -->
    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <form action="{{ route('mentor.courses.update', $course->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
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
                                value="{{ old('name', $course->name) }}" placeholder="Ej: Desarrollo Web con Laravel">
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
                                value="{{ old('code', $course->code) }}" placeholder="Ej: DW-LARAVEL-01">
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
                                placeholder="Describe el contenido y objetivos del curso">{{ old('description', $course->description) }}</textarea>
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
                                    <option value="{{ $id }}" {{ (old('speciality_id', $course->speciality_id) == $id) ? 'selected' : '' }}>{{ $name }}</option>
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
                                <option value="Principiante" {{ (old('level', $course->level) == 'Principiante') ? 'selected' : '' }}>Principiante</option>
                                <option value="Intermedio" {{ (old('level', $course->level) == 'Intermedio') ? 'selected' : '' }}>Intermedio</option>
                                <option value="Avanzado" {{ (old('level', $course->level) == 'Avanzado') ? 'selected' : '' }}>Avanzado</option>
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
                                value="{{ old('credits', $course->credits) }}" placeholder="Ej: 5">
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
                                value="{{ old('hours_per_week', $course->hours_per_week) }}" placeholder="Ej: 10">
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
                                value="{{ old('start_date', $course->start_date ? $course->start_date->format('Y-m-d') : '') }}">
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
                                value="{{ old('end_date', $course->end_date ? $course->end_date->format('Y-m-d') : '') }}">
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
                                value="{{ old('classroom', $course->classroom) }}" placeholder="Ej: Aula 101 o Enlace Zoom">
                            @error('classroom')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Status -->
                    <div class="sm:col-span-3">
                        <label for="status" class="block text-sm font-medium text-gray-700">
                            Estado <span class="text-red-500">*</span>
                        </label>
                        <div class="mt-1">
                            <select id="status" name="status" required
                                class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                <option value="draft" {{ (old('status', $course->status) == 'draft') ? 'selected' : '' }}>Borrador</option>
                                <option value="published" {{ (old('status', $course->status) == 'published') ? 'selected' : '' }}>Publicado</option>
                                <option value="archived" {{ (old('status', $course->status) == 'archived') ? 'selected' : '' }}>Archivado</option>
                            </select>
                            @error('status')
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
                                @if($course->image)
                                    <img id="image-preview" src="{{ Storage::url($course->image) }}" alt="{{ $course->name }}" class="h-full w-full object-cover">
                                @else
                                    <svg id="image-preview-placeholder" class="h-full w-full text-gray-300" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M24 20.993V24H0v-2.996A14.977 14.977 0 0112.004 15c4.904 0 9.26 2.354 11.996 5.993zM16.002 8.999a4 4 0 11-8 0 4 4 0 018 0z" />
                                    </svg>
                                @endif
                            </span>
                            <div class="ml-4">
                                <input type="file" name="image" id="image" class="hidden" onchange="previewImage(this)">
                                <label for="image" class="cursor-pointer bg-white py-2 px-3 border border-gray-300 rounded-md shadow-sm text-sm leading-4 font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    Cambiar imagen
                                </label>
                                <p class="mt-1 text-xs text-gray-500">
                                    PNG, JPG o GIF (máx. 2MB)
                                </p>
                                @error('image')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Is Premium -->
                    <div class="sm:col-span-6">
                        <div class="flex items-start">
                            <div class="flex items-center h-5">
                                <input id="is_premium" name="is_premium" type="checkbox"
                                    class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300 rounded"
                                    {{ old('is_premium', $course->is_premium) ? 'checked' : '' }}>
                            </div>
                            <div class="ml-3 text-sm">
                                <label for="is_premium" class="font-medium text-gray-700">Curso Premium</label>
                                <p class="text-gray-500">Los cursos premium requieren suscripción para acceder a su contenido.</p>
                            </div>
                        </div>
                        @error('is_premium')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="mt-8 pt-5 border-t border-gray-200 flex justify-between">
                    <div>
                        <button type="button" 
                                onclick="if(confirm('¿Estás seguro de que deseas eliminar este curso? Esta acción no se puede deshacer.')) { document.getElementById('delete-form').submit(); }" 
                                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                            <i class="fas fa-trash mr-2"></i> Eliminar Curso
                        </button>
                    </div>
                    <div class="flex space-x-3">
                        <a href="{{ route('mentor.courses.show', $course->id) }}"
                            class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Cancelar
                        </a>
                        <button type="submit"
                            class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <i class="fas fa-save mr-2"></i> Guardar Cambios
                        </button>
                    </div>
                </div>
            </div>
        </form>

        <!-- Delete Form -->
        <form id="delete-form" action="{{ route('mentor.courses.destroy', $course->id) }}" method="POST" class="hidden">
            @csrf
            @method('DELETE')
        </form>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
    // Initialize date picker
    flatpickr("[data-flatpickr]", {
        dateFormat: "Y-m-d",
        allowInput: true
    });

    // Image preview
    function previewImage(input) {
        const preview = document.getElementById('image-preview');
        const previewPlaceholder = document.getElementById('image-preview-placeholder');
        
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            
            reader.onload = function(e) {
                if (preview) {
                    preview.src = e.target.result;
                } else if (previewPlaceholder) {
                    previewPlaceholder.outerHTML = `<img id="image-preview" src="${e.target.result}" alt="Vista previa" class="h-full w-full object-cover">`;
                }
            }
            
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endpush
@endsection