@extends('layouts.mentor')

@section('title', 'Mi Perfil - MentorHub')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/mentor-profile.css') }}">
@endpush

@section('content')
<div class="container-fluid">
    <!-- Encabezado -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Mi Perfil</h1>
    </div>

    <!-- Mensajes de alerta -->
    @include('partials.alerts')

    <!-- Contenido del perfil -->
    <div class="row">
        <!-- Columna izquierda: Foto y datos básicos -->
        <div class="col-xl-4 col-lg-5">
            <div class="card shadow mb-4">
                <!-- Cabecera con foto de perfil -->
                <div class="card-header py-3 d-flex flex-column align-items-center">
                    <div class="position-relative mb-3">
                        <img class="img-profile rounded-circle" width="150" height="150"
                            src="{{ auth()->user()->profile_photo_url ?? 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->name) . '&color=7F9CF5&background=EBF4FF' }}">
                        <button class="btn btn-sm btn-primary position-absolute bottom-0 end-0 rounded-circle p-2"
                                data-bs-toggle="modal" data-bs-target="#changePhotoModal">
                            <i class="fas fa-camera"></i>
                        </button>
                    </div>
                    <h5 class="font-weight-bold">{{ auth()->user()->name }}</h5>
                    <p class="text-muted mb-0">Mentor</p>
                </div>
                <!-- Cuerpo con información básica -->
                <div class="card-body">
                    <div class="mb-3">
                        <h6 class="text-primary font-weight-bold">Información de Contacto</h6>
                        <div class="mb-2">
                            <i class="fas fa-envelope fa-fw text-gray-400 mr-2"></i>
                            {{ auth()->user()->email }}
                        </div>
                        <div class="mb-2">
                            <i class="fas fa-phone fa-fw text-gray-400 mr-2"></i>
                            {{ auth()->user()->phone ?? 'No especificado' }}
                        </div>
                        <div>
                            <i class="fas fa-map-marker-alt fa-fw text-gray-400 mr-2"></i>
                            {{ auth()->user()->location ?? 'No especificado' }}
                        </div>
                    </div>
                    <div class="mb-3">
                        <h6 class="text-primary font-weight-bold">Estadísticas</h6>
                        <div class="mb-2 d-flex justify-content-between">
                            <span>Sesiones completadas:</span>
                            <span class="font-weight-bold">{{ $completedSessions ?? 0 }}</span>
                        </div>
                        <div class="mb-2 d-flex justify-content-between">
                            <span>Estudiantes activos:</span>
                            <span class="font-weight-bold">{{ $activeStudents ?? 0 }}</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span>Calificación promedio:</span>
                            <div>
                                <span class="font-weight-bold mr-1">{{ $averageRating ?? '0.0' }}</span>
                                <i class="fas fa-star text-warning"></i>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <h6 class="text-primary font-weight-bold">Especialidades</h6>
                        <div class="d-flex flex-wrap gap-1">
                            @forelse($specialities ?? [] as $speciality)
                                <span class="badge bg-primary">{{ $speciality->name }}</span>
                            @empty
                                <span class="text-muted">No hay especialidades definidas</span>
                            @endforelse
                        </div>
                    </div>
                    <div class="text-center mt-4">
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editBasicInfoModal">
                            <i class="fas fa-edit mr-1"></i> Editar información básica
                        </button>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Columna derecha: Pestañas con más información -->
        <div class="col-xl-8 col-lg-7">
            <!-- Navegación de pestañas -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <ul class="nav nav-tabs card-header-tabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="bio-tab" data-bs-toggle="tab" href="#bio" role="tab">
                                <i class="fas fa-user-circle mr-1"></i> Biografía y Experiencia
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="specialities-tab" data-bs-toggle="tab" href="#specialities" role="tab">
                                <i class="fas fa-graduation-cap mr-1"></i> Especialidades
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="availability-tab" data-bs-toggle="tab" href="#availability" role="tab">
                                <i class="fas fa-calendar-alt mr-1"></i> Disponibilidad
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="settings-tab" data-bs-toggle="tab" href="#settings" role="tab">
                                <i class="fas fa-cog mr-1"></i> Configuración
                            </a>
                        </li>
                    </ul>
                </div>
                
                <!-- Contenido de las pestañas -->
                <div class="card-body">
                    <div class="tab-content">
                        <!-- Pestaña de Biografía -->
                        <div class="tab-pane fade show active" id="bio" role="tabpanel">
                            <h5 class="card-title">Biografía Profesional</h5>
                            <div class="mb-4">
                                @if(isset($mentorProfile) && $mentorProfile->bio)
                                    <p>{{ $mentorProfile->bio }}</p>
                                @else
                                    <p class="text-muted">No has agregado una biografía profesional todavía. Comparte tu experiencia y conocimientos para que los estudiantes te conozcan mejor.</p>
                                @endif
                                <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editBioModal">
                                    <i class="fas fa-edit mr-1"></i> Editar biografía
                                </button>
                            </div>
                            
                            <h5 class="card-title">Experiencia Profesional</h5>
                            <div class="mb-4">
                                @forelse($experiences ?? [] as $experience)
                                    <div class="card mb-3">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between">
                                                <h6 class="card-subtitle mb-2 text-primary">{{ $experience->position }}</h6>
                                                <div>
                                                    <button class="btn btn-sm btn-link text-primary" data-bs-toggle="modal" data-bs-target="#editExperienceModal" data-id="{{ $experience->id }}">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                    <button class="btn btn-sm btn-link text-danger" data-bs-toggle="modal" data-bs-target="#deleteExperienceModal" data-id="{{ $experience->id }}">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                            </div>
                                            <h6 class="card-subtitle mb-2 text-muted">{{ $experience->company }}</h6>
                                            <p class="card-text small text-muted">
                                                {{ $experience->start_date->format('M Y') }} - 
                                                @if($experience->current)
                                                    Presente
                                                @else
                                                    {{ $experience->end_date->format('M Y') }}
                                                @endif
                                            </p>
                                            <p class="card-text">{{ $experience->description }}</p>
                                        </div>
                                    </div>
                                @empty
                                    <p class="text-muted">No has agregado experiencia profesional todavía.</p>
                                @endforelse
                                <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#addExperienceModal">
                                    <i class="fas fa-plus-circle mr-1"></i> Añadir experiencia
                                </button>
                            </div>
                            
                            <h5 class="card-title">Educación</h5>
                            <div>
                                @forelse($education ?? [] as $edu)
                                    <div class="card mb-3">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between">
                                                <h6 class="card-subtitle mb-2 text-primary">{{ $edu->degree }}</h6>
                                                <div>
                                                    <button class="btn btn-sm btn-link text-primary" data-bs-toggle="modal" data-bs-target="#editEducationModal" data-id="{{ $edu->id }}">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                    <button class="btn btn-sm btn-link text-danger" data-bs-toggle="modal" data-bs-target="#deleteEducationModal" data-id="{{ $edu->id }}">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                            </div>
                                            <h6 class="card-subtitle mb-2 text-muted">{{ $edu->institution }}</h6>
                                            <p class="card-text small text-muted">
                                                {{ $edu->start_year }} - 
                                                @if($edu->current)
                                                    Presente
                                                @else
                                                    {{ $edu->end_year }}
                                                @endif
                                            </p>
                                            <p class="card-text">{{ $edu->description }}</p>
                                        </div>
                                    </div>
                                @empty
                                    <p class="text-muted">No has agregado información educativa todavía.</p>
                                @endforelse
                                <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#addEducationModal">
                                    <i class="fas fa-plus-circle mr-1"></i> Añadir educación
                                </button>
                            </div>
                        </div>
                        
                        <!-- Pestaña de Especialidades -->
                        <div class="tab-pane fade" id="specialities" role="tabpanel">
                            <h5 class="card-title">Mis Especialidades</h5>
                            <p class="text-muted mb-4">Selecciona las áreas en las que tienes experiencia y conocimientos para ofrecer mentoría.</p>
                            
                            <div class="mb-4">
                                <div class="d-flex flex-wrap gap-2 mb-3">
                                    @forelse($specialities ?? [] as $speciality)
                                        <div class="badge bg-primary p-2 d-flex align-items-center">
                                            {{ $speciality->name }}
                                            <button class="btn btn-sm text-white ms-2 p-0" data-speciality-id="{{ $speciality->id }}">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </div>
                                    @empty
                                        <p class="text-muted">No has seleccionado especialidades todavía.</p>
                                    @endforelse
                                </div>
                                <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#addSpecialityModal">
                                    <i class="fas fa-plus-circle mr-1"></i> Añadir especialidad
                                </button>
                            </div>
                            
                            <h5 class="card-title">Habilidades</h5>
                            <p class="text-muted mb-4">Añade habilidades específicas que complementen tus especialidades.</p>
                            
                            <div>
                                <div class="d-flex flex-wrap gap-2 mb-3">
                                    @forelse($skills ?? [] as $skill)
                                        <div class="badge bg-secondary p-2 d-flex align-items-center">
                                            {{ $skill->name }}
                                            <button class="btn btn-sm text-white ms-2 p-0" data-skill-id="{{ $skill->id }}">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </div>
                                    @empty
                                        <p class="text-muted">No has añadido habilidades todavía.</p>
                                    @endforelse
                                </div>
                                <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#addSkillModal">
                                    <i class="fas fa-plus-circle mr-1"></i> Añadir habilidad
                                </button>
                            </div>
                        </div>
                        
                        <!-- Pestaña de Disponibilidad -->
                        <div class="tab-pane fade" id="availability" role="tabpanel">
                            <h5 class="card-title">Mi Disponibilidad</h5>
                            <p class="text-muted mb-4">Configura tus horarios disponibles para sesiones de mentoría.</p>
                            
                            <div class="table-responsive mb-4">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Día</th>
                                            <th>Disponible</th>
                                            <th>Horario</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $days = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo'];
                                        @endphp
                                        
                                        @foreach($days as $index => $day)
                                            <tr>
                                                <td>{{ $day }}</td>
                                                <td>
                                                    <div class="form-check form-switch">
                                                        <input class="form-check-input" type="checkbox" id="day{{ $index }}" 
                                                            {{ isset($availability[$index]) && $availability[$index]['available'] ? 'checked' : '' }}>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="d-flex align-items-center gap-2">
                                                        <select class="form-select form-select-sm" id="startTime{{ $index }}" 
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
                                                        <span>a</span>
                                                        <select class="form-select form-select-sm" id="endTime{{ $index }}" 
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
                            
                            <button class="btn btn-primary" id="saveAvailabilityBtn">
                                <i class="fas fa-save mr-1"></i> Guardar disponibilidad
                            </button>
                        </div>
                        
                        <!-- Pestaña de Configuración -->
                        <div class="tab-pane fade" id="settings" role="tabpanel">
                            <h5 class="card-title">Configuración de la Cuenta</h5>
                            
                            <div class="mb-4">
                                <h6 class="text-primary">Cambiar Contraseña</h6>
                                <form id="passwordForm">
                                    <div class="mb-3">
                                        <label for="currentPassword" class="form-label">Contraseña actual</label>
                                        <input type="password" class="form-control" id="currentPassword" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="newPassword" class="form-label">Nueva contraseña</label>
                                        <input type="password" class="form-control" id="newPassword" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="confirmPassword" class="form-label">Confirmar contraseña</label>
                                        <input type="password" class="form-control" id="confirmPassword" required>
                                    </div>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-key mr-1"></i> Cambiar contraseña
                                    </button>
                                </form>
                            </div>
                            
                            <div class="mb-4">
                                <h6 class="text-primary">Preferencias de Notificación</h6>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" id="emailNotifications" 
                                        {{ isset($preferences) && $preferences->email_notifications ? 'checked' : '' }}>
                                    <label class="form-check-label" for="emailNotifications">
                                        Recibir notificaciones por correo electrónico
                                    </label>
                                </div>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" id="sessionReminders" 
                                        {{ isset($preferences) && $preferences->session_reminders ? 'checked' : '' }}>
                                    <label class="form-check-label" for="sessionReminders">
                                        Recordatorios de sesiones
                                    </label>
                                </div>
                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="checkbox" id="marketingEmails" 
                                        {{ isset($preferences) && $preferences->marketing_emails ? 'checked' : '' }}>
                                    <label class="form-check-label" for="marketingEmails">
                                        Recibir correos de marketing y novedades
                                    </label>
                                </div>
                                <button class="btn btn-primary" id="savePreferencesBtn">
                                    <i class="fas fa-save mr-1"></i> Guardar preferencias
                                </button>
                            </div>
                            
                            <div>
                                <h6 class="text-danger">Zona de Peligro</h6>
                                <p class="text-muted">Estas acciones son permanentes y no se pueden deshacer.</p>
                                <button class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deactivateAccountModal">
                                    <i class="fas fa-user-slash mr-1"></i> Desactivar cuenta
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
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="changePhotoModalLabel">Cambiar Foto de Perfil</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="photoForm" enctype="multipart/form-data">
                    <div class="mb-3 text-center">
                        <img id="photoPreview" src="{{ auth()->user()->profile_photo_url ?? 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->name) . '&color=7F9CF5&background=EBF4FF' }}" 
                             class="rounded-circle mb-3" width="150" height="150">
                    </div>
                    <div class="mb-3">
                        <label for="photoFile" class="form-label">Seleccionar nueva foto</label>
                        <input class="form-control" type="file" id="photoFile" accept="image/*" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="savePhotoBtn">Guardar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal para editar información básica -->
