@extends('mentor.layouts.app')

@section('title', 'Mi Perfil - MentorHub')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    .profile-photo-upload {
        position: relative;
        display: inline-block;
        cursor: pointer;
    }
    .profile-photo-upload img {
        transition: opacity 0.3s;
    }
    .profile-photo-upload:hover img {
        opacity: 0.8;
    }
    .profile-photo-upload .overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.5);
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        transition: opacity 0.3s;
        border-radius: 50%;
    }
    .profile-photo-upload:hover .overlay {
        opacity: 1;
    }
    .select2-container {
        width: 100% !important;
    }
</style>
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
                        <form id="photoUploadForm" enctype="multipart/form-data">
                            @csrf
                            <input type="file" id="photoInput" name="photo" accept="image/*" class="hidden" />
                            <label for="photoInput" class="profile-photo-upload cursor-pointer">
                                <img id="profilePhoto" class="h-32 w-32 rounded-full object-cover border-4 border-white shadow" 
                                    src="{{ auth()->user()->photo_url ?? 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->name) . '&color=7F9CF5&background=EBF4FF' }}"
                                    alt="{{ auth()->user()->name }}">
                                <div class="overlay">
                                    <span class="text-white">
                                        <i class="fas fa-camera text-2xl"></i>
                                    </span>
                                </div>
                            </label>
                        </form>
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
                                <a href="#bio" class="inline-flex items-center px-3 py-1.5 border border-blue-600 text-blue-600 rounded-md hover:bg-blue-50 transition-colors duration-150" onclick="event.preventDefault(); document.querySelector('a[href=\'#bio\']').click();">
                                    <i class="fas fa-edit mr-2"></i> Editar biografía
                                </a>
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
                                <a href="#bio" class="inline-flex items-center px-3 py-1.5 border border-blue-600 text-blue-600 rounded-md hover:bg-blue-50 transition-colors duration-150" onclick="event.preventDefault(); document.querySelector('a[href=\'#bio\']').click();">
                                    <i class="fas fa-plus mr-2"></i> Añadir experiencia
                                </a>
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
                                <a href="#bio" class="inline-flex items-center px-3 py-1.5 border border-blue-600 text-blue-600 rounded-md hover:bg-blue-50 transition-colors duration-150" onclick="event.preventDefault(); document.querySelector('a[href=\'#bio\']').click();">
                                    <i class="fas fa-plus mr-2"></i> Añadir educación
                                </a>
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
                                <a href="#specialities" class="inline-flex items-center px-3 py-1.5 border border-blue-600 text-blue-600 rounded-md hover:bg-blue-50 transition-colors duration-150" onclick="event.preventDefault(); document.querySelector('a[href=\'#specialities\']').click();">
                                    <i class="fas fa-plus mr-2"></i> Añadir especialidad
                                </a>
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
                        <div class="tab-pane fade show active" id="availability" role="tabpanel">
                            <h3 class="text-xl font-semibold text-gray-800 mb-4">Mi Horario de Disponibilidad</h3>
                            <p class="text-gray-600 mb-6">Configura tus horarios disponibles para sesiones de mentoría. Marca los días en los que estás disponible.</p>
                            
                            <form id="availabilityForm" method="POST" action="{{ route('mentor.availability.update') }}">
                                @csrf
                                <div class="overflow-x-auto mb-6 border border-gray-200 rounded-lg">
                                    <table class="min-w-full divide-y divide-gray-200">
                                        <thead class="bg-blue-50">
                                            <tr>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-blue-700 uppercase tracking-wider w-1/4">
                                                    Día
                                                </th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-blue-700 uppercase tracking-wider w-1/4">
                                                    Disponible
                                                </th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-blue-700 uppercase tracking-wider w-1/2">
                                                    Horario
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200">
                                            @php
                                                $days = [
                                                    0 => 'Domingo',
                                                    1 => 'Lunes',
                                                    2 => 'Martes',
                                                    3 => 'Miércoles',
                                                    4 => 'Jueves',
                                                    5 => 'Viernes',
                                                    6 => 'Sábado'
                                                ];
                                            @endphp

                                            @foreach($days as $dayIndex => $dayName)
                                                @php
                                                    $dayData = $availability[$dayIndex] ?? [
                                                        'available' => false,
                                                        'start_time' => '09:00',
                                                        'end_time' => '17:00',
                                                        'day_name' => $dayName
                                                    ];
                                                    $isAvailable = $dayData['available'] ?? false;
                                                @endphp
                                                <tr class="{{ $isAvailable ? 'bg-green-50' : 'bg-white' }} hover:bg-gray-50" data-day="{{ $dayIndex }}">
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <div class="flex items-center">
                                                            <div class="flex-shrink-0 h-10 w-10 flex items-center justify-center rounded-full bg-blue-100 text-blue-600">
                                                                <i class="fas fa-calendar-day"></i>
                                                            </div>
                                                            <div class="ml-4">
                                                                <div class="text-sm font-medium text-gray-900">{{ $dayName }}</div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <label class="inline-flex items-center">
                                                            <input type="checkbox" 
                                                                   name="availability[{{ $dayIndex }}][available]" 
                                                                   value="1" 
                                                                   class="toggle-availability rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" 
                                                                   data-day="{{ $dayIndex }}"
                                                                   {{ $isAvailable ? 'checked' : '' }}>
                                                            <span class="ml-2 text-sm text-gray-700">
                                                                {{ $isAvailable ? 'Disponible' : 'No disponible' }}
                                                            </span>
                                                        </label>
                                                    </td>
                                                    <td class="px-6 py-4">
                                                        <div class="time-slots" data-day="{{ $dayIndex }}">
                                                            <div class="flex items-center space-x-2">
                                                                <input type="time" 
                                                                       name="availability[{{ $dayIndex }}][start_time]" 
                                                                       class="start-time w-32 px-2 py-1 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                                                       value="{{ $dayData['start_time'] ?? '09:00' }}"
                                                                       {{ !$isAvailable ? 'disabled' : '' }}>
                                                                <span class="text-gray-500">a</span>
                                                                <input type="time" 
                                                                       name="availability[{{ $dayIndex }}][end_time]" 
                                                                       class="end-time w-32 px-2 py-1 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                                                       value="{{ $dayData['end_time'] ?? '17:00' }}"
                                                                       {{ !$isAvailable ? 'disabled' : '' }}>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                
                                <div class="flex justify-between items-center mt-6 pt-4 border-t border-gray-200">
                                    <div class="text-sm text-gray-500">
                                        <i class="fas fa-info-circle mr-1 text-blue-500"></i> Los cambios se guardarán automáticamente
                                    </div>
                                    <div class="flex space-x-3">
                                        <button type="button" id="copyWeekSchedule" class="px-3 py-1.5 border border-gray-300 rounded-md text-gray-700 bg-white hover:bg-gray-50 text-sm">
                                            <i class="far fa-copy mr-1"></i> Copiar horario a toda la semana
                                        </button>
                                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors duration-150 inline-flex items-center">
                                            <i class="fas fa-save mr-2"></i> Guardar disponibilidad
                                        </button>
                                    </div>
                                </div>
                            </form>
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
                            <option value="">Selecciona una especialidad</option>
                            @foreach($allSpecialities ?? [] as $speciality)
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
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    // Toast notification function
    function showToast(type, message) {
        const toast = document.createElement('div');
        toast.className = `fixed top-4 right-4 px-6 py-3 rounded-md text-white shadow-lg z-50 transition-all duration-300 transform translate-x-0 opacity-100 ${
            type === 'success' ? 'bg-green-500' : 'bg-red-500'
        }`;
        
        toast.innerHTML = `
            <div class="flex items-center">
                <i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'} mr-2"></i>
                <span>${message}</span>
            </div>
        `;
        
        document.body.appendChild(toast);
        
        // Remove toast after 5 seconds
        setTimeout(() => {
            toast.style.transform = 'translateX(120%)';
            toast.style.opacity = '0';
            setTimeout(() => {
                if (document.body.contains(toast)) {
                    document.body.removeChild(toast);
                }
            }, 300);
        }, 5000);
    }
    
    // Toggle time inputs based on availability checkbox
    function toggleTimeInputs(checkbox) {
        const dayIndex = checkbox.id.replace('day', '');
        const startTime = document.getElementById(`startTime${dayIndex}`);
        const endTime = document.getElementById(`endTime${dayIndex}`);
        
        if (startTime && endTime) {
            startTime.disabled = !checkbox.checked;
            endTime.disabled = !checkbox.checked;
        }
    }
    
    // Initialize time pickers
    function initializeTimePickers() {
        // Initialize time inputs
        const timeInputs = document.querySelectorAll('input[type="time"]');
        timeInputs.forEach(input => {
            // Set default time if empty
            if (!input.value) {
                input.value = input.id.includes('start') ? '09:00' : '17:00';
            }
        });
        
        // Toggle time inputs based on initial state
        document.querySelectorAll('input[type="checkbox"][id^="day"]').forEach(checkbox => {
            toggleTimeInputs(checkbox);
            
            // Add event listener for changes
            checkbox.addEventListener('change', function() {
                toggleTimeInputs(this);
            });
        });
    }

    document.addEventListener('DOMContentLoaded', function() {
        // Smooth scrolling for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            const targetId = this.getAttribute('href');
            if (targetId === '#') return;
            
            e.preventDefault();
            
            // Find the target element
            const targetElement = document.querySelector(targetId);
            if (!targetElement) return;
            
            // If it's a tab, activate it
            const tabTrigger = document.querySelector(`[href="${targetId}"][data-bs-toggle="tab"]`);
            if (tabTrigger) {
                new bootstrap.Tab(tabTrigger).show();
            }
            
            // Scroll to the element
            targetElement.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        });
    });

    // Initialize Select2 for specialities
    $('select[multiple]').select2({
        placeholder: 'Selecciona tus especialidades',
        allowClear: true,
            width: '100%',
            closeOnSelect: false
        });
        
        // Initialize Select2 for timezone
        $('#timezone').select2({
            width: '100%',
            placeholder: 'Selecciona tu zona horaria'
        });
        
        // Initialize time pickers and availability toggles
        initializeTimePickers();
        
        // Handle availability form submission
        const availabilityForm = document.getElementById('availabilityForm');
        if (availabilityForm) {
            availabilityForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const formData = new FormData(availabilityForm);
                const submitBtn = availabilityForm.querySelector('button[type="submit"]');
                const originalBtnText = submitBtn.innerHTML;
                
                // Show loading state
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Guardando...';
                
                // Prepare availability data
                const availabilityData = [];
                const days = [
                    { id: 0, name: 'sunday', label: 'Domingo' },
                    { id: 1, name: 'monday', label: 'Lunes' },
                    { id: 2, name: 'tuesday', label: 'Martes' },
                    { id: 3, name: 'wednesday', label: 'Miércoles' },
                    { id: 4, name: 'thursday', label: 'Jueves' },
                    { id: 5, name: 'friday', label: 'Viernes' },
                    { id: 6, name: 'saturday', label: 'Sábado' }
                ];
                
                days.forEach(day => {
                    const checkbox = document.getElementById(`day${day.id}`);
                    const startTime = document.getElementById(`startTime${day.id}`);
                    const endTime = document.getElementById(`endTime${day.id}`);
                    
                    availabilityData.push({
                        day_of_week: day.id, // Enviar el número del día (0-6)
                        is_available: checkbox.checked,
                        start_time: startTime ? startTime.value : '09:00',
                        end_time: endTime ? endTime.value : '17:00',
                        recurring: true
                    });
                });
                
                // Send data to server
                fetch('{{ route("mentor.availability.update") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ availability: availabilityData })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showToast('success', '¡Disponibilidad actualizada correctamente!');
                    } else {
                        throw new Error(data.message || 'Error al actualizar la disponibilidad');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showToast('error', error.message || 'Error al actualizar la disponibilidad');
                })
                .finally(() => {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalBtnText;
                });
            });
        }

        // Handle basic info form submission
        const basicInfoForm = document.getElementById('basicInfoForm');
        if (basicInfoForm) {
            basicInfoForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const formData = new FormData(basicInfoForm);
                const submitBtn = basicInfoForm.querySelector('button[type="submit"]');
                const originalBtnText = submitBtn.innerHTML;
                
                // Show loading state
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Guardando...';
                
                fetch(basicInfoForm.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Show success message
                        showToast('success', '¡Información actualizada correctamente!');
                        
                        // Close the modal
                        const modal = bootstrap.Modal.getInstance(document.getElementById('editBasicInfoModal'));
                        if (modal) modal.hide();
                        
                        // Update the page to reflect changes
                        setTimeout(() => window.location.reload(), 1000);
                    } else {
                        throw new Error(data.message || 'Error al actualizar la información');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showToast('error', error.message || 'Error al actualizar la información');
                })
                .finally(() => {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalBtnText;
                });
            });
        }
        
        // Handle profile photo upload
        const photoInput = document.getElementById('photoInput');
        const photoForm = document.getElementById('photoUploadForm');
        
        if (photoInput && photoForm) {
            photoInput.addEventListener('change', function() {
                const file = this.files[0];
                if (!file) return;
                
                // Show loading state
                const submitBtn = photoForm.querySelector('button[type="submit"]');
                if (submitBtn) submitBtn.disabled = true;
                
                const formData = new FormData(photoForm);
                
                fetch('{{ route("mentor.profile.photo.update") }}', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Update the profile photo
                        document.getElementById('profilePhoto').src = data.photo_url;
                        
                        // Show success message
                        showToast('success', '¡Foto de perfil actualizada correctamente!');
                    } else {
                        throw new Error(data.message || 'Error al actualizar la foto de perfil');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showToast('error', error.message || 'Error al actualizar la foto de perfil');
                })
                .finally(() => {
                    if (submitBtn) submitBtn.disabled = false;
                });
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
        
        // Toggle time inputs when day availability checkbox is clicked
        document.addEventListener('click', function(e) {
            if (e.target && e.target.classList.contains('day-checkbox')) {
                const dayRow = e.target.closest('tr');
                const timeInputs = dayRow.querySelector('.time-inputs');
                const timeInputFields = timeInputs.querySelectorAll('.time-input');
                
                if (e.target.checked) {
                    timeInputs.style.display = 'flex';
                    timeInputFields.forEach(input => input.disabled = false);
                } else {
                    timeInputs.style.display = 'none';
                    timeInputFields.forEach(input => input.disabled = true);
                }
            }
        });
        
        // Handle availability form submission
        const availabilityForm = document.getElementById('availabilityForm');
        if (availabilityForm) {
            availabilityForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const formData = new FormData(this);
                const availabilityData = {
                    _token: formData.get('_token'),
                    availability: {}
                };

                // Process form data into the expected format
                formData.forEach((value, key) => {
                    if (key.startsWith('availability[')) {
                        const dayMatch = key.match(/availability\[(\d+)\]\[(\w+)\]/);
                        if (dayMatch) {
                            const dayIndex = dayMatch[1];
                            const field = dayMatch[2];
                            
                            if (!availabilityData.availability[dayIndex]) {
                                availabilityData.availability[dayIndex] = {};
                            }
                            
                            availabilityData.availability[dayIndex][field] = value;
                        }
                    }
                });

                // Show loading state
                const submitButton = availabilityForm.querySelector('button[type="submit"]');
                const originalButtonText = submitButton.innerHTML;
                submitButton.disabled = true;
                submitButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Guardando...';

                // Send data via AJAX
                fetch('{{ route("mentor.availability.update") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(availabilityData)
                })
                .then(response => response.json())
                .then(data => {
                    showToast('success', 'Disponibilidad actualizada correctamente');
                })
                .catch(error => {
                    console.error('Error:', error);
                    showToast('error', 'Error al actualizar la disponibilidad');
                })
                .finally(() => {
                    // Restore button state
                    submitButton.disabled = false;
                    submitButton.innerHTML = originalButtonText;
                });
            });
        }
        
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

    // Funcionalidad del horario de disponibilidad
    document.addEventListener('DOMContentLoaded', function() {
        // Toggle de disponibilidad
        document.querySelectorAll('.toggle-availability').forEach(button => {
            button.addEventListener('change', function() {
                const dayIndex = this.dataset.day;
                const row = this.closest('tr');
                const isAvailable = this.checked;
                
                // Actualizar clase de la fila
                if (isAvailable) {
                    row.classList.add('bg-green-50');
                    row.classList.remove('bg-white');
                } else {
                    row.classList.remove('bg-green-50');
                    row.classList.add('bg-white');
                }
                
                // Habilitar/deshabilitar inputs de tiempo
                const timeInputs = row.querySelectorAll('.start-time, .end-time');
                timeInputs.forEach(input => {
                    input.disabled = !isAvailable;
                });
                
                // Mostrar notificación de guardado automático
                showAutoSaveNotification();
                
                // Enviar el formulario automáticamente
                document.getElementById('profile-form').submit();
            });
        });
        
        // Manejar cambio en los horarios
        document.querySelectorAll('.start-time, .end-time').forEach(input => {
            input.addEventListener('change', function() {
                showAutoSaveNotification();
                // Enviar el formulario automáticamente
                document.getElementById('profile-form').submit();
            });
        });
        
        // Añadir nuevo horario
        document.addEventListener('click', function(e) {
            if (e.target.closest('.add-slot')) {
                const timeSlots = e.target.closest('.time-slots');
                addTimeSlot(timeSlots);
            }
        });

        // Eliminar horario
        document.addEventListener('click', function(e) {
            if (e.target.closest('.remove-slot')) {
                const slotGroup = e.target.closest('.flex.items-center.space-x-2');
                if (slotGroup) {
                    slotGroup.remove();
                }
            }
        });

        // Copiar horario a toda la semana
        const copyWeekBtn = document.getElementById('copyWeekSchedule');
        if (copyWeekBtn) {
            copyWeekBtn.addEventListener('click', function() {
                const firstDay = document.querySelector('tr[data-day]');
                if (firstDay) {
                    const daySlots = firstDay.querySelectorAll('.time-slots .flex.items-center.space-x-2');
                    document.querySelectorAll('tr[data-day]').forEach(row => {
                        if (row !== firstDay) {
                            const timeSlots = row.querySelector('.time-slots');
                            timeSlots.innerHTML = ''; // Limpiar horarios existentes
                            
                            daySlots.forEach(slot => {
                                const newSlot = slot.cloneNode(true);
                                timeSlots.appendChild(newSlot);
                            });
                            
                            // Añadir botón de añadir horario
                            const addButton = document.createElement('button');
                            addButton.type = 'button';
                            addButton.className = 'add-slot mt-2 text-xs text-blue-600 hover:text-blue-800 flex items-center';
                            addButton.innerHTML = '<i class="fas fa-plus-circle mr-1"></i> Añadir horario';
                            timeSlots.appendChild(addButton);
                            
                            // Marcar como disponible
                            const checkbox = row.querySelector('.availability-toggle');
                            if (checkbox && !checkbox.checked) {
                                checkbox.checked = true;
                                checkbox.dispatchEvent(new Event('change'));
                            }
                        }
                    });
                    
                    showToast('success', 'Horario copiado a toda la semana');
                }
            });
        }

        // Función para añadir un nuevo bloque de horario
        function addTimeSlot(container) {
            const slotIndex = container.querySelectorAll('.flex.items-center.space-x-2').length;
            const slotHtml = `
                <div class="flex items-center space-x-2 mb-2">
                    <input type="time" 
                           name="availability[${container.dataset.day}][slots][${slotIndex}][start]" 
                           class="slot-start border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                           value="09:00">
                    <span class="text-gray-500">a</span>
                    <input type="time" 
                           name="availability[${container.dataset.day}][slots][${slotIndex}][end]" 
                           class="slot-end border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                           value="18:00">
                    ${slotIndex > 0 ? '<button type="button" class="remove-slot text-red-500 hover:text-red-700"><i class="fas fa-times"></i></button>' : ''}
                </div>`;
                
            // Insertar antes del botón de añadir
            const addButton = container.querySelector('.add-slot');
            if (addButton) {
                addButton.insertAdjacentHTML('beforebegin', slotHtml);
            } else {
                container.insertAdjacentHTML('beforeend', slotHtml);
            }
        }

        // Manejar el envío del formulario
        const availabilityForm = document.getElementById('availabilityForm');
        if (availabilityForm) {
            availabilityForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                // Aquí iría el código para enviar el formulario vía AJAX
                const formData = new FormData(this);
                
                // Simular envío (reemplazar con llamada AJAX real)
                setTimeout(() => {
                    showToast('success', 'Horario guardado correctamente');
                }, 1000);
            });
        }
    });
</script>
@endpush
