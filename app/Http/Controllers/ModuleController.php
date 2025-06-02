<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Module;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ModuleController extends Controller
{
    /**
     * Show the form for creating a new module.
     */
    public function create(Course $course)
    {
        $this->authorize('update', $course);
        return view('modules.create', compact('course'));
    }

    /**
     * Store a newly created module in storage.
     */
    public function store(Request $request, Course $course)
    {
        $this->authorize('update', $course);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'order' => 'required|integer|min:1',
        ]);

        $course->modules()->create($validated);

        return redirect()->route('courses.show', $course)
            ->with('success', 'Module created successfully.');
    }

    /**
     * Show the form for editing the specified module.
     */
    public function edit(Course $course, Module $module)
    {
        $this->authorize('update', $course);
        return view('modules.edit', compact('course', 'module'));
    }

    /**
     * Update the specified module in storage.
     */
    public function update(Request $request, Course $course, Module $module)
    {
        $this->authorize('update', $course);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'order' => 'required|integer|min:1',
        ]);

        $module->update($validated);

        return redirect()->route('courses.show', $course)
            ->with('success', 'Module updated successfully.');
    }

    /**
     * Remove the specified module from storage.
     */
    public function destroy(Course $course, Module $module)
    {
        $this->authorize('update', $course);
        
        $module->delete();

        return redirect()->route('courses.show', $course)
            ->with('success', 'Module deleted successfully.');
    }
}
