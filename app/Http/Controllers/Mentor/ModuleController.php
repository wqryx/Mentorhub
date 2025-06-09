<?php

namespace App\Http\Controllers\Mentor;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Module;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ModuleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \App\Models\Course  $course
     * @return \Illuminate\Http\Response
     */
    public function index(Course $course)
    {
        // Verify that the authenticated mentor is the creator of this course
        if ($course->creator_id !== Auth::id()) {
            abort(403, 'No tienes permiso para gestionar los módulos de este curso.');
        }

        $modules = $course->modules()->orderBy('order', 'asc')->get(); // Assuming an 'order' column for modules

        return view('mentor.courses.modules.index', compact('course', 'modules'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  \App\Models\Course  $course
     * @return \Illuminate\Http\Response
     */
    public function create(Course $course)
    {
        if ($course->creator_id !== Auth::id()) {
            abort(403, 'No tienes permiso para añadir módulos a este curso.');
        }

        // Calculate the next order number for the new module
        $nextOrder = $course->modules()->count() + 1;

        return view('mentor.courses.modules.create', compact('course', 'nextOrder'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Course  $course
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Course $course)
    {
        if ($course->creator_id !== Auth::id()) {
            abort(403, 'No tienes permiso para guardar módulos en este curso.');
        }

        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'order' => 'required|integer|min:1',
            'slug' => [
                'nullable',
                'string',
                'max:255',
                // Ensure slug is unique for this course's modules
                Rule::unique('modules')->where(function ($query) use ($course) {
                    return $query->where('course_id', $course->id);
                })
            ],
            'description' => 'nullable|string',
            'is_active' => 'nullable|boolean',
            'is_free' => 'nullable|boolean',
        ]);

        $slug = $validatedData['slug'] ?? Str::slug($validatedData['title']);
        // Ensure slug uniqueness again if it was auto-generated or if the initial validation passed due to it being nullable
        $originalSlug = $slug;
        $counter = 1;
        while ($course->modules()->where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $counter++;
        }

        $module = new Module();
        $module->course_id = $course->id;
        $module->title = $validatedData['title'];
        $module->slug = $slug;
        $module->order = $validatedData['order'];
        $module->description = $validatedData['description'];
        $module->is_active = $request->has('is_active'); // Handles checkbox value
        $module->is_free = $request->has('is_free');   // Handles checkbox value
        $module->save();

        return redirect()->route('mentor.courses.modules.index', $course->id)
                         ->with('success', 'Módulo "' . $module->title . '" creado exitosamente.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Course  $course
     * @param  \App\Models\Module  $module
     * @return \Illuminate\Http\Response
     */
    public function show(Course $course, Module $module)
    {
        if ($course->creator_id !== Auth::id() || $module->course_id !== $course->id) {
            abort(403, 'No tienes permiso para ver este módulo.');
        }
        // TODO: Return view for showing a module
        // return view('mentor.courses.modules.show', compact('course', 'module'));
        return response('Show module ' . $module->id . ' for course ' . $course->id . ' (Mentor) - Not Implemented Yet', 501);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Course  $course
     * @param  \App\Models\Module  $module
     * @return \Illuminate\Http\Response
     */
    public function edit(Course $course, Module $module)
    {
        if ($course->creator_id !== Auth::id() || $module->course_id !== $course->id) {
            abort(403, 'No tienes permiso para editar este módulo.');
        }

        return view('mentor.courses.modules.edit', compact('course', 'module'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Course  $course
     * @param  \App\Models\Module  $module
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Course $course, Module $module)
    {
        if ($course->creator_id !== Auth::id() || $module->course_id !== $course->id) {
            abort(403, 'No tienes permiso para actualizar este módulo.');
        }

        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'order' => 'required|integer|min:1',
            'slug' => [
                'nullable',
                'string',
                'max:255',
                // Ensure slug is unique for this course's modules, ignoring the current module
                Rule::unique('modules')->where(function ($query) use ($course) {
                    return $query->where('course_id', $course->id);
                })->ignore($module->id),
            ],
            'description' => 'nullable|string',
            'is_active' => 'nullable|boolean',
            'is_free' => 'nullable|boolean',
        ]);

        $slug = $validatedData['slug'] ?? Str::slug($validatedData['title']);
        // Ensure slug uniqueness again if it was auto-generated or if the initial validation passed due to it being nullable
        $originalSlug = $slug;
        $counter = 1;
        while ($course->modules()->where('slug', $slug)->where('id', '!=', $module->id)->exists()) {
            $slug = $originalSlug . '-' . $counter++;
        }

        $module->title = $validatedData['title'];
        $module->slug = $slug;
        $module->order = $validatedData['order'];
        $module->description = $validatedData['description'];
        $module->is_active = $request->has('is_active');
        $module->is_free = $request->has('is_free');
        $module->save();

        return redirect()->route('mentor.courses.modules.index', $course->id)
                         ->with('success', 'Módulo "' . $module->title . '" actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Course  $course
     * @param  \App\Models\Module  $module
     * @return \Illuminate\Http\Response
     */
    public function destroy(Course $course, Module $module)
    {
        if ($course->creator_id !== Auth::id() || $module->course_id !== $course->id) {
            abort(403, 'No tienes permiso para eliminar este módulo.');
        }
        // TODO: Implement destroy logic
        return response('Destroy module ' . $module->id . ' for course ' . $course->id . ' (Mentor) - Not Implemented Yet', 501);
    }
}
