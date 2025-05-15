<?php

namespace App\Http\Controllers;

use App\Http\Requests\CourseRequest;
use App\Models\Category;
use App\Models\Course;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CourseController extends Controller
{
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->middleware('auth');
        // Solo los mentores y administradores pueden crear cursos
        $this->middleware('role:Mentor')->only(['create', 'store', 'edit', 'update', 'destroy']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Course::query();

        // Aplicar filtros si están presentes
        if ($request->has('search')) {
            $query->search($request->input('search'));
        }

        if ($request->has('category')) {
            $query->category($request->input('category'));
        }

        if ($request->has('difficulty')) {
            $query->difficultyLevel($request->input('difficulty'));
        }

        // Solo mostrar cursos publicados para usuarios normales
        if (!Auth::user()->isAdmin() && !Auth::user()->isMentor()) {
            $query->published();
        }

        // Organizar por más recientes
        $courses = $query->with(['user', 'category'])
                        ->orderBy('created_at', 'desc')
                        ->paginate(12)
                        ->withQueryString();

        $categories = Category::all();
        $difficultyLevels = ['principiante', 'intermedio', 'avanzado'];

        return view('courses.index', [
            'courses' => $courses,
            'categories' => $categories,
            'difficultyLevels' => $difficultyLevels,
            'filters' => $request->only(['search', 'category', 'difficulty'])
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::all();
        $tags = Tag::all();
        $difficultyLevels = ['principiante', 'intermedio', 'avanzado'];

        return view('courses.create', [
            'categories' => $categories,
            'tags' => $tags,
            'difficultyLevels' => $difficultyLevels
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CourseRequest $request)
    {
        try {
            // Crear el curso con los datos validados
            $courseData = $request->validated();
            $courseData['user_id'] = Auth::id();
            $courseData['slug'] = Str::slug($courseData['title']);
            $courseData['status'] = $request->input('status', 'draft');
            
            // Procesar imagen destacada si se proporciona
            if ($request->hasFile('featured_image')) {
                $path = $request->file('featured_image')->store('courses/featured', 'public');
                $courseData['featured_image'] = Storage::url($path);
            }

            // Procesar video promocional si se proporciona
            if ($request->hasFile('promotional_video')) {
                $path = $request->file('promotional_video')->store('courses/videos', 'public');
                $courseData['promotional_video_url'] = Storage::url($path);
            } elseif ($request->has('promotional_video_url')) {
                // Si es un URL de YouTube o Vimeo, lo guardamos directamente
                $courseData['promotional_video_url'] = $request->input('promotional_video_url');
            }

            $course = Course::create($courseData);

            // Asociar etiquetas si se proporcionan
            if ($request->has('tags')) {
                $course->tags()->attach($request->input('tags'));
            }

            return redirect()
                ->route('courses.show', $course)
                ->with('success', 'Curso creado exitosamente');

        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Error al crear el curso: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Course $course)
    {
        // Verificar si el curso es accesible
        if ($course->status !== 'published' && 
            Auth::id() !== $course->user_id && 
            !Auth::user()->isAdmin()) {
            abort(403, 'No tienes permisos para ver este curso');
        }

        // Cargar relaciones necesarias
        $course->load(['user', 'category', 'tags', 'modules.lessons', 'enrolledUsers']);

        // Verificar si el usuario está inscrito
        $isEnrolled = Auth::check() ? $course->enrolledUsers->contains(Auth::id()) : false;

        // Obtener cursos relacionados
        $relatedCourses = Course::where('category_id', $course->category_id)
            ->where('id', '!=', $course->id)
            ->published()
            ->limit(3)
            ->get();

        return view('courses.show', [
            'course' => $course,
            'isEnrolled' => $isEnrolled,
            'relatedCourses' => $relatedCourses
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Course $course)
    {
        // Verificar que el usuario sea el propietario o administrador
        if (Auth::id() !== $course->user_id && !Auth::user()->isAdmin()) {
            abort(403, 'No tienes permisos para editar este curso');
        }

        $categories = Category::all();
        $tags = Tag::all();
        $difficultyLevels = ['principiante', 'intermedio', 'avanzado'];
        
        $selectedTags = $course->tags->pluck('id')->toArray();

        return view('courses.edit', [
            'course' => $course,
            'categories' => $categories,
            'tags' => $tags,
            'selectedTags' => $selectedTags,
            'difficultyLevels' => $difficultyLevels
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CourseRequest $request, Course $course)
    {
        try {
            // Verificar que el usuario sea el propietario o administrador
            if (Auth::id() !== $course->user_id && !Auth::user()->isAdmin()) {
                abort(403, 'No tienes permisos para actualizar este curso');
            }

            // Actualizar el curso con los datos validados
            $courseData = $request->validated();
            
            // Actualizar el slug solo si cambió el título
            if ($request->has('title') && $course->title !== $request->input('title')) {
                $courseData['slug'] = Str::slug($courseData['title']);
            }
            
            // Procesar imagen destacada si se proporciona
            if ($request->hasFile('featured_image')) {
                // Eliminar imagen anterior si existe
                if ($course->featured_image) {
                    Storage::delete(str_replace('/storage/', 'public/', $course->featured_image));
                }
                
                $path = $request->file('featured_image')->store('courses/featured', 'public');
                $courseData['featured_image'] = Storage::url($path);
            }

            // Procesar video promocional si se proporciona
            if ($request->hasFile('promotional_video')) {
                // Eliminar video anterior si existe y es un archivo local
                if ($course->promotional_video_url && !Str::contains($course->promotional_video_url, ['youtube', 'vimeo'])) {
                    Storage::delete(str_replace('/storage/', 'public/', $course->promotional_video_url));
                }
                
                $path = $request->file('promotional_video')->store('courses/videos', 'public');
                $courseData['promotional_video_url'] = Storage::url($path);
            } elseif ($request->has('promotional_video_url')) {
                // Si es un URL de YouTube o Vimeo, lo guardamos directamente
                $courseData['promotional_video_url'] = $request->input('promotional_video_url');
            }

            $course->update($courseData);

            // Actualizar etiquetas si se proporcionan
            if ($request->has('tags')) {
                $course->tags()->sync($request->input('tags'));
            } else {
                $course->tags()->detach();
            }

            return redirect()
                ->route('courses.show', $course)
                ->with('success', 'Curso actualizado exitosamente');

        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Error al actualizar el curso: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Course $course)
    {
        try {
            // Verificar que el usuario sea el propietario o administrador
            if (Auth::id() !== $course->user_id && !Auth::user()->isAdmin()) {
                abort(403, 'No tienes permisos para eliminar este curso');
            }

            // Eliminar archivos asociados
            if ($course->featured_image) {
                Storage::delete(str_replace('/storage/', 'public/', $course->featured_image));
            }
            
            if ($course->promotional_video_url && !Str::contains($course->promotional_video_url, ['youtube', 'vimeo'])) {
                Storage::delete(str_replace('/storage/', 'public/', $course->promotional_video_url));
            }

            // Eliminar el curso y sus relaciones automáticamente mediante cascada
            $course->delete();

            return redirect()
                ->route('courses.index')
                ->with('success', 'Curso eliminado exitosamente');

        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Error al eliminar el curso: ' . $e->getMessage());
        }
    }
    
    /**
     * Enroll a user in a course.
     */
    public function enroll(Course $course)
    {
        // Verificar si el usuario ya está inscrito
        if ($course->enrolledUsers()->where('user_id', Auth::id())->exists()) {
            return redirect()
                ->route('courses.show', $course)
                ->with('info', 'Ya estás inscrito en este curso');
        }

        // Inscribir al usuario en el curso
        $course->enrolledUsers()->attach(Auth::id(), [
            'enrolled_at' => now(),
            'progress' => 0
        ]);

        return redirect()
            ->route('courses.show', $course)
            ->with('success', 'Te has inscrito exitosamente en el curso');
    }
    
    /**
     * Show the courses that a user is enrolled in.
     */
    public function myLearning()
    {
        $enrolledCourses = Auth::user()
            ->enrolledCourses()
            ->with(['user', 'category'])
            ->paginate(12);

        return view('courses.my-learning', [
            'enrolledCourses' => $enrolledCourses
        ]);
    }
}
