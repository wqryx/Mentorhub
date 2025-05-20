@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="bg-white rounded-lg shadow-lg p-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold">Mis Documentos</h1>
            <button onclick="document.getElementById('uploadModal').style.display = 'block'" 
                    class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                <i class="fas fa-upload mr-2"></i> Subir Documento
            </button>
        </div>

        @if($documents->isEmpty())
            <div class="bg-gray-50 p-4 rounded-lg">
                <p class="text-gray-600">No tienes documentos subidos aún.</p>
            </div>
        @else
            <div class="space-y-4">
                @foreach($documents as $document)
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <div class="flex justify-between items-center">
                            <div>
                                <h2 class="font-semibold">{{ $document->title }}</h2>
                                <p class="text-sm text-gray-500">{{ $document->created_at->format('d/m/Y') }}</p>
                            </div>
                            <a href="{{ route('documents.show', $document) }}" 
                               class="text-blue-600 hover:text-blue-800">
                                Ver Documento
                            </a>
                        </div>
                        <p class="mt-2 text-gray-600">{{ $document->description }}</p>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <!-- Modal para subir documento -->
    <div id="uploadModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3 text-center">
                <h3 class="text-lg font-medium leading-6 text-gray-900">Subir Documento</h3>
                <div class="mt-2 px-7 py-3">
                    <form action="{{ route('documents.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="space-y-4">
                            <div>
                                <label for="title" class="block text-sm font-medium text-gray-700">Título</label>
                                <input type="text" name="title" id="title" required 
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                            </div>
                            
                            <div>
                                <label for="description" class="block text-sm font-medium text-gray-700">Descripción</label>
                                <textarea name="description" id="description" rows="3"
                                          class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"></textarea>
                            </div>

                            <div>
                                <label for="module_id" class="block text-sm font-medium text-gray-700">Asignatura</label>
                                <select name="module_id" id="module_id" required 
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                    <option value="">Selecciona una asignatura</option>
                                    @foreach($modules as $module)
                                        <option value="{{ $module->id }}">{{ $module->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label for="file" class="block text-sm font-medium text-gray-700">Archivo</label>
                                <input type="file" name="file" id="file" required 
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                            </div>

                            <div class="flex justify-end space-x-4 mt-4">
                                <button type="button" onclick="document.getElementById('uploadModal').style.display = 'none'" 
                                        class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600">
                                    Cancelar
                                </button>
                                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                                    Subir
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
