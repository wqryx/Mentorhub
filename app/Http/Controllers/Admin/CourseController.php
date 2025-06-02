<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\User;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class CourseController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin']);
    }
    
    /**
     * Display a listing of the courses.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $query = Course::with(['instructor', 'category']);
        
        // Filtrar por título
        if ($request->has('title') && $request->title) {
            $query->where('title', 'like', '%' . $request->title . '%');
        }
        
        // Filtrar por categoría
        if ($request->has('category_id') && $request->category_id) {
            $query->where('category_id', $request->category_id);
        }
        
        // Filtrar por instructor
        if ($request->has('instructor_id') && $request->instructor_id) {
            $query->where('instructor_id', $request->instructor_id);
        }
        
        // Filtrar por estado
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }
        
        // Ordenar
        $orderBy = $request->input('order_by', 'created_at');
        $orderDir = $request->input('order_dir', 'desc');
        $query->orderBy($orderBy, $orderDir);
        
        $courses = $query->paginate(10);
        
        // Obtener categorías para el filtro
        $categories = Category::orderBy('name')->get();
        
        // Obtener instructores para el filtro
        $instructors = User::role('mentor')->orderBy('name')->get();
        
        return view('admin.courses.index', compact('courses', 'categories', 'instructors'));
    }

    /**
     * Show the form for creating a new course.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $categories = Category::orderBy('name')->get();
        $instructors = User::role('mentor')->orderBy('name')->get();
        
        return view('admin.courses.create', compact('categories', 'instructors'));
    }

    /**
     * Store a newly created course in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'instructor_id' => 'required|exists:users,id',
            'price' => 'required|numeric|min:0',
            'duration' => 'required|integer|min:1',
            'level' => 'required|in:beginner,intermediate,advanced',
            'status' => 'required|in:draft,published,archived',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'image' => 'nullable|image|max:2048',
        ]);
        
        // Generar slug único
        $slug = Str::slug($validated['title']);
        $count = Course::where('slug', $slug)->count();
        if ($count > 0) {
            $slug = $slug . '-' . ($count + 1);
        }
        
        // Manejar la carga de imagen
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('courses', 'public');
            $validated['image'] = $imagePath;
        }
        
        // Crear el curso
        $course = new Course($validated);
        $course->slug = $slug;
        $course->save();
        
        return redirect()->route('admin.courses.index')
            ->with('success', 'Curso creado exitosamente.');
    }

    /**
     * Display the specified course.
     *
     * @param  \App\Models\Course  $course
     * @return \Illuminate\View\View
     */
    public function show(Course $course)
    {
        $course->load(['instructor', 'category', 'lessons', 'enrollments.user']);
        
        return view('admin.courses.show', compact('course'));
    }

    /**
     * Show the form for editing the specified course.
     *
     * @param  \App\Models\Course  $course
     * @return \Illuminate\View\View
     */
    public function edit(Course $course)
    {
        $categories = Category::orderBy('name')->get();
        $instructors = User::role('mentor')->orderBy('name')->get();
        
        return view('admin.courses.edit', compact('course', 'categories', 'instructors'));
    }

    /**
     * Update the specified course in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Course  $course
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Course $course)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'instructor_id' => 'required|exists:users,id',
            'price' => 'required|numeric|min:0',
            'duration' => 'required|integer|min:1',
            'level' => 'required|in:beginner,intermediate,advanced',
            'status' => 'required|in:draft,published,archived',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'image' => 'nullable|image|max:2048',
        ]);
        
        // Actualizar slug si el título cambió
        if ($course->title !== $validated['title']) {
            $slug = Str::slug($validated['title']);
            $count = Course::where('slug', $slug)->where('id', '!=', $course->id)->count();
            if ($count > 0) {
                $slug = $slug . '-' . ($count + 1);
            }
            $course->slug = $slug;
        }
        
        // Manejar la carga de imagen
        if ($request->hasFile('image')) {
            // Eliminar imagen anterior si existe
            if ($course->image && Storage::disk('public')->exists($course->image)) {
                Storage::disk('public')->delete($course->image);
            }
            
            $imagePath = $request->file('image')->store('courses', 'public');
            $validated['image'] = $imagePath;
        }
        
        // Actualizar el curso
        $course->update($validated);
        
        return redirect()->route('admin.courses.index')
            ->with('success', 'Curso actualizado exitosamente.');
    }

    /**
     * Remove the specified course from storage.
     *
     * @param  \App\Models\Course  $course
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Course $course)
    {
        // Verificar si hay estudiantes inscritos
        $enrollmentsCount = $course->enrollments()->count();
        if ($enrollmentsCount > 0) {
            return redirect()->route('admin.courses.index')
                ->with('error', "No se puede eliminar el curso porque tiene {$enrollmentsCount} estudiantes inscritos.");
        }
        
        // Eliminar imagen si existe
        if ($course->image && Storage::disk('public')->exists($course->image)) {
            Storage::disk('public')->delete($course->image);
        }
        
        // Eliminar lecciones asociadas
        $course->lessons()->delete();
        
        // Eliminar el curso
        $course->delete();
        
        return redirect()->route('admin.courses.index')
            ->with('success', 'Curso eliminado exitosamente.');
    }
}
