<?php

namespace App\Http\Controllers\Mentor;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Module;
use App\Models\Tutorial;
use App\Models\Content;
use App\Models\Speciality;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CourseController extends Controller
{
    /**
     * Muestra la lista de cursos creados por el mentor
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $mentor = Auth::user();
        $courses = Course::where('creator_id', $mentor->id)
            ->withCount(['modules', 'students']) // Assuming 'students' is the correct relationship name for enrollments/students in a course
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
        return view('mentor.courses.index', compact('courses'));
    }

    /**
     * Muestra el formulario para crear un nuevo curso
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $specialities = Speciality::pluck('name', 'id');
        
        return view('mentor.courses.create', compact('specialities'));
    }

    /**
     * Almacena un nuevo curso en la base de datos
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:courses,code',
            'description' => 'nullable|string',
            'level' => 'required|in:Principiante,Intermedio,Avanzado',
            'credits' => 'nullable|integer|min:0',
            'hours_per_week' => 'nullable|integer|min:0',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'classroom' => 'nullable|string|max:255',
            'schedule' => 'nullable|string|max:255',
            'speciality_id' => 'nullable|exists:specialities,id',
            'course_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            // is_active is handled by $request->boolean('is_active') directly in $courseData
        ]);
        
        $courseData = [
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
            'code' => $validated['code'],
            'description' => $validated['description'] ?? null, // Ensure nullable fields are handled
            'level' => $validated['level'],
            'credits' => $validated['credits'] ?? null,
            'hours_per_week' => $validated['hours_per_week'] ?? null,
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'] ?? null,
            'classroom' => $validated['classroom'] ?? null,
            'schedule' => $validated['schedule'] ?? null,
            'speciality_id' => $validated['speciality_id'] ?? null,
            'creator_id' => Auth::id(),
            'is_active' => $request->boolean('is_active'),
        ];
        
        if ($request->hasFile('course_image')) {
            $image = $request->file('course_image');
            $safeName = Str::slug($validated['name']);
            $imageName = $safeName . '-' . time() . '.' . $image->getClientOriginalExtension();
            // Stores in storage/app/public/course_images. Ensure 'php artisan storage:link' was run.
            $imagePath = $image->storeAs('course_images', $imageName, 'public'); 
            $courseData['image_path'] = $imagePath; // Path is relative to the 'public' disk's root
        }
        
        $course = Course::create($courseData);
        
        return redirect()->route('mentor.courses.index')
            ->with('success', 'Curso creado correctamente.');
    }

    /**
     * Muestra un curso específico
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $mentor = Auth::user();
        $course = Course::where('creator_id', $mentor->id)
            ->with(['modules.tutorials', 'speciality', 'creator'])
            ->withCount('students')
            ->findOrFail($id);
            
        return view('mentor.courses.show', compact('course'));
    }

    /**
     * Muestra el formulario para editar un curso
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $mentor = Auth::user();
        $course = Course::where('creator_id', $mentor->id)
            ->findOrFail($id);
            
        $specialities = Speciality::pluck('name', 'id');
        
        return view('mentor.courses.edit', compact('course', 'specialities'));
    }

    /**
     * Actualiza un curso en la base de datos
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $mentor = Auth::user();
        $course = Course::where('creator_id', $mentor->id)
            ->findOrFail($id);
            
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:courses,code,' . $course->id,
            'description' => 'nullable|string',
            'level' => 'required|in:Principiante,Intermedio,Avanzado',
            'credits' => 'nullable|integer|min:0',
            'hours_per_week' => 'nullable|integer|min:0',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'classroom' => 'nullable|string|max:255',
            'schedule' => 'nullable|string|max:255',
            'speciality_id' => 'nullable|exists:specialities,id',
            'course_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
        
        $courseData = [
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
            'code' => $validated['code'],
            'description' => $validated['description'] ?? null,
            'level' => $validated['level'],
            'credits' => $validated['credits'] ?? null,
            'hours_per_week' => $validated['hours_per_week'] ?? null,
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'] ?? null,
            'classroom' => $validated['classroom'] ?? null,
            'schedule' => $validated['schedule'] ?? null,
            'speciality_id' => $validated['speciality_id'] ?? null,
            'is_active' => $request->boolean('is_active'),
        ];
        
        if ($request->hasFile('course_image')) {
            // Delete old image if it exists and is not null
            if ($course->image_path) {
                Storage::disk('public')->delete($course->image_path);
            }
            
            $image = $request->file('course_image');
            $safeName = Str::slug($validated['name']);
            $imageName = $safeName . '-' . time() . '.' . $image->getClientOriginalExtension();
            $imagePath = $image->storeAs('course_images', $imageName, 'public');
            $courseData['image_path'] = $imagePath;
        }
        
        $course->update($courseData);
        
        return redirect()->route('mentor.courses.index')
            ->with('success', 'Curso actualizado correctamente.');
    }

    /**
     * Elimina un curso de la base de datos
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $mentor = Auth::user();
        $course = Course::where('creator_id', $mentor->id)
            ->findOrFail($id);
            
        // Eliminar imagen si existe
        if ($course->image_path) {
            Storage::disk('public')->delete($course->image_path);
        }
        
        // Eliminar el curso y sus relaciones (módulos, tutoriales, contenidos)
        $course->delete();
        
        return redirect()->route('mentor.courses.index')
            ->with('success', 'Curso eliminado correctamente.');
    }
    
    /**
     * Muestra los estudiantes inscritos en un curso
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function students($id)
    {
        $mentor = Auth::user();
        $course = Course::where('creator_id', $mentor->id)
            ->findOrFail($id);
            
        $students = $course->students()
            ->withPivot(['progress', 'last_activity', 'completed_at'])
            ->orderBy('pivot_progress', 'desc')
            ->paginate(20);
            
        return view('mentor.courses.students', compact('course', 'students'));
    }
    
    /**
     * Muestra las estadísticas de un curso
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function statistics($id)
    {
        $mentor = Auth::user();
        $course = Course::where('creator_id', $mentor->id)
            ->withCount('students')
            ->findOrFail($id);
            
        // Obtener estadísticas de progreso
        $completionRate = $course->students()
            ->wherePivot('completed_at', '!=', null)
            ->count() / max(1, $course->students_count) * 100;
            
        $averageProgress = $course->students()
            ->avg('course_user.progress') ?? 0;
            
        // Obtener estadísticas de módulos más vistos
        $popularModules = Module::where('course_id', $course->id)
            ->withCount('views')
            ->orderBy('views_count', 'desc')
            ->take(5)
            ->get();
            
        // Obtener estadísticas de tutoriales más vistos
        $popularTutorials = Tutorial::whereHas('module', function($query) use ($course) {
                $query->where('course_id', $course->id);
            })
            ->withCount('views')
            ->orderBy('views_count', 'desc')
            ->take(5)
            ->get();
            
        // Obtener estudiantes recientes (últimos 5 que accedieron al curso)
        $recentStudents = $course->students()
            ->withPivot(['progress', 'last_activity', 'completed_at'])
            ->orderBy('course_user.updated_at', 'desc')
            ->take(5)
            ->get();
            
        // Obtener actividad reciente (ejemplo simplificado)
        $recentActivities = collect(); // Aquí deberías cargar actividades reales de tu sistema
        
        // Contar el número total de lecciones para calcular el progreso
        $totalLessons = $course->modules->sum(function($module) {
            return $module->tutorials->count();
        });
            
        return view('mentor.courses.statistics', compact(
            'course', 
            'completionRate', 
            'averageProgress', 
            'popularModules', 
            'popularTutorials',
            'recentStudents',
            'recentActivities',
            'totalLessons'
        ));
    }
    
    /**
     * Duplica un curso existente
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function duplicate($id)
    {
        $mentor = Auth::user();
        $originalCourse = Course::where('creator_id', $mentor->id)
            ->with(['modules.tutorials.contents'])
            ->findOrFail($id);
            
        // Crear copia del curso
        $newCourse = $originalCourse->replicate();
        $newCourse->title = 'Copia de ' . $originalCourse->title;
        $newCourse->slug = Str::slug($newCourse->title);
        $newCourse->is_published = false;
        $newCourse->created_at = now();
        $newCourse->updated_at = now();
        $newCourse->save();
        
        // Duplicar módulos, tutoriales y contenidos
        foreach ($originalCourse->modules as $module) {
            $newModule = $module->replicate();
            $newModule->course_id = $newCourse->id;
            $newModule->save();
            
            foreach ($module->tutorials as $tutorial) {
                $newTutorial = $tutorial->replicate();
                $newTutorial->module_id = $newModule->id;
                $newTutorial->save();
                
                foreach ($tutorial->contents as $content) {
                    $newContent = $content->replicate();
                    $newContent->tutorial_id = $newTutorial->id;
                    $newContent->save();
                }
            }
        }
        
        return redirect()->route('mentor.courses.edit', $newCourse->id)
            ->with('success', 'Curso duplicado correctamente. Puedes editarlo ahora.');
    }
}