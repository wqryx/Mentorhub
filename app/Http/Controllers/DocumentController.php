<?php

namespace App\Http\Controllers;

use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DocumentController extends Controller
{
    /**
     * Display a listing of the user's documents.
     */
    public function index()
    {
        $user = Auth::user();
        $documents = $user->documents()->latest()->get();
        
        return view('documents.index', compact('documents'));
    }

    /**
     * Store a newly created document.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'file' => 'required|file|max:5120', // 5MB max
            'module_id' => 'required|exists:modules,id',
        ]);

        $user = Auth::user();
        
        // Generar nombre único para el archivo
        $filename = Str::slug($request->title) . '_' . time() . '.' . $request->file->getClientOriginalExtension();
        
        // Guardar archivo en storage
        $path = $request->file->storeAs('documents/' . $user->id, $filename);

        // Crear registro en la base de datos
        $document = $user->documents()->create([
            'title' => $request->title,
            'description' => $request->description,
            'module_id' => $request->module_id,
            'file_path' => $path,
        ]);

        return redirect()->route('documents.index')->with('success', 'Documento subido exitosamente');
    }

    /**
     * Display the specified document.
     */
    public function show(Document $document)
    {
        // Verificar que el usuario tiene acceso a este documento
        if ($document->user_id !== Auth::id()) {
            abort(403, 'No tienes acceso a este documento');
        }

        return view('documents.show', compact('document'));
    }
}
