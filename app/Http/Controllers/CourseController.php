<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Module;
use App\Models\Tutorial;
use App\Models\Content;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class CourseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $courses = Course::with('user')->latest()->paginate(10);
        return view('courses.index', compact('courses'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('courses.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'nullable|image|max:2048',
            'price' => 'required|numeric|min:0',
            'is_published' => 'boolean',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('courses', 'public');
        }

        $validated['user_id'] = Auth::id();
        $validated['is_published'] = $request->has('is_published');

        Course::create($validated);

        return redirect()->route('courses.index')
            ->with('success', 'Course created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Course $course)
    {
        $course->load(['modules.tutorials.contents']);
        return view('courses.show', compact('course'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Course $course)
    {
        $this->authorize('update', $course);
        return view('courses.edit', compact('course'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Course $course)
    {
        $this->authorize('update', $course);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'nullable|image|max:2048',
            'price' => 'required|numeric|min:0',
            'is_published' => 'boolean',
        ]);

        if ($request->hasFile('image')) {
            // Eliminar imagen anterior si existe
            if ($course->image) {
                Storage::disk('public')->delete($course->image);
            }
            $validated['image'] = $request->file('image')->store('courses', 'public');
        }

        $validated['is_published'] = $request->has('is_published');
        $course->update($validated);

        return redirect()->route('courses.show', $course)
            ->with('success', 'Course updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Course $course)
    {
        $this->authorize('delete', $course);

        // Eliminar imagen si existe
        if ($course->image) {
            Storage::disk('public')->delete($course->image);
        }

        $course->delete();

        return redirect()->route('courses.index')
            ->with('success', 'Course deleted successfully.');
    }

    /**
     * Toggle the publication status of the course.
     */
    public function togglePublish(Course $course)
    {
        $this->authorize('update', $course);
        
        $course->update([
            'is_published' => !$course->is_published
        ]);

        return back()->with('success', 'Course publication status updated.');
    }

    /**
     * Display the specified module.
     */
    public function showModule(Course $course, Module $module)
    {
        $module->load(['tutorials.contents']);
        return view('courses.modules.show', compact('course', 'module'));
    }

    /**
     * Display the specified tutorial.
     */
    public function showTutorial(Course $course, Module $module, Tutorial $tutorial)
    {
        $tutorial->load(['contents' => function($query) {
            $query->orderBy('order');
        }]);
        
        return view('courses.tutorials.show', compact('course', 'module', 'tutorial'));
    }

    /**
     * Display the specified content.
     */
    public function showContent(Course $course, Module $module, Tutorial $tutorial, Content $content)
    {
        return view('courses.contents.show', compact('course', 'module', 'tutorial', 'content'));
    }
}
