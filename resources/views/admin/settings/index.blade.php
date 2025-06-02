@extends('layouts.admin')

@section('title', 'Configuración del Sistema')

@section('content')
<div class="container-fluid">
    <!-- Encabezado -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Configuración del Sistema</h1>
    </div>

    <!-- Formulario de configuración -->
    <div class="row">
        <!-- Configuración General -->
        <div class="col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Configuración General</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.settings.update') }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="section" value="general">
                        
                        <div class="mb-3">
                            <label for="site_name" class="form-label">Nombre del Sitio</label>
                            <input type="text" class="form-control" id="site_name" name="site_name" value="{{ $settings['site_name'] ?? 'MentorHub' }}">
                        </div>
                        
                        <div class="mb-3">
                            <label for="site_description" class="form-label">Descripción del Sitio</label>
                            <textarea class="form-control" id="site_description" name="site_description" rows="3">{{ $settings['site_description'] ?? 'Plataforma de mentoría y aprendizaje' }}</textarea>
                        </div>
                        
                        <div class="mb-3">
                            <label for="contact_email" class="form-label">Email de Contacto</label>
                            <input type="email" class="form-control" id="contact_email" name="contact_email" value="{{ $settings['contact_email'] ?? 'contacto@mentorhub.com' }}">
                        </div>
                        
                        <div class="mb-3">
                            <label for="footer_text" class="form-label">Texto del Pie de Página</label>
                            <input type="text" class="form-control" id="footer_text" name="footer_text" value="{{ $settings['footer_text'] ?? '© ' . date('Y') . ' MentorHub - Todos los derechos reservados' }}">
                        </div>
                        
                        <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                    </form>
                </div>
            </div>
        </div>
        
        <!-- Configuración de Email -->
        <div class="col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Configuración de Email</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.settings.update') }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="section" value="email">
                        
                        <div class="mb-3">
                            <label for="mail_driver" class="form-label">Driver de Email</label>
                            <select class="form-select" id="mail_driver" name="mail_driver">
                                <option value="smtp" {{ ($settings['mail_driver'] ?? '') == 'smtp' ? 'selected' : '' }}>SMTP</option>
                                <option value="sendmail" {{ ($settings['mail_driver'] ?? '') == 'sendmail' ? 'selected' : '' }}>Sendmail</option>
                                <option value="mailgun" {{ ($settings['mail_driver'] ?? '') == 'mailgun' ? 'selected' : '' }}>Mailgun</option>
                                <option value="ses" {{ ($settings['mail_driver'] ?? '') == 'ses' ? 'selected' : '' }}>Amazon SES</option>
                                <option value="log" {{ ($settings['mail_driver'] ?? '') == 'log' ? 'selected' : '' }}>Log</option>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label for="mail_host" class="form-label">Host SMTP</label>
                            <input type="text" class="form-control" id="mail_host" name="mail_host" value="{{ $settings['mail_host'] ?? 'smtp.mailtrap.io' }}">
                        </div>
                        
                        <div class="mb-3">
                            <label for="mail_port" class="form-label">Puerto SMTP</label>
                            <input type="number" class="form-control" id="mail_port" name="mail_port" value="{{ $settings['mail_port'] ?? '2525' }}">
                        </div>
                        
                        <div class="mb-3">
                            <label for="mail_username" class="form-label">Usuario SMTP</label>
                            <input type="text" class="form-control" id="mail_username" name="mail_username" value="{{ $settings['mail_username'] ?? '' }}">
                        </div>
                        
                        <div class="mb-3">
                            <label for="mail_password" class="form-label">Contraseña SMTP</label>
                            <input type="password" class="form-control" id="mail_password" name="mail_password" value="{{ $settings['mail_password'] ?? '' }}">
                        </div>
                        
                        <div class="mb-3">
                            <label for="mail_encryption" class="form-label">Encriptación SMTP</label>
                            <select class="form-select" id="mail_encryption" name="mail_encryption">
                                <option value="" {{ ($settings['mail_encryption'] ?? '') == '' ? 'selected' : '' }}>Ninguna</option>
                                <option value="tls" {{ ($settings['mail_encryption'] ?? '') == 'tls' ? 'selected' : '' }}>TLS</option>
                                <option value="ssl" {{ ($settings['mail_encryption'] ?? '') == 'ssl' ? 'selected' : '' }}>SSL</option>
                            </select>
                        </div>
                        
                        <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <!-- Configuración de Registro -->
        <div class="col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Configuración de Registro</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.settings.update') }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="section" value="registration">
                        
                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="enable_registration" name="enable_registration" {{ ($settings['enable_registration'] ?? '1') == '1' ? 'checked' : '' }}>
                            <label class="form-check-label" for="enable_registration">Habilitar registro de usuarios</label>
                        </div>
                        
                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="email_verification" name="email_verification" {{ ($settings['email_verification'] ?? '1') == '1' ? 'checked' : '' }}>
                            <label class="form-check-label" for="email_verification">Requerir verificación de email</label>
                        </div>
                        
                        <div class="mb-3">
                            <label for="default_role" class="form-label">Rol por defecto</label>
                            <select class="form-select" id="default_role" name="default_role">
                                <option value="estudiante" {{ ($settings['default_role'] ?? 'estudiante') == 'estudiante' ? 'selected' : '' }}>Estudiante</option>
                                <option value="mentor" {{ ($settings['default_role'] ?? '') == 'mentor' ? 'selected' : '' }}>Mentor</option>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label for="password_min_length" class="form-label">Longitud mínima de contraseña</label>
                            <input type="number" class="form-control" id="password_min_length" name="password_min_length" value="{{ $settings['password_min_length'] ?? '8' }}" min="6" max="20">
                        </div>
                        
                        <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                    </form>
                </div>
            </div>
        </div>
        
        <!-- Configuración de Registro de Actividad -->
        <div class="col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Configuración de Registro de Actividad</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.settings.update') }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="section" value="activity">
                        
                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="enable_activity_log" name="enable_activity_log" {{ ($settings['enable_activity_log'] ?? '1') == '1' ? 'checked' : '' }}>
                            <label class="form-check-label" for="enable_activity_log">Habilitar registro de actividad</label>
                        </div>
                        
                        <div class="mb-3">
                            <label for="log_retention_days" class="form-label">Días de retención de registros</label>
                            <input type="number" class="form-control" id="log_retention_days" name="log_retention_days" value="{{ $settings['log_retention_days'] ?? '90' }}" min="1" max="365">
                        </div>
                        
                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="log_login_attempts" name="log_login_attempts" {{ ($settings['log_login_attempts'] ?? '1') == '1' ? 'checked' : '' }}>
                            <label class="form-check-label" for="log_login_attempts">Registrar intentos de inicio de sesión</label>
                        </div>
                        
                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="log_admin_actions" name="log_admin_actions" {{ ($settings['log_admin_actions'] ?? '1') == '1' ? 'checked' : '' }}>
                            <label class="form-check-label" for="log_admin_actions">Registrar acciones de administrador</label>
                        </div>
                        
                        <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
