@extends('layouts.admin')

@section('title', 'Editar Mensaje')

@section('content')
<div class="container mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-semibold text-gray-900">Editar Mensaje</h1>
        <a href="{{ route('admin.messages.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:outline-none focus:border-gray-700 focus:ring focus:ring-gray-200 active:bg-gray-800 disabled:opacity-25 transition">
            <i class="fas fa-arrow-left mr-2"></i> Volver a la Lista
        </a>
    </div>

    <div class="bg-white shadow-md rounded-lg overflow-hidden p-6">
        <form action="{{ route('admin.messages.update', $message->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="mb-4">
                <label for="recipient_info" class="block text-sm font-medium text-gray-700 mb-1">Destinatario</label>
                <div class="p-3 bg-gray-50 rounded-md">
                    @if($message->recipient)
                        <p class="text-gray-700">{{ $message->recipient->name }} ({{ $message->recipient->email }})</p>
                    @elseif($message->recipients && count($message->recipients) > 0)
                        <p class="text-gray-700">Múltiples destinatarios ({{ count($message->recipients) }})</p>
                    @else
                        <p class="text-gray-700">Todos los usuarios</p>
                    @endif
                </div>
                <p class="text-xs text-gray-500 mt-1">No se puede cambiar el destinatario de un mensaje existente.</p>
            </div>

            <div class="mb-4">
                <label for="subject" class="block text-sm font-medium text-gray-700 mb-1">Asunto</label>
                <input type="text" name="subject" id="subject" class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" required value="{{ old('subject', $message->subject) }}">
                @error('subject')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="content" class="block text-sm font-medium text-gray-700 mb-1">Contenido del Mensaje</label>
                <textarea name="content" id="content" rows="6" class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" required>{{ old('content', $message->content) }}</textarea>
                @error('content')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            @if($message->attachments && count($message->attachments) > 0)
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Archivos Adjuntos Actuales</label>
                <ul class="space-y-2 mb-2">
                    @foreach($message->attachments as $attachment)
                    <li class="flex items-center justify-between">
                        <div class="flex items-center">
                            <i class="fas fa-paperclip text-gray-500 mr-2"></i>
                            <span>{{ $attachment->filename }} ({{ $attachment->size_formatted }})</span>
                        </div>
                        <div class="flex items-center">
                            <a href="{{ route('admin.messages.download', ['message' => $message->id, 'attachment' => $attachment->id]) }}" class="text-blue-600 hover:text-blue-800 mr-3">
                                <i class="fas fa-download"></i>
                            </a>
                            <button type="button" onclick="document.getElementById('remove-attachment-{{ $attachment->id }}').submit();" class="text-red-600 hover:text-red-800">
                                <i class="fas fa-times"></i>
                            </button>
                            <form id="remove-attachment-{{ $attachment->id }}" action="{{ route('admin.messages.remove-attachment', ['message' => $message->id, 'attachment' => $attachment->id]) }}" method="POST" class="hidden">
                                @csrf
                                @method('DELETE')
                            </form>
                        </div>
                    </li>
                    @endforeach
                </ul>
            </div>
            @endif

            <div class="mb-4">
                <label for="attachments" class="block text-sm font-medium text-gray-700 mb-1">Añadir Archivos Adjuntos (opcional)</label>
                <input type="file" name="attachments[]" id="attachments" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100" multiple>
                <p class="text-xs text-gray-500 mt-1">Puede seleccionar múltiples archivos. Tamaño máximo: 10MB por archivo.</p>
                @error('attachments')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
                @error('attachments.*')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex justify-end">
                <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:outline-none focus:border-blue-700 focus:ring focus:ring-blue-200 active:bg-blue-800 disabled:opacity-25 transition">
                    <i class="fas fa-save mr-2"></i> Guardar Cambios
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
