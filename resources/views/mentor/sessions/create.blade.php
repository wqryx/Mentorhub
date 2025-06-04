@extends('mentor.layouts.app')

@section('title', 'Programar Nueva Sesión - MentorHub')

@section('content')
<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <!-- Encabezado -->
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Programar Nueva Sesión</h1>
        <a href="{{ route('mentor.sessions.index') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
            <i class="fas fa-arrow-left mr-2"></i> Volver a Mis Sesiones
        </a>
    </div>

    <!-- Mensajes de alerta -->
    @if(session('success'))
        <div class="rounded-md bg-green-50 p-4 mb-6">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-green-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                </div>
            </div>
        </div>
    @endif

    @if($errors->any())
        <div class="rounded-md bg-red-50 p-4 mb-6">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-red-800">
                        Hay {{ $errors->count() }} error(es) en el formulario
                    </h3>
                    <div class="mt-2 text-sm text-red-700">
                        <ul class="list-disc pl-5 space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Formulario de creación -->
    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
            <h3 class="text-lg leading-6 font-medium text-gray-900">
                Información de la Sesión
            </h3>
            <p class="mt-1 max-w-2xl text-sm text-gray-500">
                Complete la información requerida para crear una nueva sesión.
            </p>
        </div>
        <div class="px-4 py-5 sm:p-6">
            <form action="{{ route('mentor.sessions.store') }}" method="POST" class="space-y-6">
                @csrf
                
                <!-- Información Básica -->
                <div class="grid grid-cols-1 gap-6">
                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-700">Título de la Sesión <span class="text-red-500">*</span></label>
                        <input type="text" name="title" id="title" value="{{ old('title') }}" required
                               class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('title') border-red-300 text-red-900 placeholder-red-300 focus:outline-none focus:ring-red-500 focus:border-red-500 @enderror"
                               placeholder="Ej: Introducción a Laravel">
                        @error('title')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                    <!-- Estudiante -->
                    <div>
                        <label for="mentee_id" class="block text-sm font-medium text-gray-700">Estudiante <span class="text-red-500">*</span></label>
                        <select id="mentee_id" name="mentee_id" required
                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('mentee_id') border-red-300 text-red-900 placeholder-red-300 focus:outline-none focus:ring-red-500 focus:border-red-500 @enderror">
                            <option value="" selected disabled>Seleccionar estudiante</option>
                            @foreach($mentees as $id => $name)
                                <option value="{{ $id }}" {{ old('mentee_id') == $id ? 'selected' : '' }}>{{ $name }}</option>
                            @endforeach
                        </select>
                        @error('mentee_id')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Curso (Opcional) -->
                    <div>
                        <label for="course_id" class="block text-sm font-medium text-gray-700">Curso (Opcional)</label>
                        <select id="course_id" name="course_id"
                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('course_id') border-red-300 text-red-900 placeholder-red-300 focus:outline-none focus:ring-red-500 focus:border-red-500 @enderror">
                            <option value="" selected>Sin curso específico</option>
                            @foreach($courses ?? [] as $id => $title)
                                <option value="{{ $id }}" {{ old('course_id') == $id ? 'selected' : '' }}>{{ $title }}</option>
                            @endforeach
                        </select>
                        @error('course_id')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Descripción -->
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700">Descripción</label>
                    <div class="mt-1">
                        <textarea id="description" name="description" rows="4"
                                  class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full border border-gray-300 rounded-md sm:text-sm @error('description') border-red-300 text-red-900 placeholder-red-300 focus:outline-none focus:ring-red-500 focus:border-red-500 @enderror"
                                  placeholder="Describe los temas a tratar en la sesión y cualquier otro detalle relevante.">{{ old('description') }}</textarea>
                    </div>
                    @error('description')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @else
                        <p class="mt-2 text-sm text-gray-500">Describe los temas a tratar en la sesión y cualquier otro detalle relevante.</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                    <!-- Fecha y Hora -->
                    <div>
                        <label for="scheduled_at" class="block text-sm font-medium text-gray-700">Fecha y Hora <span class="text-red-500">*</span></label>
                        <input type="datetime-local" id="scheduled_at" name="scheduled_at" 
                               value="{{ old('scheduled_at') }}" required
                               min="{{ now()->format('Y-m-d\TH:i') }}"
                               class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('scheduled_at') border-red-300 text-red-900 placeholder-red-300 focus:outline-none focus:ring-red-500 focus:border-red-500 @enderror">
                        @error('scheduled_at')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @else
                            <p class="mt-2 text-sm text-gray-500">Fecha y hora de inicio de la sesión.</p>
                        @enderror
                    </div>

                    <!-- Duración -->
                    <div>
                        <label for="duration" class="block text-sm font-medium text-gray-700">Duración (minutos) <span class="text-red-500">*</span></label>
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <select id="duration" name="duration" required
                                    class="focus:ring-blue-500 focus:border-blue-500 block w-full border-gray-300 rounded-md sm:text-sm @error('duration') border-red-300 text-red-900 placeholder-red-300 focus:outline-none focus:ring-red-500 focus:border-red-500 @enderror">
                                <option value="30" {{ old('duration') == 30 ? 'selected' : '' }}>30 minutos</option>
                                <option value="45" {{ old('duration') == 45 || !old('duration') ? 'selected' : '' }}>45 minutos</option>
                                <option value="60" {{ old('duration') == 60 ? 'selected' : '' }}>1 hora</option>
                                <option value="90" {{ old('duration') == 90 ? 'selected' : '' }}>1 hora 30 minutos</option>
                                <option value="120" {{ old('duration') == 120 ? 'selected' : '' }}>2 horas</option>
                            </select>
                        </div>
                        @error('duration')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @else
                            <p class="mt-2 text-sm text-gray-500">Selecciona la duración de la sesión.</p>
                        @enderror
                    </div>
                </div>

                <!-- Formato de la Sesión -->
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                    <div>
                        <label for="format" class="block text-sm font-medium text-gray-700">Formato <span class="text-red-500">*</span></label>
                        <select id="format" name="format" required
                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('format') border-red-300 text-red-900 placeholder-red-300 focus:outline-none focus:ring-red-500 focus:border-red-500 @enderror">
                            <option value="video" {{ old('format') == 'video' ? 'selected' : '' }}>Videollamada</option>
                            <option value="audio" {{ old('format') == 'audio' ? 'selected' : '' }}>Llamada de voz</option>
                            <option value="chat" {{ old('format') == 'chat' ? 'selected' : '' }}>Chat</option>
                            <option value="in-person" {{ old('format') == 'in-person' ? 'selected' : '' }}>En persona</option>
                        </select>
                        @error('format')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @else
                            <p class="mt-2 text-sm text-gray-500">Selecciona el formato de la sesión.</p>
                        @enderror
                    </div>

                    <!-- Enlace de la Reunión -->
                    <div>
                        <label for="meeting_url" class="block text-sm font-medium text-gray-700">Enlace de la Reunión</label>
                        <div class="mt-1">
                            <input type="url" id="meeting_url" name="meeting_url" 
                                   value="{{ old('meeting_url') }}"
                                   placeholder="https://meet.google.com/xxx-xxxx-xxx"
                                   class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full border border-gray-300 rounded-md sm:text-sm @error('meeting_url') border-red-300 text-red-900 placeholder-red-300 focus:outline-none focus:ring-red-500 focus:border-red-500 @enderror">
                        </div>
                        @error('meeting_url')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @else
                            <p class="mt-2 text-sm text-gray-500">Introduce un enlace a Zoom, Google Meet u otra plataforma de videoconferencia.</p>
                        @enderror
                    </div>
                </div>

                <!-- Notas Adicionales -->
                <div>
                    <label for="notes" class="block text-sm font-medium text-gray-700">Notas Adicionales</label>
                    <div class="mt-1">
                        <textarea id="notes" name="notes" rows="3"
                                  class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full border border-gray-300 rounded-md sm:text-sm @error('notes') border-red-300 text-red-900 placeholder-red-300 focus:outline-none focus:ring-red-500 focus:border-red-500 @enderror"
                                  placeholder="Cualquier información adicional que quieras incluir.">{{ old('notes') }}</textarea>
                    </div>
                    @error('notes')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @endif
                </div>

                <!-- Botones de Acción -->
                <div class="pt-5 border-t border-gray-200">
                    <div class="flex justify-end space-x-3">
                        <a href="{{ route('mentor.sessions.index') }}" class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Cancelar
                        </a>
                        <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <i class="fas fa-save mr-2"></i> Guardar Sesión
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Inicializar select2 si está disponible
        if (typeof $.fn.select2 !== 'undefined') {
            $('#mentee_id').select2({
                placeholder: 'Seleccionar estudiante...',
                allowClear: true,
                width: '100%',
                dropdownParent: $('#mentee_id').parent()
            });
            
            $('#course_id').select2({
                placeholder: 'Seleccionar curso...',
                allowClear: true,
                width: '100%',
                dropdownParent: $('#course_id').parent()
            });
        }

        // Validación del formulario
        const form = document.querySelector('form');
        if (form) {
            form.addEventListener('submit', function(e) {
                const title = document.getElementById('title').value.trim();
                const menteeId = document.getElementById('mentee_id').value;
                const scheduledAt = document.getElementById('scheduled_at').value;
                
                if (!title || !menteeId || !scheduledAt) {
                    e.preventDefault();
                    alert('Por favor completa todos los campos requeridos.');
                }
            });
        }
    });
</script>
@endpush
