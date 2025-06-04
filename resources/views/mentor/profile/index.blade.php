@extends('mentor.layouts.app')

@section('title', 'Mi Perfil - MentorHub')

@push('styles')
<!-- Estilos específicos para el perfil si son necesarios -->
@endpush

@section('content')
<div class="container mx-auto">
    <!-- Encabezado -->
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-semibold text-gray-800">Mi Perfil</h1>
    </div>

    <!-- Contenido del perfil -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Columna izquierda: Foto y datos básicos -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <!-- Cabecera con foto de perfil -->
                <div class="p-6 flex flex-col items-center border-b border-gray-200">
                    <div class="relative mb-4">
                        <img class="h-32 w-32 rounded-full object-cover border-4 border-white shadow" 
                            src="{{ auth()->user()->photo_url ?? 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->name) . '&color=7F9CF5&background=EBF4FF' }}">
                        <button class="absolute bottom-0 right-0 bg-blue-600 text-white rounded-full p-2 shadow hover:bg-blue-700 transition-colors duration-150"
                                data-bs-toggle="modal" data-bs-target="#changePhotoModal">
                            <i class="fas fa-camera"></i>
                        </button>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800">{{ auth()->user()->name }}</h3>
                    <p class="text-sm text-gray-500">Mentor</p>
                </div>
                <!-- Cuerpo con información básica -->
                <div class="p-6">
                    <div class="space-y-4">
                        <h4 class="text-lg font-semibold text-blue-600 mb-2">Información de Contacto</h4>
                        <div class="flex items-center text-gray-700">
                            <i class="fas fa-envelope text-gray-400 w-6"></i>
                            <span class="ml-2">{{ auth()->user()->email }}</span>
                        </div>
                        <div class="flex items-center text-gray-700">
                            <i class="fas fa-phone text-gray-400 w-6"></i>
                            <span class="ml-2">{{ auth()->user()->phone ?? 'No especificado' }}</span>
                        </div>
                        <div class="flex items-center text-gray-700">
                            <i class="fas fa-map-marker-alt text-gray-400 w-6"></i>
                            <span class="ml-2">{{ auth()->user()->location ?? 'No especificado' }}</span>
                        </div>
                    </div>
                    <div class="mt-4">
                        <h4 class="text-lg font-semibold text-blue-600 mb-2">Estadísticas</h4>
                        <div class="mb-2 flex justify-between items-center">
                            <span class="text-gray-600">Sesiones completadas:</span>
                            <span class="font-bold">{{ $completedSessions ?? 0 }}</span>
                        </div>
                        <div class="mb-2 flex justify-between items-center">
                            <span class="text-gray-600">Estudiantes activos:</span>
                            <span class="font-bold">{{ $activeStudents ?? 0 }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Calificación promedio:</span>
                            <div class="flex items-center">
                                <span class="font-bold mr-1">{{ $averageRating ?? '0.0' }}</span>
                                <i class="fas fa-star text-yellow-400"></i>
                            </div>
                        </div>
                    </div>
                    <div class="mt-6">
                        <h4 class="text-lg font-semibold text-blue-600 mb-2">Especialidades</h4>
                        <div class="flex flex-wrap gap-2">
                            @forelse($specialities ?? [] as $speciality)
                                <span class="px-2 py-1 bg-blue-500 text-white text-xs rounded-full">{{ $speciality->name }}</span>
                            @empty
                                <span class="text-gray-500">No hay especialidades definidas</span>
                            @endforelse
                        </div>
                    </div>
                    <div class="text-center mt-6">
                        <button class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors duration-150 inline-flex items-center" 
                                data-bs-toggle="modal" data-bs-target="#editBasicInfoModal">
                            <i class="fas fa-edit mr-2"></i> Editar información básica
                        </button>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Columna derecha: Pestañas con más información -->
        <div class="lg:col-span-2">
            <!-- Navegación de pestañas -->
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="border-b border-gray-200">
                    <ul class="flex flex-wrap -mb-px" role="tablist">
                        <li class="mr-2">
                            <a class="inline-block py-4 px-4 text-blue-600 border-b-2 border-blue-600 font-medium text-sm" 
                               id="bio-tab" data-bs-toggle="tab" href="#bio" role="tab" aria-selected="true">
                                <i class="fas fa-user-circle mr-2"></i> Biografía y Experiencia
                            </a>
                        </li>
                        <li class="mr-2">
                            <a class="inline-block py-4 px-4 text-gray-500 hover:text-gray-600 hover:border-gray-300 border-b-2 border-transparent font-medium text-sm" 
                               id="specialities-tab" data-bs-toggle="tab" href="#specialities" role="tab" aria-selected="false">
                                <i class="fas fa-graduation-cap mr-2"></i> Especialidades
                            </a>
                        </li>
                        <li class="mr-2">
                            <a class="inline-block py-4 px-4 text-gray-500 hover:text-gray-600 hover:border-gray-300 border-b-2 border-transparent font-medium text-sm" 
                               id="availability-tab" data-bs-toggle="tab" href="#availability" role="tab" aria-selected="false">
                                <i class="fas fa-calendar-alt mr-2"></i> Disponibilidad
                            </a>
                        </li>
                        <li class="mr-2">
                            <a class="inline-block py-4 px-4 text-gray-500 hover:text-gray-600 hover:border-gray-300 border-b-2 border-transparent font-medium text-sm" 
                               id="settings-tab" data-bs-toggle="tab" href="#settings" role="tab" aria-selected="false">
                                <i class="fas fa-cog mr-2"></i> Configuración
                            </a>
                        </li>
                    </ul>
                </div>
                
                <!-- Contenido de las pestañas -->
                <div class="p-6">
                    <div class="tab-content">
                        <!-- Pestaña de Biografía -->
                        <div class="tab-pane fade show active" id="bio" role="tabpanel">
                            <h3 class="text-xl font-semibold text-gray-800 mb-4">Biografía Profesional</h3>
                            <div class="mb-6">
                                @if(isset($mentorProfile) && $mentorProfile->bio)
                                    <p class="text-gray-700 mb-4">{{ $mentorProfile->bio }}</p>
                                @else
                                    <p class="text-gray-500 mb-4">No has agregado una biografía profesional todavía. Comparte tu experiencia y conocimientos para que los estudiantes te conozcan mejor.</p>
                                @endif
                                <button class="inline-flex items-center px-3 py-1.5 border border-blue-600 text-blue-600 rounded-md hover:bg-blue-50 transition-colors duration-150" data-bs-toggle="modal" data-bs-target="#editBioModal">
                                    <i class="fas fa-edit mr-2"></i> Editar biografía
                                </button>
                            </div>
                            
                            <h3 class="text-xl font-semibold text-gray-800 mb-4">Experiencia Profesional</h3>
                            <div class="space-y-4">
                                @forelse($experiences ?? [] as $experience)
                                    <div class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden mb-4">
                                        <div class="p-4">
                                            <div class="flex justify-between items-start">
                                                <h4 class="text-lg font-medium text-blue-600 mb-1">{{ $experience->position }}</h4>
                                                <div class="flex space-x-2">
                                                    <button class="text-blue-500 hover:text-blue-700 transition-colors duration-150" data-bs-toggle="modal" data-bs-target="#editExperienceModal" data-id="{{ $experience->id }}">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                    <button class="text-red-500 hover:text-red-700 transition-colors duration-150" data-bs-toggle="modal" data-bs-target="#deleteExperienceModal" data-id="{{ $experience->id }}">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                            </div>
                                            <h5 class="text-base font-normal text-gray-500 mb-2">{{ $experience->company }}</h5>
                                            <p class="text-sm text-gray-500 mb-3">
                                                {{ $experience->start_date->format('M Y') }} - 
                                                @if($experience->current)
                                                    Presente
                                                @else
                                                    {{ $experience->end_date->format('M Y') }}
                                                @endif
                                            </p>
                                            <p class="text-gray-700">{{ $experience->description }}</p>
                                        </div>
                                    </div>
                                @empty
                                    <p class="text-gray-500">No has agregado experiencia profesional todavía.</p>
                                @endforelse
                                <button class="inline-flex items-center px-3 py-1.5 border border-blue-600 text-blue-600 rounded-md hover:bg-blue-50 transition-colors duration-150" data-bs-toggle="modal" data-bs-target="#addExperienceModal">
                                    <i class="fas fa-plus-circle mr-2"></i> Añadir experiencia
                                </button>
                            </div>
                            
                            <h3 class="text-xl font-semibold text-gray-800 mb-4">Educación</h3>
                            <div class="space-y-4">
                                @forelse($education ?? [] as $edu)
                                    <div class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden mb-4">
                                        <div class="p-4">
                                            <div class="flex justify-between items-start">
                                                <h4 class="text-lg font-medium text-blue-600 mb-1">{{ $edu->degree }}</h4>
                                                <div class="flex space-x-2">
                                                    <button class="text-blue-500 hover:text-blue-700 transition-colors duration-150" data-bs-toggle="modal" data-bs-target="#editEducationModal" data-id="{{ $edu->id }}">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                    <button class="text-red-500 hover:text-red-700 transition-colors duration-150" data-bs-toggle="modal" data-bs-target="#deleteEducationModal" data-id="{{ $edu->id }}">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                            </div>
                                            <h5 class="text-base font-normal text-gray-500 mb-2">{{ $edu->institution }}</h5>
                                            <p class="text-sm text-gray-500 mb-3">
                                                {{ $edu->start_year }} - 
                                                @if($edu->current)
                                                    Presente
                                                @else
                                                    {{ $edu->end_year }}
                                                @endif
                                            </p>
                                            <p class="text-gray-700">{{ $edu->description }}</p>
                                        </div>
                                    </div>
                                @empty
                                    <p class="text-gray-500">No has agregado información educativa todavía.</p>
                                @endforelse
                                <button class="inline-flex items-center px-3 py-1.5 border border-blue-600 text-blue-600 rounded-md hover:bg-blue-50 transition-colors duration-150" data-bs-toggle="modal" data-bs-target="#addEducationModal">
                                    <i class="fas fa-plus-circle mr-2"></i> Añadir educación
                                </button>
                            </div>
                        </div>
                        
                        <!-- Pestaña de Especialidades -->
                        <div class="tab-pane fade" id="specialities" role="tabpanel">
                            <h3 class="text-xl font-semibold text-gray-800 mb-2">Mis Especialidades</h3>
                            <p class="text-gray-600 mb-6">Selecciona las áreas en las que tienes experiencia y conocimientos para ofrecer mentoría.</p>
                            
                            <div class="mb-6">
                                <div class="flex flex-wrap gap-2 mb-4">
                                    @forelse($specialities ?? [] as $speciality)
                                        <div class="bg-blue-500 text-white text-sm px-3 py-1.5 rounded-full flex items-center">
                                            {{ $speciality->name }}
                                            <button class="ml-2 text-white hover:text-white/80 transition-colors duration-150" data-speciality-id="{{ $speciality->id }}">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </div>
                                    @empty
                                        <p class="text-gray-500">No has seleccionado especialidades todavía.</p>
                                    @endforelse
                                </div>
                                <button class="inline-flex items-center px-3 py-1.5 border border-blue-600 text-blue-600 rounded-md hover:bg-blue-50 transition-colors duration-150" data-bs-toggle="modal" data-bs-target="#addSpecialityModal">
                                    <i class="fas fa-plus-circle mr-2"></i> Añadir especialidad
                                </button>
                            </div>
                            
                            <h3 class="text-xl font-semibold text-gray-800 mb-2 mt-8">Habilidades</h3>
                            <p class="text-gray-600 mb-6">Añade habilidades específicas que complementen tus especialidades.</p>
                            
                            <div>
                                <div class="flex flex-wrap gap-2 mb-4">
                                    @forelse($skills ?? [] as $skill)
                                        <div class="bg-gray-500 text-white text-sm px-3 py-1.5 rounded-full flex items-center">
                                            {{ $skill->name }}
                                            <button class="ml-2 text-white hover:text-white/80 transition-colors duration-150" data-skill-id="{{ $skill->id }}">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </div>
                                    @empty
                                        <p class="text-gray-500">No has añadido habilidades todavía.</p>
                                    @endforelse
                                </div>
                                <button class="inline-flex items-center px-3 py-1.5 border border-blue-600 text-blue-600 rounded-md hover:bg-blue-50 transition-colors duration-150" data-bs-toggle="modal" data-bs-target="#addSkillModal">
                                    <i class="fas fa-plus-circle mr-2"></i> Añadir habilidad
                                </button>
                            </div>
                        </div>
                        
                        <!-- Pestaña de Disponibilidad -->
                        <div class="tab-pane fade" id="availability" role="tabpanel">
                            <h3 class="text-xl font-semibold text-gray-800 mb-2">Mi Disponibilidad</h3>
                            <p class="text-gray-600 mb-6">Configura tus horarios disponibles para sesiones de mentoría.</p>
                            
                            <div class="overflow-x-auto mb-6">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Día</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Disponible</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Horario</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @php
                                            $days = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo'];
                                        @endphp
                                        
                                        @foreach($days as $index => $day)
                                            <tr class="{{ $index % 2 === 0 ? 'bg-white' : 'bg-gray-50' }}">
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $day }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    <label class="inline-flex relative items-center cursor-pointer">
                                                        <input type="checkbox" id="day{{ $index }}" 
                                                            class="sr-only peer"
                                                            {{ isset($availability[$index]) && $availability[$index]['available'] ? 'checked' : '' }}>
                                                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                                                    </label>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    <div class="flex items-center space-x-2">
                                                        <select class="block w-24 py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 text-sm" 
                                                               id="startTime{{ $index }}" 
                                                               {{ isset($availability[$index]) && $availability[$index]['available'] ? '' : 'disabled' }}>
                                                            @for($hour = 8; $hour <= 20; $hour++)
                                                                <option value="{{ sprintf('%02d:00', $hour) }}" 
                                                                       {{ isset($availability[$index]) && $availability[$index]['start_time'] == sprintf('%02d:00', $hour) ? 'selected' : '' }}>
                                                                    {{ sprintf('%02d:00', $hour) }}
                                                                </option>
                                                                <option value="{{ sprintf('%02d:30', $hour) }}" 
                                                                       {{ isset($availability[$index]) && $availability[$index]['start_time'] == sprintf('%02d:30', $hour) ? 'selected' : '' }}>
                                                                    {{ sprintf('%02d:30', $hour) }}
                                                                </option>
                                                            @endfor
                                                        </select>
                                                        <span class="text-gray-500">a</span>
                                                        <select class="block w-24 py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 text-sm" 
                                                               id="endTime{{ $index }}" 
                                                               {{ isset($availability[$index]) && $availability[$index]['available'] ? '' : 'disabled' }}>
                                                            @for($hour = 8; $hour <= 21; $hour++)
                                                                <option value="{{ sprintf('%02d:00', $hour) }}" 
                                                                       {{ isset($availability[$index]) && $availability[$index]['end_time'] == sprintf('%02d:00', $hour) ? 'selected' : '' }}>
                                                                    {{ sprintf('%02d:00', $hour) }}
                                                                </option>
                                                                <option value="{{ sprintf('%02d:30', $hour) }}" 
                                                                       {{ isset($availability[$index]) && $availability[$index]['end_time'] == sprintf('%02d:30', $hour) ? 'selected' : '' }}>
                                                                    {{ sprintf('%02d:30', $hour) }}
                                                                </option>
                                                            @endfor
                                                        </select>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            
                            <button class="mt-4 px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors duration-150 inline-flex items-center" id="saveAvailabilityBtn">
                                <i class="fas fa-save mr-2"></i> Guardar disponibilidad
                            </button>
                        </div>
                        
                        <!-- Pestaña de Configuración -->
                        <div class="tab-pane fade" id="settings" role="tabpanel">
                            <h3 class="text-xl font-semibold text-gray-800 mb-4">Configuración de la Cuenta</h3>
                            
                            <div class="mb-8">
                                <h4 class="text-lg font-semibold text-blue-600 mb-4">Cambiar Contraseña</h4>
                                <form id="passwordForm" class="space-y-4">
                                    <div>
                                        <label for="currentPassword" class="block text-sm font-medium text-gray-700 mb-1">Contraseña actual</label>
                                        <input type="password" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" id="currentPassword" required>
                                    </div>
                                    <div>
                                        <label for="newPassword" class="block text-sm font-medium text-gray-700 mb-1">Nueva contraseña</label>
                                        <input type="password" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" id="newPassword" required>
                                    </div>
                                    <div>
                                        <label for="confirmPassword" class="block text-sm font-medium text-gray-700 mb-1">Confirmar contraseña</label>
                                        <input type="password" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" id="confirmPassword" required>
                                    </div>
                                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors duration-150 inline-flex items-center">
                                        <i class="fas fa-key mr-2"></i> Cambiar contraseña
                                    </button>
                                </form>
                            </div>
                            
                            <div class="mb-8">
                                <h4 class="text-lg font-semibold text-blue-600 mb-4">Preferencias de Notificación</h4>
                                <div class="space-y-3">
                                    <div class="flex items-center">
                                        <input class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded" type="checkbox" id="emailNotifications" 
                                            {{ isset($preferences) && $preferences->email_notifications ? 'checked' : '' }}>
                                        <label class="ml-2 block text-sm text-gray-700" for="emailNotifications">
                                            Recibir notificaciones por correo electrónico
                                        </label>
                                    </div>
                                    <div class="flex items-center">
                                        <input class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded" type="checkbox" id="sessionReminders" 
                                            {{ isset($preferences) && $preferences->session_reminders ? 'checked' : '' }}>
                                        <label class="ml-2 block text-sm text-gray-700" for="sessionReminders">
                                            Recordatorios de sesiones
                                        </label>
                                    </div>
                                    <div class="flex items-center">
                                        <input class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded" type="checkbox" id="marketingEmails" 
                                            {{ isset($preferences) && $preferences->marketing_emails ? 'checked' : '' }}>
                                        <label class="ml-2 block text-sm text-gray-700" for="marketingEmails">
                                            Recibir correos de marketing y novedades
                                        </label>
                                    </div>
                                </div>
                                <button class="mt-4 px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors duration-150 inline-flex items-center" id="savePreferencesBtn">
                                    <i class="fas fa-save mr-2"></i> Guardar preferencias
                                </button>
                            </div>
                            
                            <div class="mt-10 pt-6 border-t border-gray-200">
                                <h4 class="text-lg font-semibold text-red-600 mb-2">Zona de Peligro</h4>
                                <p class="text-gray-600 mb-4">Estas acciones son permanentes y no se pueden deshacer.</p>
                                <button class="px-4 py-2 border border-red-600 text-red-600 rounded-md hover:bg-red-50 transition-colors duration-150 inline-flex items-center" data-bs-toggle="modal" data-bs-target="#deactivateAccountModal">
                                    <i class="fas fa-user-slash mr-2"></i> Desactivar cuenta
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal para cambiar foto de perfil -->
<div class="modal fade" id="changePhotoModal" tabindex="-1" aria-labelledby="changePhotoModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content rounded-lg shadow-xl">
            <div class="modal-header border-b border-gray-200 py-3">
                <h5 class="modal-title text-lg font-medium text-gray-800" id="changePhotoModalLabel">Cambiar Foto de Perfil</h5>
                <button type="button" class="text-gray-400 hover:text-gray-500" data-bs-dismiss="modal" aria-label="Close">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body p-6">
                <form id="photoForm" enctype="multipart/form-data">
                    <div class="mb-5 text-center">
                        <img id="photoPreview" src="{{ auth()->user()->photo_url ?? 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->name) . '&color=7F9CF5&background=EBF4FF' }}" 
                             class="rounded-full object-cover border-4 border-white shadow mx-auto mb-4" width="150" height="150">
                    </div>
                    <div class="mb-4">
                        <label for="photoFile" class="block text-sm font-medium text-gray-700 mb-1">Seleccionar nueva foto</label>
                        <input class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" type="file" id="photoFile" accept="image/*" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer border-t border-gray-200 py-3 px-6 flex justify-end space-x-2">
                <button type="button" class="px-4 py-2 border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50 transition-colors duration-150" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors duration-150 inline-flex items-center" id="savePhotoBtn"><i class="fas fa-save mr-2"></i>Guardar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal para editar información básica -->
<div class="modal fade" id="editBasicInfoModal" tabindex="-1" aria-labelledby="editBasicInfoModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content rounded-lg shadow-xl">
            <div class="modal-header border-b border-gray-200 py-3">
                <h5 class="modal-title text-lg font-medium text-gray-800" id="editBasicInfoModalLabel">Editar Información Básica</h5>
                <button type="button" class="text-gray-400 hover:text-gray-500" data-bs-dismiss="modal" aria-label="Close">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body p-6">
                <form id="basicInfoForm" class="space-y-4">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nombre completo</label>
                        <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" id="name" value="{{ auth()->user()->name }}" required>
                    </div>
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Correo electrónico</label>
                        <input type="email" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" id="email" value="{{ auth()->user()->email }}" required>
                    </div>
                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Teléfono</label>
                        <input type="tel" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" id="phone" value="{{ auth()->user()->phone ?? '' }}">
                    </div>
                    <div>
                        <label for="location" class="block text-sm font-medium text-gray-700 mb-1">Ubicación</label>
                        <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" id="location" value="{{ auth()->user()->location ?? '' }}">
                    </div>
                </form>
            </div>
            <div class="modal-footer border-t border-gray-200 py-3 px-6 flex justify-end space-x-2">
                <button type="button" class="px-4 py-2 border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50 transition-colors duration-150" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors duration-150 inline-flex items-center" id="saveBasicInfoBtn"><i class="fas fa-save mr-2"></i>Guardar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal para editar biografía -->
<div class="modal fade" id="editBioModal" tabindex="-1" aria-labelledby="editBioModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content rounded-lg shadow-xl">
            <div class="modal-header border-b border-gray-200 py-3">
                <h5 class="modal-title text-lg font-medium text-gray-800" id="editBioModalLabel">Editar Biografía Profesional</h5>
                <button type="button" class="text-gray-400 hover:text-gray-500" data-bs-dismiss="modal" aria-label="Close">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body p-6">
                <form id="bioForm">
                    <div class="mb-4">
                        <label for="bio" class="block text-sm font-medium text-gray-700 mb-1">Biografía (máx. 500 caracteres)</label>
                        <textarea class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" id="bio" rows="5" maxlength="500">{{ $mentorProfile->bio ?? '' }}</textarea>
                        <p class="mt-1 text-sm text-gray-500">Describe tu experiencia profesional, enfoque de mentoría y áreas de especialización.</p>
                    </div>
                </form>
            </div>
            <div class="modal-footer border-t border-gray-200 py-3 px-6 flex justify-end space-x-2">
                <button type="button" class="px-4 py-2 border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50 transition-colors duration-150" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors duration-150 inline-flex items-center" id="saveBioBtn"><i class="fas fa-save mr-2"></i>Guardar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal para añadir experiencia -->
<div class="modal fade" id="addExperienceModal" tabindex="-1" aria-labelledby="addExperienceModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content rounded-lg shadow-xl">
            <div class="modal-header border-b border-gray-200 py-3">
                <h5 class="modal-title text-lg font-medium text-gray-800" id="addExperienceModalLabel">Añadir Experiencia Profesional</h5>
                <button type="button" class="text-gray-400 hover:text-gray-500" data-bs-dismiss="modal" aria-label="Close">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body p-6">
                <form id="experienceForm" class="space-y-4">
                    <div>
                        <label for="position" class="block text-sm font-medium text-gray-700 mb-1">Cargo</label>
                        <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" id="position" required>
                    </div>
                    <div>
                        <label for="company" class="block text-sm font-medium text-gray-700 mb-1">Empresa</label>
                        <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" id="company" required>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="startDate" class="block text-sm font-medium text-gray-700 mb-1">Fecha de inicio</label>
                            <input type="month" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" id="startDate" required>
                        </div>
                        <div id="endDateCol">
                            <label for="endDate" class="block text-sm font-medium text-gray-700 mb-1">Fecha de fin</label>
                            <input type="month" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" id="endDate">
                        </div>
                    </div>
                    <div class="flex items-center">
                        <input type="checkbox" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded" id="currentJob">
                        <label class="ml-2 block text-sm text-gray-700" for="currentJob">Trabajo actual</label>
                    </div>
                    <div>
                        <label for="expDescription" class="block text-sm font-medium text-gray-700 mb-1">Descripción</label>
                        <textarea class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" id="expDescription" rows="3"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer border-t border-gray-200 py-3 px-6 flex justify-end space-x-2">
                <button type="button" class="px-4 py-2 border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50 transition-colors duration-150" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors duration-150 inline-flex items-center" id="saveExperienceBtn"><i class="fas fa-save mr-2"></i>Guardar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal para añadir educación -->
<div class="modal fade" id="addEducationModal" tabindex="-1" aria-labelledby="addEducationModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content rounded-lg shadow-xl">
            <div class="modal-header border-b border-gray-200 py-3">
                <h5 class="modal-title text-lg font-medium text-gray-800" id="addEducationModalLabel">Añadir Educación</h5>
                <button type="button" class="text-gray-400 hover:text-gray-500" data-bs-dismiss="modal" aria-label="Close">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body p-6">
                <form id="educationForm" class="space-y-4">
                    <div>
                        <label for="degree" class="block text-sm font-medium text-gray-700 mb-1">Título o Grado</label>
                        <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" id="degree" required>
                    </div>
                    <div>
                        <label for="institution" class="block text-sm font-medium text-gray-700 mb-1">Institución</label>
                        <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" id="institution" required>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="startYear" class="block text-sm font-medium text-gray-700 mb-1">Año de inicio</label>
                            <input type="number" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" id="startYear" min="1950" max="2030" required>
                        </div>
                        <div id="endYearCol">
                            <label for="endYear" class="block text-sm font-medium text-gray-700 mb-1">Año de fin</label>
                            <input type="number" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" id="endYear" min="1950" max="2030">
                        </div>
                    </div>
                    <div class="flex items-center">
                        <input type="checkbox" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded" id="currentEducation">
                        <label class="ml-2 block text-sm text-gray-700" for="currentEducation">En curso</label>
                    </div>
                    <div>
                        <label for="eduDescription" class="block text-sm font-medium text-gray-700 mb-1">Descripción</label>
                        <textarea class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" id="eduDescription" rows="3"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer border-t border-gray-200 py-3 px-6 flex justify-end space-x-2">
                <button type="button" class="px-4 py-2 border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50 transition-colors duration-150" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors duration-150 inline-flex items-center" id="saveEducationBtn"><i class="fas fa-save mr-2"></i>Guardar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal para añadir especialidad -->
<div class="modal fade" id="addSpecialityModal" tabindex="-1" aria-labelledby="addSpecialityModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content rounded-lg shadow-xl">
            <div class="modal-header border-b border-gray-200 py-3">
                <h5 class="modal-title text-lg font-medium text-gray-800" id="addSpecialityModalLabel">Añadir Especialidad</h5>
                <button type="button" class="text-gray-400 hover:text-gray-500" data-bs-dismiss="modal" aria-label="Close">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body p-6">
                <form id="specialityForm">
                    <div>
                        <label for="speciality" class="block text-sm font-medium text-gray-700 mb-1">Especialidad</label>
                        <select class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" id="speciality" required>
                            <option value="" selected disabled>Seleccionar especialidad...</option>
                            @foreach($availableSpecialities ?? [] as $speciality)
                                <option value="{{ $speciality->id }}">{{ $speciality->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer border-t border-gray-200 py-3 px-6 flex justify-end space-x-2">
                <button type="button" class="px-4 py-2 border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50 transition-colors duration-150" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors duration-150 inline-flex items-center" id="saveSpecialityBtn"><i class="fas fa-plus mr-2"></i>Añadir</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal para añadir habilidad -->
<div class="modal fade" id="addSkillModal" tabindex="-1" aria-labelledby="addSkillModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content rounded-lg shadow-xl">
            <div class="modal-header border-b border-gray-200 py-3">
                <h5 class="modal-title text-lg font-medium text-gray-800" id="addSkillModalLabel">Añadir Habilidad</h5>
                <button type="button" class="text-gray-400 hover:text-gray-500" data-bs-dismiss="modal" aria-label="Close">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body p-6">
                <form id="skillForm">
                    <div>
                        <label for="skill" class="block text-sm font-medium text-gray-700 mb-1">Habilidad</label>
                        <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" id="skill" required>
                        <p class="mt-1 text-sm text-gray-500">Ingresa una habilidad específica (ej. JavaScript, Diseño UX, Análisis de datos)</p>
                    </div>
                </form>
            </div>
            <div class="modal-footer border-t border-gray-200 py-3 px-6 flex justify-end space-x-2">
                <button type="button" class="px-4 py-2 border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50 transition-colors duration-150" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors duration-150 inline-flex items-center" id="saveSkillBtn"><i class="fas fa-plus mr-2"></i>Añadir</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal para desactivar cuenta -->
<div class="modal fade" id="deactivateAccountModal" tabindex="-1" aria-labelledby="deactivateAccountModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content rounded-lg shadow-xl">
            <div class="modal-header border-b border-gray-200 py-3">
                <h5 class="modal-title text-lg font-medium text-red-600" id="deactivateAccountModalLabel">Desactivar Cuenta</h5>
                <button type="button" class="text-gray-400 hover:text-gray-500" data-bs-dismiss="modal" aria-label="Close">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body p-6">
                <div class="p-4 mb-4 bg-yellow-50 border-l-4 border-yellow-400 text-yellow-700">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-exclamation-triangle"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium"><strong>Advertencia:</strong> Esta acción desactivará tu cuenta de mentor. Tus estudiantes no podrán acceder a tus servicios y perderás acceso a tus datos de mentor.</p>
                        </div>
                    </div>
                </div>
                <p class="mb-3 text-gray-700">Para confirmar, escribe "DESACTIVAR" en el campo de abajo:</p>
                <div>
                    <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-red-500 focus:border-red-500" id="deactivateConfirm" placeholder="DESACTIVAR">
                </div>
            </div>
            <div class="modal-footer border-t border-gray-200 py-3 px-6 flex justify-end space-x-2">
                <button type="button" class="px-4 py-2 border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50 transition-colors duration-150" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition-colors duration-150 inline-flex items-center" id="deactivateAccountBtn" disabled>
                    <i class="fas fa-user-slash mr-2"></i> Desactivar Cuenta
                </button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Previsualización de foto de perfil
        const photoFile = document.getElementById('photoFile');
        const photoPreview = document.getElementById('photoPreview');
        
        if (photoFile) {
            photoFile.addEventListener('change', function() {
                const file = this.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        photoPreview.src = e.target.result;
                    };
                    reader.readAsDataURL(file);
                }
            });
        }
        
        // Manejar checkbox de trabajo actual
        const currentJobCheckbox = document.getElementById('currentJob');
        const endDateCol = document.getElementById('endDateCol');
        const endDateInput = document.getElementById('endDate');
        
        if (currentJobCheckbox && endDateCol && endDateInput) {
            currentJobCheckbox.addEventListener('change', function() {
                if (this.checked) {
                    endDateCol.style.opacity = '0.5';
                    endDateInput.disabled = true;
                    endDateInput.value = '';
                } else {
                    endDateCol.style.opacity = '1';
                    endDateInput.disabled = false;
                }
            });
        }
        
        // Manejar checkbox de educación en curso
        const currentEducationCheckbox = document.getElementById('currentEducation');
        const endYearCol = document.getElementById('endYearCol');
        const endYearInput = document.getElementById('endYear');
        
        if (currentEducationCheckbox && endYearCol && endYearInput) {
            currentEducationCheckbox.addEventListener('change', function() {
                if (this.checked) {
                    endYearCol.style.opacity = '0.5';
                    endYearInput.disabled = true;
                    endYearInput.value = '';
                } else {
                    endYearCol.style.opacity = '1';
                    endYearInput.disabled = false;
                }
            });
        }
        
        // Manejar confirmación de desactivación de cuenta
        const deactivateConfirmInput = document.getElementById('deactivateConfirm');
        const deactivateAccountBtn = document.getElementById('deactivateAccountBtn');
        
        if (deactivateConfirmInput && deactivateAccountBtn) {
            deactivateConfirmInput.addEventListener('input', function() {
                deactivateAccountBtn.disabled = this.value !== 'DESACTIVAR';
            });
        }
        
        // Manejar disponibilidad
        const dayCheckboxes = document.querySelectorAll('input[id^="day"]');
        
        dayCheckboxes.forEach((checkbox, index) => {
            const startTimeSelect = document.getElementById(`startTime${index}`);
            const endTimeSelect = document.getElementById(`endTime${index}`);
            
            checkbox.addEventListener('change', function() {
                startTimeSelect.disabled = !this.checked;
                endTimeSelect.disabled = !this.checked;
            });
        });
        
        // Manejar envío de formularios
        // Estos son ejemplos y deberían adaptarse a la lógica real de tu aplicación
        
        // Guardar foto de perfil
        const savePhotoBtn = document.getElementById('savePhotoBtn');
        if (savePhotoBtn) {
            savePhotoBtn.addEventListener('click', function() {
                const photoForm = document.getElementById('photoForm');
                if (photoForm.checkValidity()) {
                    // Aquí iría la lógica para enviar la foto al servidor
                    // En una implementación real, esto sería un FormData con AJAX
                    
                    // Simulación de éxito
                    alert('Foto de perfil actualizada correctamente');
                    const modal = bootstrap.Modal.getInstance(document.getElementById('changePhotoModal'));
                    modal.hide();
                } else {
                    photoForm.reportValidity();
                }
            });
        }
        
        // Guardar información básica
        const saveBasicInfoBtn = document.getElementById('saveBasicInfoBtn');
        if (saveBasicInfoBtn) {
            saveBasicInfoBtn.addEventListener('click', function() {
                const basicInfoForm = document.getElementById('basicInfoForm');
                if (basicInfoForm.checkValidity()) {
                    // Aquí iría la lógica para enviar los datos al servidor
                    
                    // Simulación de éxito
                    alert('Información básica actualizada correctamente');
                    const modal = bootstrap.Modal.getInstance(document.getElementById('editBasicInfoModal'));
                    modal.hide();
                } else {
                    basicInfoForm.reportValidity();
                }
            });
        }
        
        // Guardar disponibilidad
        const saveAvailabilityBtn = document.getElementById('saveAvailabilityBtn');
        if (saveAvailabilityBtn) {
            saveAvailabilityBtn.addEventListener('click', function() {
                // Recopilar datos de disponibilidad
                const availability = [];
                
                dayCheckboxes.forEach((checkbox, index) => {
                    if (checkbox.checked) {
                        const startTime = document.getElementById(`startTime${index}`).value;
                        const endTime = document.getElementById(`endTime${index}`).value;
                        
                        availability.push({
                            day_index: index,
                            available: true,
                            start_time: startTime,
                            end_time: endTime
                        });
                    }
                });
                
                // Aquí iría la lógica para enviar los datos al servidor
                console.log('Disponibilidad a guardar:', availability);
                
                // Simulación de éxito
                alert('Disponibilidad actualizada correctamente');
            });
        }
    });
</script>
@endpush
