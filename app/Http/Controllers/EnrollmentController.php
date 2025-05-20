<?php

namespace App\Http\Controllers;

use App\Models\Enrollment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class EnrollmentController extends Controller
{
    /**
     * Display the enrollment form.
     */
    public function index()
    {
        $user = Auth::user();
        
        // Obtener la información de inscripción existente
        $enrollment = $user->enrollment;
        
        // Obtener los datos del curso del usuario
        $course = $user->course;
        
        return view('enrollment.index', compact('enrollment', 'course'));
    }

    /**
     * Verify enrollment data.
     */
    public function verify(Request $request)
    {
        $request->validate([
            'course' => 'required|string',
            'cycle' => 'required|string',
            'academic_year' => 'required|string',
            'identification_type' => 'required|string',
            'identification_number' => 'required|string',
            'birth_date' => 'required|date',
            'phone' => 'required|string',
            'address' => 'required|string',
            'emergency_contact' => 'required|string',
            'emergency_phone' => 'required|string',
        ]);

        // Verificar si ya existe una inscripción
        $existingEnrollment = Enrollment::where('user_id', Auth::id())
            ->where('academic_year', $request->academic_year)
            ->first();

        if ($existingEnrollment) {
            return response()->json([
                'success' => false,
                'message' => 'Ya tienes una inscripción para este año académico.'
            ], 400);
        }

        return response()->json([
            'success' => true,
            'message' => 'Datos válidos'
        ]);
    }

    /**
     * Submit enrollment data.
     */
    public function submit(Request $request)
    {
        $request->validate([
            'course' => 'required|string',
            'cycle' => 'required|string',
            'academic_year' => 'required|string',
            'identification_type' => 'required|string',
            'identification_number' => 'required|string',
            'birth_date' => 'required|date',
            'phone' => 'required|string',
            'address' => 'required|string',
            'emergency_contact' => 'required|string',
            'emergency_phone' => 'required|string',
            'photo' => 'nullable|image|max:2048', // 2MB max
            'documents.*' => 'nullable|file|max:2048', // 2MB max por documento
        ]);

        DB::transaction(function () use ($request) {
            $user = Auth::user();
            
            // Actualizar datos del usuario
            $user->update([
                'course' => $request->course,
                'cycle' => $request->cycle,
                'academic_year' => $request->academic_year,
                'identification_type' => $request->identification_type,
                'identification_number' => $request->identification_number,
                'birth_date' => $request->birth_date,
                'phone' => $request->phone,
                'address' => $request->address,
                'emergency_contact' => $request->emergency_contact,
                'emergency_phone' => $request->emergency_phone,
            ]);

            // Subir foto si se proporciona
            if ($request->hasFile('photo')) {
                $photoPath = $request->photo->store('profiles', 'public');
                $user->update(['photo' => $photoPath]);
            }

            // Crear o actualizar inscripción
            $enrollment = $user->enrollment ?? new Enrollment();
            $enrollment->user_id = $user->id;
            $enrollment->academic_year = $request->academic_year;
            $enrollment->status = 'pendiente';
            $enrollment->save();

            // Subir documentos adicionales
            if ($request->hasFile('documents')) {
                foreach ($request->file('documents') as $document) {
                    $filePath = $document->store('documents/enrollment', 'public');
                    $enrollment->documents()->create([
                        'file_path' => $filePath,
                        'type' => 'enrollment',
                    ]);
                }
            }
        });

        return redirect()->route('dashboard.student')->with('success', 'Inscripción completada exitosamente');
    }
}
