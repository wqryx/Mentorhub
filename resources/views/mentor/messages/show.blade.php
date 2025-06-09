@extends('mentor.layouts.app')

@section('title', 'Mensaje - ' . $conversation->subject . ' - MentorHub')

@push('styles')
<style>
    .message {
        border-left: 4px solid transparent;
        transition: all 0.2s;
    }
    .message:hover {
        background-color: #f9fafb;
    }
    .message-unread {
        border-left-color: #3b82f6;
        background-color: #eff6ff;
    }
    .message-header {
        border-bottom: 1px solid #e5e7eb;
    }
    .message-content {
        line-height: 1.6;
    }
    .message-content img {
        max-width: 100%;
        height: auto;
    }
    .message-actions {
        opacity: 0;
        transition: opacity 0.2s;
    }
    .message:hover .message-actions {
        opacity: 1;
    }
</style>
@endpush

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-5xl mx-auto">
        <!-- Encabezado -->
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">{{ $conversation->subject }}</h1>
                <p class="text-gray-500">
                    Conversación con 
                    <span class="font-medium">{{ $otherUser->name }}</span>
                </p>
            </div>
            <a href="{{ route('mentor.messages') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition-colors duration-150 inline-flex items-center">
                <i class="fas fa-arrow-left mr-2"></i> Volver a mensajes
            </a>
        </div>

        <!-- Mensajes de la conversación -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
            <div class="divide-y divide-gray-200">
                @forelse($conversation->messages as $message)
                    <div class="message p-6 {{ !$message->read && $message->recipient_id === auth()->id() ? 'message-unread' : '' }}">
                        <div class="flex items-start justify-between mb-2">
                            <div class="flex items-center">
                                <img src="{{ $message->sender->profile_photo_url }}" 
                                     alt="{{ $message->sender->name }}" 
                                     class="w-10 h-10 rounded-full mr-3">
                                <div>
                                    <div class="font-medium text-gray-900">
                                        {{ $message->sender->name }}
                                        @if($message->sender->id === auth()->id())
                                            <span class="text-sm text-gray-500">(Tú)</span>
                                        @endif
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        {{ $message->created_at->diffForHumans() }}
                                    </div>
                                </div>
                            </div>
                            <div class="message-actions">
                                <button class="text-gray-400 hover:text-gray-600">
                                    <i class="fas fa-ellipsis-v"></i>
                                </button>
                            </div>
                        </div>
                        <div class="message-content pl-13 text-gray-700">
                            {!! $message->content !!}
                        </div>
                    </div>
                @empty
                    <div class="p-6 text-center text-gray-500">
                        No hay mensajes en esta conversación.
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Formulario de respuesta -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <form action="{{ route('mentor.messages.reply', $conversation) }}" method="POST">
                @csrf
                <div class="p-6">
                    <label for="reply-content" class="block text-sm font-medium text-gray-700 mb-2">
                        Responder a {{ $otherUser->name }}:
                    </label>
                    <textarea name="message" id="reply-content" rows="4" 
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50" 
                        placeholder="Escribe tu respuesta aquí..." required></textarea>
                    @error('message')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div class="bg-gray-50 px-6 py-3 flex justify-end">
                    <button type="submit" class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <i class="fas fa-paper-plane mr-2"></i> Enviar respuesta
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Desplazarse al final de la conversación
        const messagesContainer = document.querySelector('.divide-y');
        if (messagesContainer) {
            messagesContainer.scrollTop = messagesContainer.scrollHeight;
        }
        
        // Inicializar TinyMCE para el editor de respuestas
        tinymce.init({
            selector: '#reply-content',
            plugins: 'link lists table code',
            toolbar: 'undo redo | formatselect | bold italic backcolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | removeformat | link table code',
            menubar: false,
            height: 200,
            skin: 'oxide',
            content_css: 'default',
            statusbar: false,
            setup: function(editor) {
                editor.on('keydown', function(e) {
                    if (e.keyCode === 13 && !e.shiftKey) {
                        e.preventDefault();
                        const form = editor.formElement.closest('form');
                        if (form) form.submit();
                    }
                });
            }
        });
    });
</script>
@endpush