<div class="modal fade" id="editBasicInfoModal" tabindex="-1" aria-labelledby="editBasicInfoModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editBasicInfoModalLabel">Editar Información Básica</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="basicInfoForm">
                    <div class="mb-3">
                        <label for="name" class="form-label">Nombre completo</label>
                        <input type="text" class="form-control" id="name" value="{{ auth()->user()->name }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Correo electrónico</label>
                        <input type="email" class="form-control" id="email" value="{{ auth()->user()->email }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="phone" class="form-label">Teléfono</label>
                        <input type="tel" class="form-control" id="phone" value="{{ auth()->user()->phone ?? '' }}">
                    </div>
                    <div class="mb-3">
                        <label for="location" class="form-label">Ubicación</label>
                        <input type="text" class="form-control" id="location" value="{{ auth()->user()->location ?? '' }}">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="saveBasicInfoBtn">Guardar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal para editar biografía -->
<div class="modal fade" id="editBioModal" tabindex="-1" aria-labelledby="editBioModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editBioModalLabel">Editar Biografía Profesional</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="bioForm">
                    <div class="mb-3">
                        <label for="bio" class="form-label">Biografía (máx. 500 caracteres)</label>
                        <textarea class="form-control" id="bio" rows="5" maxlength="500">{{ $mentorProfile->bio ?? '' }}</textarea>
                        <div class="form-text">Describe tu experiencia profesional, enfoque de mentoría y áreas de especialización.</div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="saveBioBtn">Guardar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal para añadir experiencia -->
