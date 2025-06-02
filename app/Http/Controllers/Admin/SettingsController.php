<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Artisan;

class SettingsController extends Controller
{
    /**
     * Show the general settings page.
     *
     * @return \Illuminate\View\View
     */
    public function general()
    {
        return view('admin.settings.general', [
            'title' => 'Configuración General',
            'description' => 'Administra la configuración general de la aplicación',
            'settings' => [
                'site_name' => setting('site_name', config('app.name')),
                'site_description' => setting('site_description', config('app.description')),
                'contact_email' => setting('contact_email', config('mail.from.address')),
                'timezone' => setting('timezone', config('app.timezone')),
                'date_format' => setting('date_format', 'd/m/Y'),
                'time_format' => setting('time_format', 'H:i'),
            ]
        ]);
    }

    /**
     * Update general settings.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateGeneral(Request $request)
    {
        $validated = $request->validate([
            'site_name' => 'required|string|max:255',
            'site_description' => 'nullable|string|max:500',
            'contact_email' => 'required|email',
            'timezone' => 'required|timezone',
            'date_format' => 'required|string|in:d/m/Y,m/d/Y,Y-m-d',
            'time_format' => 'required|string|in:H:i,h:i A',
        ]);

        // Update settings in the database
        foreach ($validated as $key => $value) {
            setting([$key => $value])->save();
        }

        // Clear the application cache
        Artisan::call('config:clear');
        Artisan::call('cache:clear');

        return redirect()
            ->route('admin.settings.general')
            ->with('success', 'La configuración general ha sido actualizada exitosamente.');
    }

    /**
     * Show the notification settings page.
     *
     * @return \Illuminate\View\View
     */
    public function notifications()
    {
        return view('admin.settings.notifications', [
            'title' => 'Configuración de Notificaciones',
            'description' => 'Administra las preferencias de notificaciones del sistema',
            'settings' => [
                'enable_email_notifications' => setting('enable_email_notifications', true),
                'notification_email' => setting('notification_email', config('mail.from.address')),
                'notify_new_users' => setting('notify_new_users', true),
                'notify_new_courses' => setting('notify_new_courses', true),
                'notify_new_payments' => setting('notify_new_payments', true),
            ]
        ]);
    }

    /**
     * Update notification settings.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateNotifications(Request $request)
    {
        $validated = $request->validate([
            'enable_email' => 'sometimes|boolean',
            'notification_email' => 'required_if:enable_email,1|email|max:255',
            'new_user_notification' => 'sometimes|boolean',
            'new_course_notification' => 'sometimes|boolean',
            'new_payment_notification' => 'sometimes|boolean',
        ], [
            'notification_email.required_if' => 'El campo correo de notificación es obligatorio cuando las notificaciones por correo están habilitadas.',
        ]);

        // Map form field names to setting keys
        $settingsMap = [
            'enable_email' => 'enable_email_notifications',
            'notification_email' => 'notification_email',
            'new_user_notification' => 'notify_new_users',
            'new_course_notification' => 'notify_new_courses',
            'new_payment_notification' => 'notify_new_payments',
        ];

        // Update each setting
        foreach ($settingsMap as $field => $setting) {
            if (array_key_exists($field, $validated)) {
                setting([$setting => $validated[$field]])->save();
            }
        }

        // Clear the application cache
        Artisan::call('config:clear');
        Artisan::call('cache:clear');

        return redirect()
            ->route('admin.settings.notifications')
            ->with('success', 'La configuración de notificaciones ha sido actualizada exitosamente.');
    }

    /**
     * Show the appearance settings page.
     *
     * @return \Illuminate\View\View
     */
    public function appearance()
    {
        $themes = [
            'light' => 'Claro',
            'dark' => 'Oscuro',
            'system' => 'Sistema'
        ];
        
        // Get current theme from settings or use 'light' as default
        $currentTheme = setting('theme', 'light');
        
        // Get UI preferences
        $settings = [
            'enable_animations' => setting('enable_animations', true),
            'sidebar_collapsed' => setting('sidebar_collapsed', false),
            'header_fixed' => setting('header_fixed', true),
            'footer_fixed' => setting('footer_fixed', false),
            'sidebar_style' => setting('sidebar_style', 'default'),
            'navbar_style' => setting('navbar_style', 'default'),
        ];
        
        return view('admin.settings.appearance', [
            'title' => 'Apariencia',
            'description' => 'Personaliza la apariencia del panel de administración',
            'themes' => $themes,
            'current_theme' => $currentTheme,
            'settings' => $settings,
            'current_theme' => $currentTheme,
            'settings' => [
                'enable_animations' => session('ui.animations', true),
                'sidebar_collapsed' => session('ui.sidebar.collapsed', false),
                'header_fixed' => session('ui.header.fixed', true),
                'footer_fixed' => session('ui.footer.fixed', false),
            ]
        ]);
    }

