<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Setting;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Configuraciones generales
        $this->createGeneralSettings();
        
        // Configuraciones de email
        $this->createEmailSettings();
        
        // Configuraciones de registro
        $this->createRegistrationSettings();
        
        // Configuraciones de actividad
        $this->createActivitySettings();
    }
    
    /**
     * Crear configuraciones generales
     */
    private function createGeneralSettings(): void
    {
        Setting::set('site_name', 'MentorHub', 'Nombre del sitio web', 'general');
        Setting::set('site_description', 'Plataforma de mentoría y aprendizaje', 'Descripción corta del sitio', 'general');
        Setting::set('contact_email', 'contacto@mentorhub.com', 'Email de contacto principal', 'general');
        Setting::set('footer_text', '© ' . date('Y') . ' MentorHub - Todos los derechos reservados', 'Texto del pie de página', 'general');
    }
    
    /**
     * Crear configuraciones de email
     */
    private function createEmailSettings(): void
    {
        Setting::set('mail_driver', 'smtp', 'Driver de correo', 'email');
        Setting::set('mail_host', 'smtp.mailtrap.io', 'Host SMTP', 'email');
        Setting::set('mail_port', '2525', 'Puerto SMTP', 'email');
        Setting::set('mail_username', '', 'Usuario SMTP', 'email');
        Setting::set('mail_password', '', 'Contraseña SMTP', 'email');
        Setting::set('mail_encryption', 'tls', 'Encriptación SMTP', 'email');
    }
    
    /**
     * Crear configuraciones de registro
     */
    private function createRegistrationSettings(): void
    {
        Setting::set('enable_registration', '1', 'Permitir registro de nuevos usuarios', 'registration');
        Setting::set('email_verification', '1', 'Requerir verificación de email', 'registration');
        Setting::set('default_role', 'student', 'Rol por defecto para nuevos usuarios', 'registration');
        Setting::set('password_min_length', '8', 'Longitud mínima de contraseña', 'registration');
    }
    
    /**
     * Crear configuraciones de actividad
     */
    private function createActivitySettings(): void
    {
        Setting::set('enable_activity_log', '1', 'Habilitar registro de actividad', 'activity');
        Setting::set('log_retention_days', '30', 'Días de retención de logs', 'activity');
        Setting::set('log_login_attempts', '1', 'Registrar intentos de inicio de sesión', 'activity');
        Setting::set('log_admin_actions', '1', 'Registrar acciones de administradores', 'activity');
    }
}
