@extends('mentor.layouts.app')

@section('title', 'Editar Sesión - MentorHub')

@section('content')
<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Editar Sesión</h1>
            <p class="text-sm text-gray-500">Actualiza los detalles de la sesión de mentoría</p>
        </div>
        <div>
            <a href="{{ route('mentor.sessions.show', $session->id) }}" 
               class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md shadow-sm text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                <i class="fas fa-arrow-left mr-2"></i> Volver a la sesión
            </a>
        </div>
    </div>

    @include('partials.alerts')

    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
            <h3 class="text-lg leading-6 font-medium text-gray-900">
                Información de la Sesión
            </h3>
            <p class="mt-1 max-w-2xl text-sm text-gray-500">
                Actualiza los detalles de la sesión programada.
            </p>
        </div>
        <div class="px-4 py-5 sm:p-6">
            <form action="{{ route('mentor.sessions.update', $session->id) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')
                
                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700">
                        Título de la Sesión <span class="text-red-500">*</span>
                    </label>
                    <div class="mt-1">
                        <input type="text" 
                               class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md @error('title') border-red-300 text-red-900 placeholder-red-300 focus:outline-none focus:ring-red-500 focus:border-red-500 @enderror" 
                               id="title" 
                               name="title" 
                               value="{{ old('title', $session->title) }}" 
                               required>
                        @error('title')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                    <div class="sm:col-span-3">
                        <label for="mentee_id" class="block text-sm font-medium text-gray-700">
                            Estudiante <span class="text-red-500">*</span>
                        </label>
                        <div class="mt-1">
                            <select id="mentee_id" 
                                    name="mentee_id" 
                                    class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md @error('mentee_id') border-red-300 text-red-900 placeholder-red-300 focus:outline-none focus:ring-red-500 focus:border-red-500 @enderror"
                                    required>
                                <option value="">Seleccionar estudiante...</option>
                                @foreach($mentees as $id => $name)
                                    <option value="{{ $id }}" {{ old('mentee_id', $session->mentee_id) == $id ? 'selected' : '' }}>
                                        {{ $name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('mentee_id')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="sm:col-span-3">
                        <label for="course_id" class="block text-sm font-medium text-gray-700">
                            Curso (opcional)
                        </label>
                        <div class="mt-1">
                            <select id="course_id" 
                                    name="course_id" 
                                    class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md @error('course_id') border-red-300 text-red-900 placeholder-red-300 focus:outline-none focus:ring-red-500 focus:border-red-500 @enderror">
                                <option value="">Seleccionar curso...</option>
                                @if(isset($courses))
                                    @foreach($courses as $id => $title)
                                        <option value="{{ $id }}" {{ old('course_id', $session->course_id) == $id ? 'selected' : '' }}>
                                            {{ $title }}
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                            @error('course_id')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                    <div class="sm:col-span-3">
                        <label for="scheduled_at" class="block text-sm font-medium text-gray-700">
                            Fecha y Hora <span class="text-red-500">*</span>
                        </label>
                        <div class="mt-1">
                            <input type="datetime-local" 
                                   class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md @error('scheduled_at') border-red-300 text-red-900 placeholder-red-300 focus:outline-none focus:ring-red-500 focus:border-red-500 @enderror"
                                   id="scheduled_at" 
                                   name="scheduled_at" 
                                   value="{{ old('scheduled_at', $session->scheduled_at ? $session->scheduled_at->format('Y-m-d\TH:i') : '') }}" 
                                   min="{{ now()->format('Y-m-d\TH:i') }}"
                                   required>
                            @error('scheduled_at')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="sm:col-span-3">
                        <label for="duration" class="block text-sm font-medium text-gray-700">
                            Duración <span class="text-red-500">*</span>
                        </label>
                        <div class="mt-1">
                            <select id="duration" 
                                    name="duration" 
                                    class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md @error('duration') border-red-300 text-red-900 placeholder-red-300 focus:outline-none focus:ring-red-500 focus:border-red-500 @enderror"
                                    required>
                                <option value="30" {{ old('duration', $session->duration) == 30 ? 'selected' : '' }}>30 minutos</option>
                                <option value="45" {{ old('duration', $session->duration) == 45 ? 'selected' : '' }}>45 minutos</option>
                                <option value="60" {{ old('duration', $session->duration) == 60 ? 'selected' : '' }}>1 hora</option>
                                <option value="90" {{ old('duration', $session->duration) == 90 ? 'selected' : '' }}>1 hora 30 minutos</option>
                                <option value="120" {{ old('duration', $session->duration) == 120 ? 'selected' : '' }}>2 horas</option>
                            </select>
                            @error('duration')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700">
                        Descripción
                    </label>
                    <div class="mt-1">
                        <textarea id="description" 
                                 name="description" 
                                 rows="3" 
                                 class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border border-gray-300 rounded-md @error('description') border-red-300 text-red-900 placeholder-red-300 focus:outline-none focus:ring-red-500 focus:border-red-500 @enderror">{{ old('description', $session->description) }}</textarea>
                        @error('description')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <label for="meeting_url" class="block text-sm font-medium text-gray-700">
                        Enlace de la Reunión
                    </label>
                    <div class="mt-1">
                        <input type="url" 
                               class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md @error('meeting_url') border-red-300 text-red-900 placeholder-red-300 focus:outline-none focus:ring-red-500 focus:border-red-500 @enderror" 
                               id="meeting_url" 
                               name="meeting_url" 
                               value="{{ old('meeting_url', $session->meeting_url) }}"
                               placeholder="https://meet.google.com/...">
                        @error('meeting_url')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <p class="mt-2 text-sm text-gray-500">URL de Google Meet, Zoom, o la plataforma que utilices.</p>
                </div>

                <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                    <div class="sm:col-span-3">
                        <label for="status" class="block text-sm font-medium text-gray-700">
                            Estado <span class="text-red-500">*</span>
                        </label>
                        <div class="mt-1">
                            <select id="status" 
                                    name="status" 
                                    class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md @error('status') border-red-300 text-red-900 placeholder-red-300 focus:outline-none focus:ring-red-500 focus:border-red-500 @enderror"
                                    required>
                                <option value="scheduled" {{ old('status', $session->status) == 'scheduled' ? 'selected' : '' }}>Programada</option>
                                <option value="completed" {{ old('status', $session->status) == 'completed' ? 'selected' : '' }}>Completada</option>
                                <option value="cancelled" {{ old('status', $session->status) == 'cancelled' ? 'selected' : '' }}>Cancelada</option>
                            </select>
                            @error('status')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <div>
                    <label for="notes" class="block text-sm font-medium text-gray-700">
                        Notas
                    </label>
                    <div class="mt-1">
                        <textarea id="notes" 
                                 name="notes" 
                                 rows="3" 
                                 class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border border-gray-300 rounded-md @error('notes') border-red-300 text-red-900 placeholder-red-300 focus:outline-none focus:ring-red-500 focus:border-red-500 @enderror">{{ old('notes', $session->notes) }}</textarea>
                        @error('notes')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <p class="mt-2 text-sm text-gray-500">Notas adicionales sobre la sesión (solo visibles para ti).</p>
                </div>

                <div class="pt-5">
                    <div class="flex justify-end gap-3">
                        <a href="{{ route('mentor.sessions.show', $session->id) }}" 
                           class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Cancelar
                        </a>
                        <button type="submit" 
                                class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <i class="fas fa-save mr-2"></i> Guardar Cambios
                        </button>
                    </div>
                </div>
                    </form>
                </div>
            </div>
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
                allowClear: true
            });
            
            $('#course_id').select2({
                placeholder: 'Seleccionar curso...',
                allowClear: true
            });
            
            $('#status').select2();
        }
    });
</script>
@endpush