    /**
     * Update appearance settings.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateAppearance(Request $request)
    {
        $validated = $request->validate([
            'theme' => 'required|in:light,dark,system',
            'enable_animations' => 'sometimes|boolean',
            'sidebar_collapsed' => 'sometimes|boolean',
            'header_fixed' => 'sometimes|boolean',
            'footer_fixed' => 'sometimes|boolean',
            'sidebar_style' => 'sometimes|in:default,compact,icon-only',
            'navbar_style' => 'sometimes|in:default,transparent,gradient',
            'primary_color' => 'sometimes|regex:/^#[a-f0-9]{6}$/i',
            'secondary_color' => 'sometimes|regex:/^#[a-f0-9]{6}$/i',
        ], [
            'theme.required' => 'Debes seleccionar un tema.',
            'primary_color.regex' => 'El color primario debe ser un código de color hexadecimal válido.',
            'secondary_color.regex' => 'El color secundario debe ser un código de color hexadecimal válido.',
        ]);

        // Handle theme setting
        if (isset($validated['theme'])) {
            setting(['theme' => $validated['theme']])->save();
            
            // Store in session for immediate effect
            session(['theme' => $validated['theme']]);
        }

        // Handle UI preferences
        $uiSettings = [
            'enable_animations' => $validated['enable_animations'] ?? false,
            'sidebar_collapsed' => $validated['sidebar_collapsed'] ?? false,
            'header_fixed' => $validated['header_fixed'] ?? true,
            'footer_fixed' => $validated['footer_fixed'] ?? false,
            'sidebar_style' => $validated['sidebar_style'] ?? 'default',
            'navbar_style' => $validated['navbar_style'] ?? 'default',
        ];

        // Save each UI setting
        foreach ($uiSettings as $key => $value) {
            setting([$key => $value])->save();
        }

        // Handle color scheme if provided
        if (isset($validated['primary_color'])) {
            setting(['primary_color' => $validated['primary_color']])->save();
        }
        
        if (isset($validated['secondary_color'])) {
            setting(['secondary_color' => $validated['secondary_color']])->save();
        }

        // Clear the application cache
        Artisan::call('config:clear');
        Artisan::call('cache:clear');
        Artisan::call('view:clear');

        // If this is an AJAX request, return JSON response
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Configuración de apariencia actualizada correctamente',
                'theme' => $validated['theme'],
                'ui' => [
                    'animations' => $validated['enable_animations'],
                    'sidebar' => ['collapsed' => $validated['sidebar_collapsed']],
                    'header' => ['fixed' => $validated['header_fixed']],
                    'footer' => ['fixed' => $validated['footer_fixed']]
                ]
            ]);
        }

        return redirect()
            ->route('admin.settings.appearance')
            ->with('success', 'Configuración de apariencia actualizada correctamente');
    }
    
    /**
     * Show the test settings page.
     *
     * @return \Illuminate\View\View
     */
    public function testSettings()
    {
        return view('admin.test-settings', [
            'title' => 'Test de Configuración',
            'description' => 'Página de prueba para el sistema de configuración'
        ]);
    }
    
    /**
     * Update a test setting.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateTestSetting(Request $request)
    {
        $validated = $request->validate([
            'key' => 'required|string|max:255',
            'value' => 'required|string|max:1000',
        ]);
        
        // Update the setting
        setting([$validated['key'] => $validated['value']])->save();
        
        return redirect()
            ->route('admin.settings.test')
            ->with('success', "Configuración '{$validated['key']}' actualizada correctamente con valor '{$validated['value']}'");
    }
}
