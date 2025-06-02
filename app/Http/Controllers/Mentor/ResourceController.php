<?php

namespace App\Http\Controllers\Mentor;

use App\Http\Controllers\Controller;
use App\Models\Resource;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ResourceController extends Controller
{
    /**
     * Display a listing of the resources.
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $mentor = Auth::user();
        $query = Resource::where('creator_id', $mentor->id);
        
        // Filtrar por tipo
        if ($request->has('type') && $request->type != 'all') {
            $query->where('type', $request->type);
        }
        
        // Filtrar por curso
        if ($request->has('course_id') && $request->course_id != 'all') {
            $query->where('course_id', $request->course_id);
        }
        
        // Filtrar por visibilidad
        if ($request->has('visibility')) {
            if ($request->visibility == 'public') {
                $query->where('is_public', true);
            } elseif ($request->visibility == 'private') {
                $query->where('is_public', false);
            }
        }
        
        // Filtrar por etiqueta
        if ($request->has('tag') && !empty($request->tag)) {
            $query->withTag($request->tag);
        }
        
        // Ordenar recursos
        $orderBy = $request->order_by ?? 'created_at';
        $orderDir = $request->order_dir ?? 'desc';
        $query->orderBy($orderBy, $orderDir);
        
        $resources = $query->paginate(10);
        
        // Obtener cursos del mentor para el filtro
        $courses = Course::where('creator_id', $mentor->id)
            ->pluck('title', 'id')
            ->toArray();
        
        // Tipos de recursos para el filtro
        $resourceTypes = [
            'document' => 'Documentos',
            'video' => 'Videos',
            'link' => 'Enlaces',
            'presentation' => 'Presentaciones',
            'exercise' => 'Ejercicios',
            'quiz' => 'Cuestionarios',
            'other' => 'Otros'
        ];
        
        return view('mentor.resources', compact(
            'resources',
            'courses',
            'resourceTypes',
            'request'
        ));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $mentor = Auth::user();
        $courses = Course::where('creator_id', $mentor->id)
            ->pluck('title', 'id');
            
        $resourceTypes = [
            'document' => 'Documentos',
            'video' => 'Videos',
            'link' => 'Enlaces',
            'presentation' => 'Presentaciones',
            'exercise' => 'Ejercicios',
            'quiz' => 'Cuestionarios',
            'other' => 'Otros'
        ];
        
        return view('mentor.resources.create', compact('courses', 'resourceTypes'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|string|in:document,video,link,presentation,exercise,quiz,other',
            'url' => 'required_if:type,link,video|nullable|url',
            'file' => 'required_if:type,document,presentation,exercise|nullable|file|max:10240',
            'course_id' => 'nullable|exists:courses,id',
            'is_public' => 'boolean',
            'is_premium' => 'boolean',
            'tags' => 'nullable|string',
        ]);
        
        $resourceData = [
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'type' => $validated['type'],
            'creator_id' => Auth::id(),
            'course_id' => $validated['course_id'] ?? null,
            'is_public' => $request->has('is_public'),
            'is_premium' => $request->has('is_premium'),
            'tags' => !empty($validated['tags']) ? explode(',', $validated['tags']) : [],
        ];
        
        // Manejar URL para recursos tipo enlace o video
        if (in_array($validated['type'], ['link', 'video']) && !empty($validated['url'])) {
            $resourceData['url'] = $validated['url'];
        }
        
        // Manejar archivo subido
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $fileName = Str::slug($validated['title']) . '-' . time() . '.' . $file->getClientOriginalExtension();
            $filePath = $file->storeAs('resources/' . Auth::id(), $fileName, 'public');
            $resourceData['file_path'] = $filePath;
        }
        
        $resource = Resource::create($resourceData);
        
        return redirect()->route('mentor.resources')
            ->with('success', 'Recurso creado correctamente');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $mentor = Auth::user();
        $resource = Resource::where('creator_id', $mentor->id)
            ->with(['course', 'comments.author'])
            ->findOrFail($id);
            
        // Incrementar contador de vistas
        $resource->incrementViewsCount();
        
        return view('mentor.resources.show', compact('resource'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $mentor = Auth::user();
        $resource = Resource::where('creator_id', $mentor->id)
            ->findOrFail($id);
            
        $courses = Course::where('creator_id', $mentor->id)
            ->pluck('title', 'id');
            
        $resourceTypes = [
            'document' => 'Documentos',
            'video' => 'Videos',
            'link' => 'Enlaces',
            'presentation' => 'Presentaciones',
            'exercise' => 'Ejercicios',
            'quiz' => 'Cuestionarios',
            'other' => 'Otros'
        ];
        
        return view('mentor.resources.edit', compact('resource', 'courses', 'resourceTypes'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $mentor = Auth::user();
        $resource = Resource::where('creator_id', $mentor->id)
            ->findOrFail($id);
            
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|string|in:document,video,link,presentation,exercise,quiz,other',
            'url' => 'required_if:type,link,video|nullable|url',
            'file' => 'nullable|file|max:10240',
            'course_id' => 'nullable|exists:courses,id',
            'is_public' => 'boolean',
            'is_premium' => 'boolean',
            'tags' => 'nullable|string',
        ]);
        
        $resourceData = [
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'type' => $validated['type'],
            'course_id' => $validated['course_id'] ?? null,
            'is_public' => $request->has('is_public'),
            'is_premium' => $request->has('is_premium'),
            'tags' => !empty($validated['tags']) ? explode(',', $validated['tags']) : [],
        ];
        
        // Manejar URL para recursos tipo enlace o video
        if (in_array($validated['type'], ['link', 'video']) && !empty($validated['url'])) {
            $resourceData['url'] = $validated['url'];
        }
        
        // Manejar archivo subido
        if ($request->hasFile('file')) {
            // Eliminar archivo anterior si existe
            if ($resource->file_path) {
                Storage::disk('public')->delete($resource->file_path);
            }
            
            $file = $request->file('file');
            $fileName = Str::slug($validated['title']) . '-' . time() . '.' . $file->getClientOriginalExtension();
            $filePath = $file->storeAs('resources/' . Auth::id(), $fileName, 'public');
            $resourceData['file_path'] = $filePath;
        }
        
        $resource->update($resourceData);
        
        return redirect()->route('mentor.resources')
            ->with('success', 'Recurso actualizado correctamente');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $mentor = Auth::user();
        $resource = Resource::where('creator_id', $mentor->id)
            ->findOrFail($id);
            
        // Eliminar archivo si existe
        if ($resource->file_path) {
            Storage::disk('public')->delete($resource->file_path);
        }
        
        $resource->delete();
        
        return redirect()->route('mentor.resources')
            ->with('success', 'Recurso eliminado correctamente');
    }

    /**
     * Download the specified resource.
     *
     * @param  int  $id
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function download($id)
    {
        $resource = Resource::findOrFail($id);
        
        // Verificar permisos
        if (!$resource->is_public && $resource->creator_id != Auth::id()) {
            abort(403, 'No tienes permiso para descargar este recurso');
        }
        
        // Verificar que exista el archivo
        if (!$resource->file_path || !Storage::disk('public')->exists($resource->file_path)) {
            abort(404, 'El archivo no existe');
        }
        
        // Incrementar contador de descargas
        $resource->incrementDownloadsCount();
        
        return Storage::disk('public')->download(
            $resource->file_path, 
            Str::slug($resource->title) . '.' . pathinfo($resource->file_path, PATHINFO_EXTENSION)
        );
    }
}