<div class="modal fade" id="addExperienceModal" tabindex="-1" aria-labelledby="addExperienceModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addExperienceModalLabel">Añadir Experiencia Profesional</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="experienceForm">
                    <div class="mb-3">
                        <label for="position" class="form-label">Cargo</label>
                        <input type="text" class="form-control" id="position" required>
                    </div>
                    <div class="mb-3">
                        <label for="company" class="form-label">Empresa</label>
                        <input type="text" class="form-control" id="company" required>
                    </div>
                    <div class="row mb-3">
                        <div class="col">
                            <label for="startDate" class="form-label">Fecha de inicio</label>
                            <input type="month" class="form-control" id="startDate" required>
                        </div>
                        <div class="col" id="endDateCol">
                            <label for="endDate" class="form-label">Fecha de fin</label>
                            <input type="month" class="form-control" id="endDate">
                        </div>
                    </div>
                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="currentJob">
                        <label class="form-check-label" for="currentJob">Trabajo actual</label>
                    </div>
                    <div class="mb-3">
                        <label for="expDescription" class="form-label">Descripción</label>
                        <textarea class="form-control" id="expDescription" rows="3"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="saveExperienceBtn">Guardar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal para añadir educación -->
<div class="modal fade" id="addEducationModal" tabindex="-1" aria-labelledby="addEducationModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addEducationModalLabel">Añadir Educación</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="educationForm">
                    <div class="mb-3">
                        <label for="degree" class="form-label">Título o Grado</label>
                        <input type="text" class="form-control" id="degree" required>
                    </div>
                    <div class="mb-3">
                        <label for="institution" class="form-label">Institución</label>
                        <input type="text" class="form-control" id="institution" required>
                    </div>
                    <div class="row mb-3">
                        <div class="col">
                            <label for="startYear" class="form-label">Año de inicio</label>
                            <input type="number" class="form-control" id="startYear" min="1950" max="2030" required>
                        </div>
                        <div class="col" id="endYearCol">
                            <label for="endYear" class="form-label">Año de fin</label>
                            <input type="number" class="form-control" id="endYear" min="1950" max="2030">
                        </div>
                    </div>
                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="currentEducation">
                        <label class="form-check-label" for="currentEducation">En curso</label>
                    </div>
                    <div class="mb-3">
                        <label for="eduDescription" class="form-label">Descripción</label>
                        <textarea class="form-control" id="eduDescription" rows="3"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="saveEducationBtn">Guardar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal para añadir especialidad -->
