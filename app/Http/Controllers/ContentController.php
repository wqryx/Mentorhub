<?php

namespace App\Http\Controllers;

use App\Models\Content;
use App\Models\Course;
use App\Models\Module;
use App\Models\Tutorial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ContentController extends Controller
{
    /**
     * Show the form for creating a new content.
     */
    public function create(Course $course, Module $module, Tutorial $tutorial, $type)
    {
        $this->authorize('update', $course);
        
        $validTypes = ['text', 'video', 'file', 'code', 'quiz', 'iframe'];
        if (!in_array($type, $validTypes)) {
            abort(404);
        }
        
        return view('contents.create', compact('course', 'module', 'tutorial', 'type'));
    }

    /**
     * Store a newly created content in storage.
     */
    public function store(Request $request, Course $course, Module $module, Tutorial $tutorial)
    {
        $this->authorize('update', $course);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|in:text,video,file,code,quiz,iframe',
            'content' => 'required|string',
            'order' => 'required|integer|min:1',
            'file' => 'required_if:type,file|file|max:10240', // 10MB max
            'code_language' => 'required_if:type,code|string|max:50',
            'is_free' => 'boolean',
        ]);

        $contentData = [
            'title' => $validated['title'],
            'type' => $validated['type'],
            'content' => $validated['content'],
            'order' => $validated['order'],
            'is_free' => $request->has('is_free'),
            'meta' => [],
        ];

        if ($validated['type'] === 'file' && $request->hasFile('file')) {
            $path = $request->file('file')->store('contents/files', 'public');
            $contentData['content'] = $path;
            $contentData['meta'] = [
                'original_name' => $request->file('file')->getClientOriginalName(),
                'mime_type' => $request->file('file')->getMimeType(),
                'size' => $request->file('file')->getSize(),
            ];
        }

        if ($validated['type'] === 'code') {
            $contentData['meta'] = [
                'language' => $validated['code_language'],
            ];
        }

        $tutorial->contents()->create($contentData);

        return redirect()->route('courses.modules.tutorials.show', [$course, $module, $tutorial])
            ->with('success', 'Content created successfully.');
    }

    /**
     * Display the specified content.
     */
    public function show(Course $course, Module $module, Tutorial $tutorial, Content $content)
    {
        return view('contents.show', compact('course', 'module', 'tutorial', 'content'));
    }

    /**
     * Show the form for editing the specified content.
     */
    public function edit(Course $course, Module $module, Tutorial $tutorial, Content $content)
    {
        $this->authorize('update', $course);
        return view('contents.edit', compact('course', 'module', 'tutorial', 'content'));
    }

    /**
     * Update the specified content in storage.
     */
    public function update(Request $request, Course $course, Module $module, Tutorial $tutorial, Content $content)
    {
        $this->authorize('update', $course);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'order' => 'required|integer|min:1',
            'file' => 'nullable|file|max:10240', // 10MB max
            'code_language' => 'required_if:type,code|string|max:50',
            'is_free' => 'boolean',
        ]);

        $contentData = [
            'title' => $validated['title'],
            'content' => $validated['content'],
            'order' => $validated['order'],
            'is_free' => $request->has('is_free'),
        ];

        if ($content->type === 'file' && $request->hasFile('file')) {
            // Eliminar archivo anterior si existe
            if ($content->content) {
                Storage::disk('public')->delete($content->content);
            }
            
            $path = $request->file('file')->store('contents/files', 'public');
            $contentData['content'] = $path;
            $contentData['meta'] = array_merge($content->meta ?? [], [
                'original_name' => $request->file('file')->getClientOriginalName(),
                'mime_type' => $request->file('file')->getMimeType(),
                'size' => $request->file('file')->getSize(),
            ]);
        }

        if ($content->type === 'code') {
            $contentData['meta'] = array_merge($content->meta ?? [], [
                'language' => $validated['code_language'],
            ]);
        }

        $content->update($contentData);

        return redirect()->route('courses.modules.tutorials.show', [$course, $module, $tutorial])
            ->with('success', 'Content updated successfully.');
    }

    /**
     * Remove the specified content from storage.
     */
    public function destroy(Course $course, Module $module, Tutorial $tutorial, Content $content)
    {
        $this->authorize('update', $course);
        
        // Eliminar archivo si existe
        if ($content->type === 'file' && $content->content) {
            Storage::disk('public')->delete($content->content);
        }
        
        $content->delete();

        return redirect()->route('courses.modules.tutorials.show', [$course, $module, $tutorial])
            ->with('success', 'Content deleted successfully.');
    }
}
