@extends('mentor.layouts.app')

@section('title', 'Crear Nueva Sesión de Mentoría - MentorHub')

@section('content')
<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <!-- Encabezado -->
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Programar Nueva Sesión de Mentoría</h1>
        <a href="{{ route('mentor.mentorias.index') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
            <i class="fas fa-arrow-left mr-2"></i> Volver a Mis Mentorías
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
                Información de la Mentoría
            </h3>
            <p class="mt-1 max-w-2xl text-sm text-gray-500">
                Complete la información requerida para crear una nueva mentoría.
            </p>
        </div>
        <div class="px-4 py-5 sm:p-6">
            <form action="{{ route('mentor.mentorias.store') }}" method="POST" class="space-y-6">
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
                    
                    <!-- Tipo de Sesión -->
                    <div>
                        <label for="type" class="block text-sm font-medium text-gray-700">Tipo de Sesión <span class="text-red-500">*</span></label>
                        <select id="type" name="type" required
                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('type') border-red-300 text-red-900 placeholder-red-300 focus:outline-none focus:ring-red-500 focus:border-red-500 @enderror">
                            <option value="" disabled {{ old('type') ? '' : 'selected' }}>Selecciona un tipo</option>
                            <option value="one_time" {{ old('type') == 'one_time' ? 'selected' : '' }}>Sesión Única</option>
                            <option value="recurring" {{ old('type') == 'recurring' ? 'selected' : '' }}>Sesión Recurrente</option>
                        </select>
                        @error('type')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @else
                            <p class="mt-2 text-sm text-gray-500">Selecciona si es una sesión única o recurrente.</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                    <!-- Estudiante -->
                    <div>
                        <label for="student_id" class="block text-sm font-medium text-gray-700">Estudiante <span class="text-red-500">*</span></label>
                        <select id="student_id" name="student_id" required
                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('student_id') border-red-300 text-red-900 placeholder-red-300 focus:outline-none focus:ring-red-500 focus:border-red-500 @enderror">
                            <option value="" selected disabled>Seleccionar estudiante</option>
                            @foreach($mentees as $id => $name)
                                <option value="{{ $id }}" {{ old('student_id') == $id ? 'selected' : '' }}>{{ $name }}</option>
                            @endforeach
                        </select>
                        @error('student_id')
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
                    <label for="description" class="block text-sm font-medium text-gray-700">Descripción <span class="text-red-500">*</span></label>
                    <div class="mt-1">
                        <textarea id="description" name="description" rows="4" required
                                  class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full border border-gray-300 rounded-md sm:text-sm @error('description') border-red-300 text-red-900 placeholder-red-300 focus:outline-none focus:ring-red-500 focus:border-red-500 @enderror"
                                  placeholder="Describe los temas a tratar, objetivos de la sesión y cualquier otro detalle relevante.">{{ old('description') }}</textarea>
                    </div>
                    @error('description')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @else
                        <p class="mt-2 text-sm text-gray-500">Describe los temas a tratar, objetivos de la sesión y cualquier otro detalle relevante.</p>
                    @enderror
                </div>

                <!-- Objetivos del Estudiante -->
                <div>
                    <label for="student_goals" class="block text-sm font-medium text-gray-700">Objetivos del Estudiante</label>
                    <div class="mt-1">
                        <textarea id="student_goals" name="student_goals" rows="2"
                                  class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full border border-gray-300 rounded-md sm:text-sm @error('student_goals') border-red-300 text-red-900 placeholder-red-300 focus:outline-none focus:ring-red-500 focus:border-red-500 @enderror"
                                  placeholder="¿Qué espera lograr el estudiante con esta sesión?">{{ old('student_goals') }}</textarea>
                    </div>
                    @error('student_goals')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @else
                        <p class="mt-2 text-sm text-gray-500">¿Qué espera lograr el estudiante con esta sesión?</p>
                    @enderror
                </div>

                <!-- Detalles de la Sesión -->
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                    <!-- Fecha y Hora de Inicio -->
                    <div>
                        <label for="start_time" class="block text-sm font-medium text-gray-700">Fecha y Hora de Inicio <span class="text-red-500">*</span></label>
                        <input type="datetime-local" id="start_time" name="start_time" 
                               value="{{ old('start_time') }}" required
                               min="{{ now()->format('Y-m-d\TH:i') }}"
                               class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('start_time') border-red-300 text-red-900 placeholder-red-300 focus:outline-none focus:ring-red-500 focus:border-red-500 @enderror">
                        @error('start_time')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @else
                            <p class="mt-2 text-sm text-gray-500">Fecha y hora de inicio de la sesión.</p>
                        @enderror
                    </div>

                    <!-- Duración -->
                    <div>
                        <label for="duration_minutes" class="block text-sm font-medium text-gray-700">Duración (minutos) <span class="text-red-500">*</span></label>
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <input type="number" id="duration_minutes" name="duration_minutes" 
                                   value="{{ old('duration_minutes', 60) }}" min="15" max="240" step="15" required
                                   class="focus:ring-blue-500 focus:border-blue-500 block w-full pr-12 sm:text-sm border-gray-300 rounded-md @error('duration_minutes') border-red-300 text-red-900 placeholder-red-300 focus:outline-none focus:ring-red-500 focus:border-red-500 @enderror">
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 sm:text-sm">min</span>
                            </div>
                        </div>
                        @error('duration_minutes')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @else
                            <p class="mt-2 text-sm text-gray-500">Duración en minutos (15-240, en incrementos de 15).</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                    <!-- Formato de la Sesión -->
                    <div>
                        <label for="format" class="block text-sm font-medium text-gray-700">Formato <span class="text-red-500">*</span></label>
                        <select id="format" name="format" required
                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('format') border-red-300 text-red-900 placeholder-red-300 focus:outline-none focus:ring-red-500 focus:border-red-500 @enderror">
                            <option value="" disabled {{ old('format') ? '' : 'selected' }}>Selecciona un formato</option>
                            <option value="video_call" {{ old('format') == 'video_call' ? 'selected' : '' }}>Videollamada</option>
                            <option value="phone_call" {{ old('format') == 'phone_call' ? 'selected' : '' }}>Llamada telefónica</option>
                            <option value="in_person" {{ old('format') == 'in_person' ? 'selected' : '' }}>En persona</option>
                        </select>
                        @error('format')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @else
                            <p class="mt-2 text-sm text-gray-500">Selecciona el formato de la sesión.</p>
                        @enderror
                    </div>

                    <!-- Enlace de la Reunión -->
                    <div>
                        <label for="meeting_link" class="block text-sm font-medium text-gray-700">Enlace de la Reunión</label>
                        <div class="mt-1">
                            <input type="url" id="meeting_link" name="meeting_link" 
                                   value="{{ old('meeting_link') }}"
                                   placeholder="https://meet.google.com/..."
                                   class="block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('meeting_link') border-red-300 text-red-900 placeholder-red-300 focus:outline-none focus:ring-red-500 focus:border-red-500 @enderror">
                        </div>
                        @error('meeting_link')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @else
                            <p class="mt-2 text-sm text-gray-500">Deja en blanco para generar automáticamente (si aplica).</p>
                        @enderror
                    </div>
                </div>

                <!-- Tarifa y Recurrencia -->
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                    <!-- Tarifa de la Sesión -->
                    <div>
                        <label for="session_fee" class="block text-sm font-medium text-gray-700">Tarifa de la Sesión (€)</label>
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 sm:text-sm">€</span>
                            </div>
                            <input type="number" id="session_fee" name="session_fee" 
                                   value="{{ old('session_fee', 0) }}" min="0" step="0.01"
                                   class="focus:ring-blue-500 focus:border-blue-500 block w-full pl-7 pr-12 sm:text-sm border-gray-300 rounded-md @error('session_fee') border-red-300 text-red-900 placeholder-red-300 focus:outline-none focus:ring-red-500 focus:border-red-500 @enderror">
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                <span class="text-gray-500 sm:text-sm">por sesión</span>
                            </div>
                        </div>
                        @error('session_fee')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @else
                            <p class="mt-2 text-sm text-gray-500">Deja en 0 para ofrecer la sesión sin costo.</p>
                        @enderror
                    </div>

                    <!-- Sesión Recurrente -->
                    <div class="flex items-start pt-6">
                        <div class="flex items-center h-5">
                            <input id="is_recurring" name="is_recurring" type="checkbox" value="1" 
                                   {{ old('is_recurring') ? 'checked' : '' }}
                                   class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300 rounded">
                        </div>
                        <div class="ml-3 text-sm">
                            <label for="is_recurring" class="font-medium text-gray-700">Sesión recurrente</label>
                            <p class="text-gray-500">¿Esta sesión se repite periódicamente?</p>
                        </div>
                    </div>
                </div>

                <!-- Patrón de Recurrencia (condicional) -->
                <div id="recurrence_pattern_container" class="hidden">
                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                        <div>
                            <label for="recurrence_pattern" class="block text-sm font-medium text-gray-700">Frecuencia</label>
                            <select id="recurrence_pattern" name="recurrence_pattern"
                                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                <option value="weekly" {{ old('recurrence_pattern') == 'weekly' ? 'selected' : '' }}>Semanal</option>
                                <option value="biweekly" {{ old('recurrence_pattern') == 'biweekly' ? 'selected' : '' }}>Quincenal</option>
                                <option value="monthly" {{ old('recurrence_pattern') == 'monthly' ? 'selected' : '' }}>Mensual</option>
                            </select>
                        </div>
                        <div>
                            <label for="recurrence_end_date" class="block text-sm font-medium text-gray-700">Fecha de Finalización</label>
                            <input type="date" id="recurrence_end_date" name="recurrence_end_date" 
                                   value="{{ old('recurrence_end_date') }}"
                                   min="{{ now()->format('Y-m-d') }}"
                                   class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                        </div>
                    </div>
                </div>

                <!-- Notas del Mentor -->
                <div>
                    <label for="mentor_notes" class="block text-sm font-medium text-gray-700">Notas Privadas</label>
                    <div class="mt-1">
                        <textarea id="mentor_notes" name="mentor_notes" rows="3"
                                  class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full border border-gray-300 rounded-md sm:text-sm @error('mentor_notes') border-red-300 text-red-900 placeholder-red-300 focus:outline-none focus:ring-red-500 focus:border-red-500 @enderror"
                                  placeholder="Notas privadas que solo tú podrás ver.">{{ old('mentor_notes') }}</textarea>
                    </div>
                    @error('mentor_notes')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @else
                        <p class="mt-2 text-sm text-gray-500">Estas notas son solo para tu referencia y no serán visibles para el estudiante.</p>
                    @enderror
                </div>

                <div class="flex items-start pt-6 border-t border-gray-200 mt-6">
                    <div class="flex items-center h-5">
                        <input id="is_active" name="is_active" type="checkbox" value="1" checked
                               class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300 rounded">
                    </div>
                    <div class="ml-3 text-sm">
                        <label for="is_active" class="font-medium text-gray-700">Activar esta mentoría</label>
                        <p class="text-gray-500">Haz que esta mentoría sea visible para los estudiantes.</p>
                    </div>
                </div>

                <div class="pt-6 border-t border-gray-200 mt-6">
                    <div class="flex justify-end space-x-3">
                        <button type="reset" class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Limpiar
                        </button>
                        <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                            </svg>
                            Guardar Mentoría
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Elementos del DOM
        const isRecurringCheckbox = document.getElementById('is_recurring');
        const recurrenceContainer = document.getElementById('recurrence_pattern_container');
        const startTimeInput = document.getElementById('start_time');
            const durationInput = document.getElementById('duration_minutes');
            const formatSelect = document.getElementById('format');
            const meetingLinkInput = document.getElementById('meeting_link');
            
            // Mostrar/ocultar la configuración de recurrencia
            function toggleRecurrenceSettings() {
                if (isRecurringCheckbox.checked) {
                    recurrenceContainer.classList.remove('hidden');
                } else {
                    recurrenceContainer.classList.add('hidden');
                }
            }
            
            // Generar enlace de reunión basado en el formato seleccionado
            function generateMeetingLink() {
                if (meetingLinkInput.value.trim() === '') {
                    const format = formatSelect.value;
                    let link = '';
                    
                    switch(format) {
                        case 'video':
                            link = 'https://meet.google.com/new';
                            break;
                        case 'audio':
                            link = 'https://meet.jit.si/' + Math.random().toString(36).substring(2, 15);
                            break;
                        case 'chat':
                            link = 'https://slack.com';
                            break;
                        default:
                            link = '';
                    }
                    
                    meetingLinkInput.value = link;
                }
            }
            
            // Validar fecha y hora de inicio
            function validateDateTime() {
                const now = new Date();
                const selectedDate = new Date(startTimeInput.value);
                
                if (selectedDate < now) {
                    alert('La fecha y hora de inicio deben ser futuras.');
                    const tomorrow = new Date();
                    tomorrow.setDate(tomorrow.getDate() + 1);
                    startTimeInput.value = tomorrow.toISOString().slice(0, 16);
                }
            }
            
            // Validar duración
            function validateDuration() {
                let duration = parseInt(durationInput.value);
                
                if (isNaN(duration) || duration < 15) {
                    durationInput.value = 15;
                } else if (duration > 240) {
                    durationInput.value = 240;
                } else {
                    // Redondear al múltiplo de 15 más cercano
                    durationInput.value = Math.round(duration / 15) * 15;
                }
            }
            
            // Lógica para habilitar/deshabilitar campos de horario según el checkbox del día
            const dayCheckboxes = document.querySelectorAll('input[name="days[]"]');
            
            function toggleTimeInputs(checkbox) {
                const dayIndex = checkbox.value;
                const card = checkbox.closest('.border-gray-200');
                const timeInputs = card ? card.querySelectorAll('input[type="time"]') : [];
                
                timeInputs.forEach(input => {
                    input.disabled = !checkbox.checked;
                    input.required = checkbox.checked;
                    
                    // Cambiar estilos visuales según el estado
                    if (checkbox.checked) {
                        input.classList.remove('bg-gray-100', 'text-gray-400');
                        input.classList.add('bg-white', 'text-gray-900');
                    } else {
                        input.classList.remove('bg-white', 'text-gray-900');
                        input.classList.add('bg-gray-100', 'text-gray-400');
                    }
                });
            }
            
            // Configurar eventos para cada checkbox
            dayCheckboxes.forEach(checkbox => {
                // Estado inicial
                toggleTimeInputs(checkbox);
                
                // Cambio de estado
                checkbox.addEventListener('change', function() {
                    toggleTimeInputs(this);
                });
            });
            
            // Validación de horas (opcional)
            document.querySelector('form').addEventListener('submit', function(e) {
                let isValid = true;
                const checkedDays = Array.from(dayCheckboxes).filter(cb => cb.checked);
                
                checkedDays.forEach(checkbox => {
                    const dayIndex = checkbox.value;
                    const card = checkbox.closest('.border-gray-200');
                    const startTime = card ? card.querySelector('input[name^="start_time"]') : null;
                    const endTime = card ? card.querySelector('input[name^="end_time"]') : null;
                    
                    if (startTime && endTime && startTime.value >= endTime.value) {
                        isValid = false;
                        // Resaltar campos con error
                        startTime.classList.add('border-red-500');
                        endTime.classList.add('border-red-500');
                    } else {
                        if (startTime) startTime.classList.remove('border-red-500');
                        if (endTime) endTime.classList.remove('border-red-500');
                    }
                });
                
                if (!isValid) {
                    e.preventDefault();
                    alert('La hora de inicio debe ser anterior a la hora de finalización para todos los días seleccionados.');
                }
            });
            
            // Event Listeners
            isRecurringCheckbox.addEventListener('change', toggleRecurrenceSettings);
            formatSelect.addEventListener('change', generateMeetingLink);
            startTimeInput.addEventListener('change', validateDateTime);
            durationInput.addEventListener('change', validateDuration);
            durationInput.addEventListener('blur', validateDuration);
            
            // Inicialización
            toggleRecurrenceSettings();
            validateDuration();
            
            // Si hay un error en el formato, mostrar el contenedor de recurrencia
            const oldIsRecurring = @json(old('is_recurring') ? true : false);
            if (oldIsRecurring) {
                recurrenceContainer.classList.remove('hidden');
            }
    });
</script>
@endpush