<div class="modal fade" id="addSpecialityModal" tabindex="-1" aria-labelledby="addSpecialityModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addSpecialityModalLabel">Añadir Especialidad</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="specialityForm">
                    <div class="mb-3">
                        <label for="speciality" class="form-label">Especialidad</label>
                        <select class="form-select" id="speciality" required>
                            <option value="" selected disabled>Seleccionar especialidad...</option>
                            @foreach($availableSpecialities ?? [] as $speciality)
                                <option value="{{ $speciality->id }}">{{ $speciality->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="saveSpecialityBtn">Añadir</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal para añadir habilidad -->
<div class="modal fade" id="addSkillModal" tabindex="-1" aria-labelledby="addSkillModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addSkillModalLabel">Añadir Habilidad</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="skillForm">
                    <div class="mb-3">
                        <label for="skill" class="form-label">Habilidad</label>
                        <input type="text" class="form-control" id="skill" required>
                        <div class="form-text">Ingresa una habilidad específica (ej. JavaScript, Diseño UX, Análisis de datos)</div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="saveSkillBtn">Añadir</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal para desactivar cuenta -->
<div class="modal fade" id="deactivateAccountModal" tabindex="-1" aria-labelledby="deactivateAccountModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-danger" id="deactivateAccountModalLabel">Desactivar Cuenta</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <strong>Advertencia:</strong> Esta acción desactivará tu cuenta de mentor. Tus estudiantes no podrán acceder a tus servicios y perderás acceso a tus datos de mentor.
                </div>
                <p>Para confirmar, escribe "DESACTIVAR" en el campo de abajo:</p>
                <div class="mb-3">
                    <input type="text" class="form-control" id="deactivateConfirm" placeholder="DESACTIVAR">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-danger" id="deactivateAccountBtn" disabled>
                    <i class="fas fa-user-slash me-1"></i> Desactivar Cuenta
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
