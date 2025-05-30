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
            ->withCount(['modules', 'students'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
        return view('dashboard.mentor.my_courses', compact('courses'));
    }

    /**
     * Muestra el formulario para crear un nuevo curso
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $specialities = Speciality::pluck('name', 'id');
        
        return view('dashboard.mentor.courses.create', compact('specialities'));
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
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'short_description' => 'required|string|max:255',
            'level' => 'required|in:beginner,intermediate,advanced',
            'price' => 'required|numeric|min:0',
            'is_published' => 'boolean',
            'is_featured' => 'boolean',
            'speciality_id' => 'required|exists:specialities,id',
            'image' => 'nullable|image|max:2048',
            'duration' => 'required|integer|min:1',
            'requirements' => 'nullable|string',
            'what_will_learn' => 'nullable|string',
        ]);
        
        $courseData = [
            'title' => $validated['title'],
            'slug' => Str::slug($validated['title']),
            'description' => $validated['description'],
            'short_description' => $validated['short_description'],
            'level' => $validated['level'],
            'price' => $validated['price'],
            'is_published' => $request->has('is_published'),
            'is_featured' => $request->has('is_featured'),
            'speciality_id' => $validated['speciality_id'],
            'creator_id' => Auth::id(),
            'duration' => $validated['duration'],
            'requirements' => $validated['requirements'] ?? null,
            'what_will_learn' => $validated['what_will_learn'] ?? null,
        ];
        
        // Manejar imagen del curso
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = Str::slug($validated['title']) . '-' . time() . '.' . $image->getClientOriginalExtension();
            $imagePath = $image->storeAs('courses', $imageName, 'public');
            $courseData['image'] = $imagePath;
        }
        
        $course = Course::create($courseData);
        
        return redirect()->route('mentor.courses')
            ->with('success', 'Curso creado correctamente');
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
            
        return view('dashboard.mentor.courses.show', compact('course'));
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
        
        return view('dashboard.mentor.courses.edit', compact('course', 'specialities'));
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
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'short_description' => 'required|string|max:255',
            'level' => 'required|in:beginner,intermediate,advanced',
            'price' => 'required|numeric|min:0',
            'is_published' => 'boolean',
            'is_featured' => 'boolean',
            'speciality_id' => 'required|exists:specialities,id',
            'image' => 'nullable|image|max:2048',
            'duration' => 'required|integer|min:1',
            'requirements' => 'nullable|string',
            'what_will_learn' => 'nullable|string',
        ]);
        
        $courseData = [
            'title' => $validated['title'],
            'slug' => Str::slug($validated['title']),
            'description' => $validated['description'],
            'short_description' => $validated['short_description'],
            'level' => $validated['level'],
            'price' => $validated['price'],
            'is_published' => $request->has('is_published'),
            'is_featured' => $request->has('is_featured'),
            'speciality_id' => $validated['speciality_id'],
            'duration' => $validated['duration'],
            'requirements' => $validated['requirements'] ?? null,
            'what_will_learn' => $validated['what_will_learn'] ?? null,
        ];
        
        // Manejar imagen del curso
        if ($request->hasFile('image')) {
            // Eliminar imagen anterior si existe
            if ($course->image) {
                Storage::disk('public')->delete($course->image);
            }
            
            $image = $request->file('image');
            $imageName = Str::slug($validated['title']) . '-' . time() . '.' . $image->getClientOriginalExtension();
            $imagePath = $image->storeAs('courses', $imageName, 'public');
            $courseData['image'] = $imagePath;
        }
        
        $course->update($courseData);
        
        return redirect()->route('mentor.courses')
            ->with('success', 'Curso actualizado correctamente');
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
        if ($course->image) {
            Storage::disk('public')->delete($course->image);
        }
        
        // Eliminar el curso y sus relaciones (módulos, tutoriales, contenidos)
        $course->delete();
        
        return redirect()->route('mentor.courses')
            ->with('success', 'Curso eliminado correctamente');
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
            
        return view('dashboard.mentor.courses.students', compact('course', 'students'));
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
            
        return view('dashboard.mentor.courses.statistics', compact(
            'course', 
            'completionRate', 
            'averageProgress', 
            'popularModules', 
            'popularTutorials'
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