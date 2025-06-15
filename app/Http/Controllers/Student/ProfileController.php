<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        // El middleware de roles ha sido eliminado temporalmente
    }

    /**
     * Mostrar el formulario de edición de perfil del estudiante.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function edit()
    {
        $user = Auth::user();
        return view('student.profile.edit', compact('user'));
    }

    /**
     * Actualizar el perfil del estudiante.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    /**
     * Actualizar el avatar del usuario.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    /**
     * Actualizar las preferencias de notificaciones del usuario.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateNotificationPreferences(Request $request)
    {
        $validated = $request->validate([
            'email_notifications' => ['sometimes', 'boolean'],
            'push_notifications' => ['sometimes', 'boolean'],
            'mentor_updates' => ['sometimes', 'boolean'],
            'course_updates' => ['sometimes', 'boolean'],
            'assignment_reminders' => ['sometimes', 'boolean'],
            'event_reminders' => ['sometimes', 'boolean'],
        ]);

        // Obtener el usuario autenticado
        $user = Auth::user();
        
        // Actualizar las preferencias de notificaciones
        $user->notification_preferences = array_merge(
            (array) $user->notification_preferences,
            $validated
        );
        
        $user->save();

        return back()->with('success', 'Preferencias de notificaciones actualizadas correctamente.');
    }
    
    /**
     * Actualizar la configuración de privacidad del usuario.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updatePrivacySettings(Request $request)
    {
        $validated = $request->validate([
            'profile_visibility' => ['required', 'in:public,mentors,private'],
            'email_visibility' => ['required', 'in:public,mentors,private'],
            'activity_status' => ['required', 'in:online,away,invisible'],
            'show_last_seen' => ['sometimes', 'boolean'],
            'show_online_status' => ['sometimes', 'boolean'],
            'search_engine_indexing' => ['sometimes', 'boolean'],
        ]);

        // Obtener el usuario autenticado
        $user = Auth::user();
        
        // Actualizar la configuración de privacidad
        $user->privacy_settings = array_merge(
            (array) $user->privacy_settings,
            $validated
        );
        
        $user->save();

        return back()->with('success', 'Configuración de privacidad actualizada correctamente.');
    }
    
    /**
     * Actualizar la configuración de apariencia del usuario.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateAppearanceSettings(Request $request)
    {
        $validated = $request->validate([
            'theme' => ['required', 'in:light,dark,system'],
            'font_size' => ['sometimes', 'in:sm,base,lg,xl'],
            'density' => ['sometimes', 'in:compact,normal,comfortable'],
            'color_scheme' => ['sometimes', 'in:blue,indigo,purple,pink,red,orange,yellow,green,emerald,teal,cyan'],
            'sidebar_collapsed' => ['sometimes', 'boolean'],
            'show_sidebar_text' => ['sometimes', 'boolean'],
        ]);

        // Obtener el usuario autenticado
        $user = Auth::user();
        
        // Actualizar la configuración de apariencia
        $user->appearance_settings = array_merge(
            (array) $user->appearance_settings,
            $validated
        );
        
        $user->save();

        // Si se cambió el tema, guardar en la sesión para aplicación inmediata
        if ($request->has('theme')) {
            session(['theme' => $validated['theme']]);
        }

        return back()->with('success', 'Configuración de apariencia actualizada correctamente.');
    }
    
    /**
     * Actualizar la configuración de aprendizaje del usuario.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateLearningSettings(Request $request)
    {
        $validated = $request->validate([
            'default_language' => ['required', 'in:es,en,fr,de,pt'],
            'difficulty_level' => ['sometimes', 'in:beginner,intermediate,advanced'],
            'learning_goals' => ['sometimes', 'array'],
            'learning_goals.*' => ['string', 'max:255'],
            'preferred_learning_style' => ['sometimes', 'in:visual,auditory,reading_writing,kinesthetic'],
            'daily_goal_minutes' => ['sometimes', 'integer', 'min:10', 'max:240'],
            'weekly_reminder' => ['sometimes', 'boolean'],
            'course_updates_notification' => ['sometimes', 'boolean'],
            'new_resources_notification' => ['sometimes', 'boolean'],
        ]);

        // Obtener el usuario autenticado
        $user = Auth::user();
        
        // Actualizar la configuración de aprendizaje
        $user->learning_settings = array_merge(
            (array) $user->learning_settings,
            $validated
        );
        
        $user->save();

        // Actualizar el idioma de la aplicación si es necesario
        if ($request->has('default_language')) {
            app()->setLocale($validated['default_language']);
            session(['app_locale' => $validated['default_language']]);
        }

        return back()->with('success', 'Configuración de aprendizaje actualizada correctamente.');
    }

    /**
     * Actualizar el avatar del usuario.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateAvatar(Request $request)
    {
        $request->validate([
            'avatar' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $user = Auth::user();
        
        // Eliminar avatar anterior si existe
        if ($user->profile && $user->profile->avatar) {
            Storage::disk('public')->delete($user->profile->avatar);
        }
        
        // Guardar nuevo avatar
        $path = $request->file('avatar')->store('avatars', 'public');
        
        if ($user->profile) {
            $user->profile->update(['avatar' => $path]);
        } else {
            $user->profile()->create(['avatar' => $path]);
        }
        
        return response()->json([
            'success' => true,
            'avatar_url' => $user->fresh()->getAvatarUrl(),
            'message' => 'Avatar actualizado correctamente.'
        ]);
    }

    /**
     * Actualizar el perfil del estudiante.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    /**
     * Actualizar las preferencias del perfil del estudiante.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updatePreferences(Request $request)
    {
        $user = Auth::user();
        
        $validated = $request->validate([
            'email_notifications' => 'sometimes|boolean',
            'push_notifications' => 'sometimes|boolean',
            'sms_notifications' => 'sometimes|boolean',
            'notification_frequency' => 'sometimes|in:instantly,daily,weekly',
        ]);
        
        // Asegurarse de que los valores booleanos se guarden correctamente
        $preferences = [
            'email_notifications' => $request->boolean('email_notifications'),
            'push_notifications' => $request->boolean('push_notifications'),
            'sms_notifications' => $request->boolean('sms_notifications'),
            'notification_frequency' => $request->input('notification_frequency', 'daily'),
        ];
        
        // Actualizar o crear las preferencias del perfil
        if ($user->profile) {
            $user->profile->update(['preferences' => json_encode($preferences)]);
        } else {
            $user->profile()->create(['preferences' => json_encode($preferences)]);
        }
        
        return redirect()->back()->with('success', 'Preferencias actualizadas correctamente.');
    }

    /**
     * Actualizar el perfil del estudiante.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'bio' => 'nullable|string|max:1000',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'current_password' => 'nullable|required_with:password',
            'password' => 'nullable|string|min:8|confirmed',
        ]);
        
        // Verificar contraseña actual si se está cambiando
        if ($request->filled('current_password')) {
            if (!Hash::check($request->current_password, $user->password)) {
                return back()->withErrors(['current_password' => 'La contraseña actual no es correcta.']);
            }
        }
        
        // Actualizar datos básicos
        $user->name = $request->name;
        $user->email = $request->email;
        
        // Actualizar datos de perfil
        if (!$user->profile) {
            $user->profile()->create([
                'bio' => $request->bio,
                'phone' => $request->phone,
                'address' => $request->address,
            ]);
        } else {
            $user->profile->update([
                'bio' => $request->bio,
                'phone' => $request->phone,
                'address' => $request->address,
            ]);
        }
        
        // Actualizar avatar si se ha subido uno nuevo
        if ($request->hasFile('avatar')) {
            // Eliminar avatar anterior si existe
            if ($user->profile && $user->profile->avatar) {
                Storage::disk('public')->delete($user->profile->avatar);
            }
            
            // Guardar nuevo avatar
            $path = $request->file('avatar')->store('avatars', 'public');
            
            if ($user->profile) {
                $user->profile->avatar = $path;
                $user->profile->save();
            } else {
                $user->profile()->create(['avatar' => $path]);
            }
        }
        
        // Actualizar contraseña si se ha proporcionado una nueva
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }
        
        $user->save();
        
        return redirect()->route('student.profile')->with('success', 'Perfil actualizado correctamente.');
    }
    
    /**
     * Actualizar la contraseña del usuario.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = Auth::user();
        
        // Actualizar la contraseña
        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return response()->json([
            'message' => 'Contraseña actualizada correctamente.',
            'success' => true
        ]);
    }
}
