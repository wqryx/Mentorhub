@extends('layouts.dashboard.student')

@section('dashboard-content')
<div class="container-fluid">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Configuración</h1>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-md-3">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="card-title mb-0">Menú de configuración</h5>
                </div>
                <div class="list-group list-group-flush">
                    <a href="#account-section" class="list-group-item list-group-item-action active" data-bs-toggle="list">
                        <i class="fas fa-user me-2"></i> Cuenta
                    </a>
                    <a href="#notifications-section" class="list-group-item list-group-item-action" data-bs-toggle="list">
                        <i class="fas fa-bell me-2"></i> Notificaciones
                    </a>
                    <a href="#privacy-section" class="list-group-item list-group-item-action" data-bs-toggle="list">
                        <i class="fas fa-lock me-2"></i> Privacidad
                    </a>
                    <a href="#appearance-section" class="list-group-item list-group-item-action" data-bs-toggle="list">
                        <i class="fas fa-palette me-2"></i> Apariencia
                    </a>
                    <a href="#learning-section" class="list-group-item list-group-item-action" data-bs-toggle="list">
                        <i class="fas fa-graduation-cap me-2"></i> Aprendizaje
                    </a>
                    <a href="#security-section" class="list-group-item list-group-item-action" data-bs-toggle="list">
                        <i class="fas fa-shield-alt me-2"></i> Seguridad
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-9">
            <div class="tab-content">
                <!-- Sección de Cuenta -->
                <div class="tab-pane fade show active" id="account-section">
                    <div class="card shadow-sm mb-4">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Información de la cuenta</h5>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('student.profile.update') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                @method('PATCH')
                                
                                <div class="row mb-4">
                                    <div class="col-md-3 text-center">
                                        @if(auth()->user()->profile && auth()->user()->profile->avatar)
                                            <img src="{{ asset('storage/' . auth()->user()->profile->avatar) }}" alt="{{ auth()->user()->name }}" class="img-thumbnail rounded-circle mb-3" style="width: 150px; height: 150px; object-fit: cover;">
                                        @else
                                            <div class="rounded-circle bg-primary d-flex align-items-center justify-content-center text-white mb-3" style="width: 150px; height: 150px; font-size: 60px; margin: 0 auto;">
                                                {{ substr(auth()->user()->name, 0, 1) }}
                                            </div>
                                        @endif
                                        
                                        <div class="mb-3">
                                            <input type="file" class="form-control form-control-sm" id="avatar" name="avatar" accept="image/*">
                                            <div class="form-text">JPG, PNG o GIF. Máx. 2MB</div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-9">
                                        <div class="mb-3">
                                            <label for="name" class="form-label">Nombre completo</label>
                                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $user->name) }}" required>
                                            @error('name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label for="email" class="form-label">Correo electrónico</label>
                                            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $user->email) }}" required>
                                            @error('email')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label for="phone" class="form-label">Teléfono</label>
                                            <input type="tel" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone', $user->profile->phone ?? '') }}">
                                            @error('phone')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label for="bio" class="form-label">Biografía</label>
                                            <textarea class="form-control @error('bio') is-invalid @enderror" id="bio" name="bio" rows="3">{{ old('bio', $user->profile->bio ?? '') }}</textarea>
                                            @error('bio')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                
                                <button type="submit" class="btn btn-primary">Guardar cambios</button>
                            </form>
                        </div>
                    </div>
                </div>
                
                <!-- Sección de Notificaciones -->
                <div class="tab-pane fade" id="notifications-section">
                    <div class="card shadow-sm mb-4">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Preferencias de notificaciones</h5>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('student.settings.update-notifications') }}" method="POST">
                                @csrf
                                @method('PATCH')
                                
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Notificaciones por correo electrónico</label>
                                    
                                    <div class="form-check form-switch mb-2">
                                        <input class="form-check-input" type="checkbox" id="email_course_updates" name="email_notifications[]" value="course_updates" checked>
                                        <label class="form-check-label" for="email_course_updates">Actualizaciones de cursos</label>
                                    </div>
                                    
                                    <div class="form-check form-switch mb-2">
                                        <input class="form-check-input" type="checkbox" id="email_new_content" name="email_notifications[]" value="new_content" checked>
                                        <label class="form-check-label" for="email_new_content">Nuevo contenido en cursos inscritos</label>
                                    </div>
                                    
                                    <div class="form-check form-switch mb-2">
                                        <input class="form-check-input" type="checkbox" id="email_events" name="email_notifications[]" value="events" checked>
                                        <label class="form-check-label" for="email_events">Recordatorios de eventos</label>
                                    </div>
                                    
                                    <div class="form-check form-switch mb-2">
                                        <input class="form-check-input" type="checkbox" id="email_messages" name="email_notifications[]" value="messages" checked>
                                        <label class="form-check-label" for="email_messages">Nuevos mensajes</label>
                                    </div>
                                    
                                    <div class="form-check form-switch mb-2">
                                        <input class="form-check-input" type="checkbox" id="email_promotions" name="email_notifications[]" value="promotions">
                                        <label class="form-check-label" for="email_promotions">Promociones y ofertas</label>
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Notificaciones dentro de la plataforma</label>
                                    
                                    <div class="form-check form-switch mb-2">
                                        <input class="form-check-input" type="checkbox" id="platform_course_updates" name="platform_notifications[]" value="course_updates" checked>
                                        <label class="form-check-label" for="platform_course_updates">Actualizaciones de cursos</label>
                                    </div>
                                    
                                    <div class="form-check form-switch mb-2">
                                        <input class="form-check-input" type="checkbox" id="platform_new_content" name="platform_notifications[]" value="new_content" checked>
                                        <label class="form-check-label" for="platform_new_content">Nuevo contenido en cursos inscritos</label>
                                    </div>
                                    
                                    <div class="form-check form-switch mb-2">
                                        <input class="form-check-input" type="checkbox" id="platform_events" name="platform_notifications[]" value="events" checked>
                                        <label class="form-check-label" for="platform_events">Recordatorios de eventos</label>
                                    </div>
                                    
                                    <div class="form-check form-switch mb-2">
                                        <input class="form-check-input" type="checkbox" id="platform_messages" name="platform_notifications[]" value="messages" checked>
                                        <label class="form-check-label" for="platform_messages">Nuevos mensajes</label>
                                    </div>
                                </div>
                                
                                <button type="submit" class="btn btn-primary">Guardar preferencias</button>
                            </form>
                        </div>
                    </div>
                </div>
                
                <!-- Sección de Privacidad -->
                <div class="tab-pane fade" id="privacy-section">
                    <div class="card shadow-sm mb-4">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Configuración de privacidad</h5>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('student.settings.update-privacy') }}" method="POST">
                                @csrf
                                @method('PATCH')
                                
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Visibilidad del perfil</label>
                                    
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="radio" name="profile_visibility" id="visibility_public" value="public">
                                        <label class="form-check-label" for="visibility_public">
                                            <strong>Público</strong> - Todos los usuarios pueden ver mi perfil
                                        </label>
                                    </div>
                                    
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="radio" name="profile_visibility" id="visibility_students" value="students" checked>
                                        <label class="form-check-label" for="visibility_students">
                                            <strong>Estudiantes</strong> - Solo estudiantes y profesores pueden ver mi perfil
                                        </label>
                                    </div>
                                    
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="radio" name="profile_visibility" id="visibility_private" value="private">
                                        <label class="form-check-label" for="visibility_private">
                                            <strong>Privado</strong> - Solo mis profesores pueden ver mi perfil
                                        </label>
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Datos compartidos</label>
                                    
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox" id="share_email" name="share_data[]" value="email">
                                        <label class="form-check-label" for="share_email">Compartir mi correo electrónico</label>
                                    </div>
                                    
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox" id="share_phone" name="share_data[]" value="phone">
                                        <label class="form-check-label" for="share_phone">Compartir mi número de teléfono</label>
                                    </div>
                                    
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox" id="share_courses" name="share_data[]" value="courses" checked>
                                        <label class="form-check-label" for="share_courses">Mostrar cursos en los que estoy inscrito</label>
                                    </div>
                                </div>
                                
                                <button type="submit" class="btn btn-primary">Guardar configuración</button>
                            </form>
                        </div>
                    </div>
                </div>
                
                <!-- Sección de Apariencia -->
                <div class="tab-pane fade" id="appearance-section">
                    <div class="card shadow-sm mb-4">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Preferencias de apariencia</h5>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('student.settings.update-appearance') }}" method="POST">
                                @csrf
                                @method('PATCH')
                                
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Tema</label>
                                    
                                    <div class="row">
                                        <div class="col-md-4 mb-3">
                                            <div class="card">
                                                <div class="card-body text-center">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="theme" id="theme_light" value="light" checked>
                                                        <label class="form-check-label w-100" for="theme_light">
                                                            <div class="bg-light border p-3 mb-2">
                                                                <div class="bg-primary text-white p-2 mb-2">Header</div>
                                                                <div class="bg-white border p-2">Content</div>
                                                            </div>
                                                            Claro
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-4 mb-3">
                                            <div class="card">
                                                <div class="card-body text-center">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="theme" id="theme_dark" value="dark">
                                                        <label class="form-check-label w-100" for="theme_dark">
                                                            <div class="bg-dark border p-3 mb-2">
                                                                <div class="bg-primary text-white p-2 mb-2">Header</div>
                                                                <div class="bg-secondary text-white p-2">Content</div>
                                                            </div>
                                                            Oscuro
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-4 mb-3">
                                            <div class="card">
                                                <div class="card-body text-center">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="theme" id="theme_system" value="system">
                                                        <label class="form-check-label w-100" for="theme_system">
                                                            <div class="bg-light border p-3 mb-2">
                                                                <div class="bg-white border p-2 mb-1" style="height: 15px;"></div>
                                                                <div class="bg-dark border p-2" style="height: 15px;"></div>
                                                            </div>
                                                            Automático (sistema)
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="text_size" class="form-label fw-bold">Tamaño del texto</label>
                                    <select class="form-select" id="text_size" name="text_size">
                                        <option value="small">Pequeño</option>
                                        <option value="medium" selected>Mediano</option>
                                        <option value="large">Grande</option>
                                    </select>
                                </div>
                                
                                <button type="submit" class="btn btn-primary">Guardar preferencias</button>
                            </form>
                        </div>
                    </div>
                </div>
                
                <!-- Sección de Aprendizaje -->
                <div class="tab-pane fade" id="learning-section">
                    <div class="card shadow-sm mb-4">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Preferencias de aprendizaje</h5>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('student.settings.update-learning') }}" method="POST">
                                @csrf
                                @method('PATCH')
                                
                                <div class="mb-3">
                                    <label for="default_language" class="form-label fw-bold">Idioma de contenido preferido</label>
                                    <select class="form-select" id="default_language" name="default_language">
                                        <option value="es" selected>Español</option>
                                        <option value="en">Inglés</option>
                                        <option value="fr">Francés</option>
                                        <option value="de">Alemán</option>
                                        <option value="pt">Portugués</option>
                                    </select>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Preferencias de video</label>
                                    
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox" id="autoplay_videos" name="video_preferences[]" value="autoplay" checked>
                                        <label class="form-check-label" for="autoplay_videos">Reproducir videos automáticamente</label>
                                    </div>
                                    
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox" id="subtitles" name="video_preferences[]" value="subtitles">
                                        <label class="form-check-label" for="subtitles">Mostrar subtítulos cuando estén disponibles</label>
                                    </div>
                                    
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox" id="hd_quality" name="video_preferences[]" value="hd_quality" checked>
                                        <label class="form-check-label" for="hd_quality">Reproducir videos en alta calidad cuando sea posible</label>
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="course_display" class="form-label fw-bold">Visualización de cursos</label>
                                    <select class="form-select" id="course_display" name="course_display">
                                        <option value="grid" selected>Cuadrícula</option>
                                        <option value="list">Lista</option>
                                    </select>
                                </div>
                                
                                <button type="submit" class="btn btn-primary">Guardar preferencias</button>
                            </form>
                        </div>
                    </div>
                </div>
                
                <!-- Sección de Seguridad -->
                <div class="tab-pane fade" id="security-section">
                    <div class="card shadow-sm mb-4">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Seguridad de la cuenta</h5>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('student.settings.update-password') }}" method="POST">
                                @csrf
                                @method('PATCH')
                                
                                <div class="mb-3">
                                    <label for="current_password" class="form-label">Contraseña actual</label>
                                    <input type="password" class="form-control @error('current_password') is-invalid @enderror" id="current_password" name="current_password" required>
                                    @error('current_password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="mb-3">
                                    <label for="password" class="form-label">Nueva contraseña</label>
                                    <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" required>
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">La contraseña debe tener al menos 8 caracteres y contener letras y números.</div>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="password_confirmation" class="form-label">Confirmar nueva contraseña</label>
                                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                                </div>
                                
                                <button type="submit" class="btn btn-primary">Cambiar contraseña</button>
                            </form>
                            
                            <hr class="my-4">
                            
                            <h5>Dispositivos conectados</h5>
                            <p class="text-muted">Estos son los dispositivos que actualmente tienen acceso a tu cuenta.</p>
                            
                            <div class="list-group mb-3">
                                <div class="list-group-item">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="mb-1"><i class="fas fa-laptop me-2"></i> Windows 10 - Chrome</h6>
                                            <p class="text-muted mb-0 small">
                                                <span>IP: 192.168.1.1</span>
                                                <span class="mx-2">•</span>
                                                <span>Última actividad: Ahora (Este dispositivo)</span>
                                            </p>
                                        </div>
                                        <span class="badge bg-success">Activo</span>
                                    </div>
                                </div>
                                
                                <div class="list-group-item">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="mb-1"><i class="fas fa-mobile-alt me-2"></i> iPhone - Safari</h6>
                                            <p class="text-muted mb-0 small">
                                                <span>IP: 192.168.1.2</span>
                                                <span class="mx-2">•</span>
                                                <span>Última actividad: Hace 2 días</span>
                                            </p>
                                        </div>
                                        <button class="btn btn-sm btn-outline-danger">Cerrar sesión</button>
                                    </div>
                                </div>
                            </div>
                            
                            <button class="btn btn-outline-danger">Cerrar sesión en todos los dispositivos</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Activar la navegación por pestañas
        var triggerTabList = [].slice.call(document.querySelectorAll('a[data-bs-toggle="list"]'));
        triggerTabList.forEach(function(triggerEl) {
            var tabTrigger = new bootstrap.Tab(triggerEl);
            
            triggerEl.addEventListener('click', function(event) {
                event.preventDefault();
                tabTrigger.show();
            });
        });
        
        // Permitir navegación directa a pestañas con fragmentos de URL
        if (window.location.hash) {
            const hash = window.location.hash;
            const tabTrigger = document.querySelector(`a[href="${hash}"]`);
            if (tabTrigger) {
                new bootstrap.Tab(tabTrigger).show();
            }
        }
    });
</script>
@endpush
