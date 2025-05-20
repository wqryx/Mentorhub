@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="bg-white rounded-lg shadow-lg p-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold">{{ $document->title }}</h1>
            <a href="{{ route('documents.index') }}" 
               class="text-blue-600 hover:text-blue-800">
                <i class="fas fa-arrow-left mr-2"></i> Volver
            </a>
        </div>

        <div class="space-y-6">
            <div class="bg-gray-50 p-4 rounded-lg">
                <h2 class="font-semibold mb-2">Información del Documento</h2>
                <div class="space-y-2">
                    <p><strong>Fecha de Subida:</strong> {{ $document->created_at->format('d/m/Y') }}</p>
                    <p><strong>Asignatura:</strong> {{ $document->module->name }}</p>
                </div>
            </div>

            <div class="bg-gray-50 p-4 rounded-lg">
                <h2 class="font-semibold mb-2">Descripción</h2>
                <p class="text-gray-600">{{ $document->description }}</p>
            </div>

            <div class="bg-gray-50 p-4 rounded-lg">
                <h2 class="font-semibold mb-2">Descargar Documento</h2>
                <a href="{{ $document->file_url }}" 
                   class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    <i class="fas fa-download mr-2"></i> Descargar
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
