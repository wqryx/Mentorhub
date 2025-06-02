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
        $this->middleware('role:student');
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
}
