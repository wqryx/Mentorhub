@extends('layouts.admin')

@section('title', 'Configuración - Admin')

@section('content')
<div class="container-fluid">
    <!-- Encabezado -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Configuración del Sistema</h1>
        <div>
            <button type="button" class="btn btn-secondary me-2" id="resetSettings">
                <i class="fas fa-undo"></i> Restablecer
            </button>
            <button type="button" class="btn btn-primary" id="saveSettings">
                <i class="fas fa-save"></i> Guardar Cambios
            </button>
        </div>
    </div>

    <!-- Mensajes de alerta -->
    <div id="alertContainer"></div>

    <!-- Pestañas de configuración -->
    <ul class="nav nav-tabs" id="settingsTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="general-tab" data-bs-toggle="tab" data-bs-target="#general" type="button" role="tab">
                <i class="fas fa-cog me-1"></i> General
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="email-tab" data-bs-toggle="tab" data-bs-target="#email" type="button" role="tab">
                <i class="fas fa-envelope me-1"></i> Correo Electrónico
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="activity-logs-tab" data-bs-toggle="tab" data-bs-target="#activity-logs" type="button" role="tab">
                <i class="fas fa-history me-1"></i> Registros de Actividad
            </button>
        </li>
    </ul>

    <!-- Contenido de las pestañas -->
    <div class="tab-content p-3 border border-top-0 rounded-bottom" id="settingsTabsContent">
        <!-- Pestaña General -->
        <div class="tab-pane fade show active" id="general" role="tabpanel">
            <form id="generalSettingsForm">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="app_name" class="form-label">Nombre de la Aplicación</label>
                            <input type="text" class="form-control" id="app_name" name="app_name" value="{{ config('app.name') }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="timezone" class="form-label">Zona Horaria</label>
                            <select class="form-select" id="timezone" name="timezone" required>
                                @foreach(timezone_identifiers_list() as $timezone)
                                    <option value="{{ $timezone }}" {{ config('app.timezone') === $timezone ? 'selected' : '' }}>
                                        {{ $timezone }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="app_url" class="form-label">URL de la Aplicación</label>
                            <input type="url" class="form-control" id="app_url" name="app_url" value="{{ config('app.url') }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="locale" class="form-label">Idioma Predeterminado</label>
                            <select class="form-select" id="locale" name="locale" required>
                                <option value="es" {{ config('app.locale') === 'es' ? 'selected' : '' }}>Español</option>
                                <option value="en" {{ config('app.locale') === 'en' ? 'selected' : '' }}>English</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="mb-3 form-check">
                    <input type="checkbox" class="form-check-input" id="maintenance_mode" name="maintenance_mode" {{ config('app.maintenance_mode') ? 'checked' : '' }}>
                    <label class="form-check-label" for="maintenance_mode">Modo Mantenimiento</label>
                    <div class="form-text">Cuando esté activado, solo los administradores podrán acceder al sistema.</div>
                </div>
            </form>
        </div>

        <!-- Pestaña de Correo Electrónico -->
        <div class="tab-pane fade" id="email" role="tabpanel">
            <form id="emailSettingsForm">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    Configura el servidor SMTP para habilitar el envío de notificaciones por correo electrónico.
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="mail_host" class="form-label">Servidor SMTP</label>
                            <input type="text" class="form-control" id="mail_host" name="mail_host" value="{{ config('mail.mailers.smtp.host', 'smtp.mailtrap.io') }}">
                        </div>
                        <div class="mb-3">
                            <label for="mail_port" class="form-label">Puerto</label>
                            <input type="number" class="form-control" id="mail_port" name="mail_port" value="{{ config('mail.mailers.smtp.port', 2525) }}">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="mail_username" class="form-label">Usuario</label>
                            <input type="text" class="form-control" id="mail_username" name="mail_username" value="{{ config('mail.mailers.smtp.username') }}">
                        </div>
                        <div class="mb-3">
                            <label for="mail_password" class="form-label">Contraseña</label>
                            <input type="password" class="form-control" id="mail_password" name="mail_password" placeholder="••••••••">
                            <div class="form-text">Dejar en blanco para no cambiar</div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="mail_from_address" class="form-label">Correo del Remitente</label>
                            <input type="email" class="form-control" id="mail_from_address" name="mail_from_address" value="{{ config('mail.from.address', 'noreply@mentorhub.com') }}">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="mail_from_name" class="form-label">Nombre del Remitente</label>
                            <input type="text" class="form-control" id="mail_from_name" name="mail_from_name" value="{{ config('mail.from.name', 'MentorHub') }}">
                        </div>
                    </div>
                </div>
                <button type="button" class="btn btn-outline-primary" id="testEmail">
                    <i class="fas fa-paper-plane me-1"></i> Probar Configuración
                </button>
            </form>
        </div>
        
        <!-- Pestaña de Registros de Actividad -->
        <div class="tab-pane fade" id="activity-logs" role="tabpanel">
            <form id="activityLogsSettingsForm">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    Configure cómo se gestionan los registros de actividad en el sistema.
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="activity_retention_days" class="form-label">Período de retención (días)</label>
                            <input type="number" class="form-control" id="activity_retention_days" name="activity_retention_days" value="90" min="1" max="365">
                            <div class="form-text">Número de días que se conservarán los registros antes de ser eliminados automáticamente.</div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Acciones a registrar</label>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="log_created" name="log_actions[]" value="created" checked>
                                <label class="form-check-label" for="log_created">Creación</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="log_updated" name="log_actions[]" value="updated" checked>
                                <label class="form-check-label" for="log_updated">Actualización</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="log_deleted" name="log_actions[]" value="deleted" checked>
                                <label class="form-check-label" for="log_deleted">Eliminación</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="log_login" name="log_actions[]" value="login" checked>
                                <label class="form-check-label" for="log_login">Inicio de sesión</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="log_logout" name="log_actions[]" value="logout" checked>
                                <label class="form-check-label" for="log_logout">Cierre de sesión</label>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Modelos a registrar</label>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="log_users" name="log_models[]" value="User" checked>
                                <label class="form-check-label" for="log_users">Usuarios</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="log_roles" name="log_models[]" value="Role" checked>
                                <label class="form-check-label" for="log_roles">Roles</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="log_permissions" name="log_models[]" value="Permission" checked>
                                <label class="form-check-label" for="log_permissions">Permisos</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="log_courses" name="log_models[]" value="Course" checked>
                                <label class="form-check-label" for="log_courses">Cursos</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="log_modules" name="log_models[]" value="Module" checked>
                                <label class="form-check-label" for="log_modules">Módulos</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="log_tutorials" name="log_models[]" value="Tutorial" checked>
                                <label class="form-check-label" for="log_tutorials">Tutoriales</label>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="cleanup_schedule" class="form-label">Programación de limpieza</label>
                            <select class="form-select" id="cleanup_schedule" name="cleanup_schedule">
                                <option value="daily">Diaria</option>
                                <option value="weekly" selected>Semanal</option>
                                <option value="monthly">Mensual</option>
                            </select>
                            <div class="form-text">Frecuencia con la que se ejecutará la limpieza automática de registros antiguos.</div>
                        </div>
                    </div>
                </div>
                
                <div class="mt-3">
                    <button type="button" class="btn btn-danger me-2" id="pruneLogs">
                        <i class="fas fa-trash me-1"></i> Limpiar Registros Antiguos
                    </button>
                    <button type="button" class="btn btn-secondary me-2" id="exportAllLogs">
                        <i class="fas fa-file-export me-1"></i> Exportar Todos los Registros
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Lógica para guardar la configuración
        document.getElementById('saveSettings').addEventListener('click', function() {
            // Implementar lógica de guardado
            alert('Configuración guardada correctamente');
        });

        // Lógica para restablecer la configuración
        document.getElementById('resetSettings').addEventListener('click', function() {
            if (confirm('¿Estás seguro de que deseas restablecer la configuración a los valores predeterminados?')) {
                // Implementar lógica de restablecimiento
                alert('Configuración restablecida');
            }
        });
        
        // Lógica para limpiar registros antiguos
        document.getElementById('pruneLogs').addEventListener('click', function() {
            const retentionDays = document.getElementById('activity_retention_days').value;
            if (confirm(`¿Estás seguro de que deseas eliminar todos los registros de actividad más antiguos que ${retentionDays} días? Esta acción no se puede deshacer.`)) {
                // Aquí se implementaría la llamada AJAX para ejecutar el comando de limpieza
                fetch('/admin/activity-logs/prune', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ days: retentionDays })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert(`Se han eliminado ${data.deleted} registros de actividad antiguos.`);
                    } else {
                        alert('Error al limpiar los registros: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error al procesar la solicitud.');
                });
            }
        });
        
        // Lógica para exportar todos los registros
        document.getElementById('exportAllLogs').addEventListener('click', function() {
            window.location.href = '/admin/activity-logs/export?format=xlsx';
        });
    });
</script>
@endpush
@endsection
