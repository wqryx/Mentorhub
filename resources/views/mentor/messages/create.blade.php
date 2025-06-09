@extends('mentor.layouts.app')

@section('title', 'Nuevo Mensaje - MentorHub')

@push('styles')
<style>
    .message-recipient {
        min-height: 42px;
    }
    .message-recipient .select2-container {
        width: 100% !important;
    }
    .message-recipient .select2-selection {
        height: 42px !important;
        border: 1px solid #d1d5db;
        border-radius: 0.375rem;
    }
    .message-recipient .select2-selection__rendered {
        line-height: 40px !important;
    }
    .message-recipient .select2-selection__arrow {
        height: 40px !important;
    }
    .tox-tinymce {
        border-radius: 0.375rem !important;
        border-color: #d1d5db !important;
    }
</style>
@endpush

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <!-- Encabezado -->
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Nuevo Mensaje</h1>
            <a href="{{ route('mentor.messages') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition-colors duration-150 inline-flex items-center">
                <i class="fas fa-arrow-left mr-2"></i> Volver a mensajes
            </a>
        </div>

        <!-- Mensajes de alerta -->
        @include('partials.alerts')

        <!-- Formulario de mensaje -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <form action="{{ route('mentor.messages.store') }}" method="POST">
                @csrf
                <div class="p-6 space-y-6">
                    <!-- Destinatario -->
                    <div>
                        <label for="recipient_id" class="block text-sm font-medium text-gray-700 mb-1">
                            Para:
                        </label>
                        <div class="message-recipient">
                            <select name="recipient_id" id="recipient_id" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50" required>
                                <option value="">Selecciona un destinatario</option>
                                @foreach($mentees as $mentee)
                                    <option value="{{ $mentee->id }}" {{ old('recipient_id') == $mentee->id ? 'selected' : '' }}>
                                        {{ $mentee->name }} ({{ $mentee->email }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        @error('recipient_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Asunto -->
                    <div>
                        <label for="subject" class="block text-sm font-medium text-gray-700 mb-1">
                            Asunto:
                        </label>
                        <input type="text" name="subject" id="subject" value="{{ old('subject') }}" 
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50" 
                            placeholder="Asunto del mensaje" required>
                        @error('subject')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Contenido del mensaje -->
                    <div>
                        <label for="content" class="block text-sm font-medium text-gray-700 mb-1">
                            Mensaje:
                        </label>
                        <textarea name="content" id="content" rows="10" 
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50" 
                            placeholder="Escribe tu mensaje aquí..." required>{{ old('content') }}</textarea>
                        @error('content')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Acciones del formulario -->
                <div class="bg-gray-50 px-6 py-3 flex justify-end space-x-3">
                    <a href="{{ route('mentor.messages') }}" class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Cancelar
                    </a>
                    <button type="submit" class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <i class="fas fa-paper-plane mr-2"></i> Enviar mensaje
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Inicializar Select2 para el selector de destinatarios
        $('#recipient_id').select2({
            placeholder: 'Buscar estudiante...',
            allowClear: true,
            width: '100%',
            dropdownParent: $('.message-recipient')
        });

        // Inicializar TinyMCE para el editor de texto enriquecido
        tinymce.init({
            selector: '#content',
            plugins: 'link lists table code',
            toolbar: 'undo redo | formatselect | bold italic backcolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | removeformat | link table code',
            menubar: false,
            height: 300,
            skin: 'oxide',
            content_css: 'default',
            statusbar: false,
            setup: function(editor) {
                editor.on('change', function() {
                    editor.save();
                });
            }
        });
    });
</script>
@endpush
