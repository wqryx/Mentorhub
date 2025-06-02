<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Module;
use App\Models\Tutorial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TutorialController extends Controller
{
    /**
     * Show the form for creating a new tutorial.
     */
    public function create(Course $course, Module $module)
    {
        $this->authorize('update', $course);
        return view('tutorials.create', compact('course', 'module'));
    }

    /**
     * Store a newly created tutorial in storage.
     */
    public function store(Request $request, Course $course, Module $module)
    {
        $this->authorize('update', $course);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'order' => 'required|integer|min:1',
            'video_url' => 'nullable|url',
            'duration' => 'nullable|integer|min:0',
            'is_free' => 'boolean',
        ]);

        $module->tutorials()->create($validated);

        return redirect()->route('courses.modules.show', [$course, $module])
            ->with('success', 'Tutorial created successfully.');
    }

    /**
     * Show the form for editing the specified tutorial.
     */
    public function edit(Course $course, Module $module, Tutorial $tutorial)
    {
        $this->authorize('update', $course);
        return view('tutorials.edit', compact('course', 'module', 'tutorial'));
    }

    /**
     * Update the specified tutorial in storage.
     */
    public function update(Request $request, Course $course, Module $module, Tutorial $tutorial)
    {
        $this->authorize('update', $course);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'order' => 'required|integer|min:1',
            'video_url' => 'nullable|url',
            'duration' => 'nullable|integer|min:0',
            'is_free' => 'boolean',
        ]);

        $tutorial->update($validated);

        return redirect()->route('courses.modules.show', [$course, $module])
            ->with('success', 'Tutorial updated successfully.');
    }

    /**
     * Remove the specified tutorial from storage.
     */
    public function destroy(Course $course, Module $module, Tutorial $tutorial)
    {
        $this->authorize('update', $course);
        
        $tutorial->delete();

        return redirect()->route('courses.modules.show', [$course, $module])
            ->with('success', 'Tutorial deleted successfully.');
    }
}
