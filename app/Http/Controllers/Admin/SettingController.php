<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class SettingController extends Controller
{
    /**
     * Mostrar la configuración general
     */
    public function general()
    {
        return view('admin.settings.general', [
            'title' => 'Configuración General',
            'settings' => [
                'site_name' => config('app.name'),
                'site_description' => config('app.description'),
                'contact_email' => config('mail.contact_email', ''),
                'timezone' => config('app.timezone'),
                'date_format' => config('app.date_format', 'd/m/Y'),
                'time_format' => config('app.time_format', 'H:i'),
            ]
        ]);
    }

    /**
     * Actualizar la configuración general
     */
    public function updateGeneral(Request $request)
    {
        $validated = $request->validate([
            'site_name' => 'required|string|max:255',
            'site_description' => 'nullable|string|max:500',
            'contact_email' => 'required|email',
            'timezone' => 'required|timezone',
            'date_format' => 'required|string',
            'time_format' => 'required|string',
        ]);

        // Actualizar configuración (en una aplicación real, se guardaría en la base de datos o archivo de configuración)
        config(['app.name' => $validated['site_name']]);
        // Agregar otras actualizaciones de configuración aquí

        return redirect()
            ->route('admin.settings.general')
            ->with('success', 'Configuración general actualizada correctamente');
    }

    /**
     * Mostrar configuración de notificaciones
     */
    public function notifications()
    {
        return view('admin.settings.notifications', [
            'title' => 'Configuración de Notificaciones',
            'settings' => [
                'enable_email_notifications' => session('notifications.email.enabled', true),
                'notification_email' => session('notifications.email.address', config('mail.from.address')),
                'notify_new_users' => session('notifications.events.new_user', true),
                'notify_new_orders' => session('notifications.events.new_course', true),
                'notify_comments' => session('notifications.events.new_payment', true),
            ]
        ]);
    }

    /**
     * Actualizar configuración de notificaciones
     */
    public function updateNotifications(Request $request)
    {
        $validated = $request->validate([
            'email_notifications' => 'sometimes|boolean',
            'notification_email' => 'required_if:email_notifications,1|email|nullable',
            'new_user_notification' => 'sometimes|boolean',
            'new_course_notification' => 'sometimes|boolean',
            'new_payment_notification' => 'sometimes|boolean',
        ]);

        // Actualizar configuración en la sesión
        session([
            'notifications.email.enabled' => (bool)($validated['email_notifications'] ?? false),
            'notifications.email.address' => $validated['notification_email'] ?? null,
            'notifications.events.new_user' => (bool)($validated['new_user_notification'] ?? false),
            'notifications.events.new_course' => (bool)($validated['new_course_notification'] ?? false),
            'notifications.events.new_payment' => (bool)($validated['new_payment_notification'] ?? false),
        ]);

        return redirect()
            ->route('admin.settings.notifications')
            ->with('success', 'Configuración de notificaciones actualizada correctamente');
    }

    /**
     * Mostrar configuración de apariencia
     */
    public function appearance()
    {
        $themes = [
            'light' => 'Claro',
            'dark' => 'Oscuro'
        ];
        
        // Obtener el tema actual de la sesión o usar 'light' como valor por defecto
        $currentTheme = session('theme', 'light');
        
        // Asegurarse de que el tema sea válido
        if (!in_array($currentTheme, ['light', 'dark'])) {
            $currentTheme = 'light';
            session(['theme' => 'light']);
        }
        
        return view('admin.settings.appearance', [
            'title' => 'Apariencia',
            'themes' => $themes,
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
     * Actualizar configuración de apariencia
     */
    public function updateAppearance(Request $request)
    {
        try {
            $validated = $request->validate([
                'theme' => 'required|in:light,dark',
                // Asegurarse de que el tema sea válido
                'theme' => function($attribute, $value, $fail) {
                    if (!in_array($value, ['light', 'dark'])) {
                        $fail('El tema seleccionado no es válido');
                    }
                },
                'enable_animations' => 'required|boolean',
                'sidebar_collapsed' => 'required|boolean',
                'header_fixed' => 'required|boolean',
                'footer_fixed' => 'required|boolean',
            ], [
                'theme.required' => 'El tema es obligatorio',
                'theme.in' => 'El tema seleccionado no es válido',
                '*.boolean' => 'El valor debe ser verdadero o falso',
                '*.required' => 'Este campo es obligatorio'
            ]);

            // Actualizar la configuración en la sesión
            session([
                'theme' => $validated['theme'],
                'ui.animations' => (bool)$validated['enable_animations'],
                'ui.sidebar.collapsed' => (bool)$validated['sidebar_collapsed'],
                'ui.header.fixed' => (bool)$validated['header_fixed'],
                'ui.footer.fixed' => (bool)$validated['footer_fixed']
            ]);

            // Aplicar cambios en tiempo real
            if ($validated['header_fixed']) {
                session(['ui.header.classes' => 'fixed top-0 right-0 left-0 z-40']);
            } else {
                session(['ui.header.classes' => '']);
            }

            // Forzar la guardada de la sesión
            $request->session()->save();

            $response = [
                'success' => true,
                'message' => 'Configuración de apariencia actualizada correctamente',
                'theme' => $validated['theme'],
                'ui' => [
                    'animations' => (bool)$validated['enable_animations'],
                    'sidebar' => ['collapsed' => (bool)$validated['sidebar_collapsed']],
                    'header' => ['fixed' => (bool)$validated['header_fixed']],
                    'footer' => ['fixed' => (bool)$validated['footer_fixed']]
                ]
            ];

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json($response);
            }

            return redirect()
                ->route('admin.settings.appearance')
                ->with('success', $response['message']);

        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error de validación',
                    'errors' => $e->errors()
                ], 422);
            }
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al actualizar la configuración: ' . $e->getMessage()
                ], 500);
            }
            return back()->with('error', 'Error al actualizar la configuración: ' . $e->getMessage());
        }
    }
}
