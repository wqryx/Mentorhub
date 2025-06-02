@extends('layouts.admin')

@section('title', 'Ver Mensaje')

@section('content')
<div class="container mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-semibold text-gray-900">Detalles del Mensaje</h1>
        <a href="{{ route('admin.messages.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:outline-none focus:border-gray-700 focus:ring focus:ring-gray-200 active:bg-gray-800 disabled:opacity-25 transition">
            <i class="fas fa-arrow-left mr-2"></i> Volver a la Lista
        </a>
    </div>

    <div class="bg-white shadow-md rounded-lg overflow-hidden p-6">
        <div class="border-b pb-4 mb-4">
            <div class="flex justify-between items-start">
                <div>
                    <h2 class="text-xl font-semibold text-gray-900">{{ $message->subject }}</h2>
                    <p class="text-sm text-gray-600 mt-1">
                        <span class="font-medium">De:</span> {{ $message->sender->name ?? 'Sistema' }}
                        <span class="ml-4 font-medium">Para:</span> {{ $message->recipient->name ?? 'Múltiples destinatarios' }}
                    </p>
                </div>
                <div class="text-sm text-gray-500">
                    {{ $message->created_at->format('d/m/Y H:i') }}
                </div>
            </div>
        </div>

        <div class="prose max-w-none">
            {!! nl2br(e($message->content)) !!}
        </div>

        @if($message->attachments && count($message->attachments) > 0)
        <div class="mt-6 border-t pt-4">
            <h3 class="text-lg font-medium text-gray-900 mb-2">Archivos Adjuntos</h3>
            <ul class="space-y-2">
                @foreach($message->attachments as $attachment)
                <li class="flex items-center">
                    <i class="fas fa-paperclip text-gray-500 mr-2"></i>
                    <a href="{{ route('admin.messages.download', ['message' => $message->id, 'attachment' => $attachment->id]) }}" class="text-blue-600 hover:text-blue-800">
                        {{ $attachment->filename }} ({{ $attachment->size_formatted }})
                    </a>
                </li>
                @endforeach
            </ul>
        </div>
        @endif

        <div class="mt-6 flex justify-between">
            <div>
                @if(!$message->read_at && $message->recipient_id == auth()->id())
                <form action="{{ route('admin.messages.mark-as-read', $message->id) }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="inline-flex items-center px-3 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:outline-none focus:border-green-700 focus:ring focus:ring-green-200 active:bg-green-800 disabled:opacity-25 transition">
                        <i class="fas fa-check mr-2"></i> Marcar como Leído
                    </button>
                </form>
                @endif
            </div>
            <div class="flex space-x-2">
                @if($message->sender_id != auth()->id() && $message->recipient_id == auth()->id())
                <a href="{{ route('admin.messages.reply', $message->id) }}" class="inline-flex items-center px-3 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:outline-none focus:border-blue-700 focus:ring focus:ring-blue-200 active:bg-blue-800 disabled:opacity-25 transition">
                    <i class="fas fa-reply mr-2"></i> Responder
                </a>
                @endif
                <form action="{{ route('admin.messages.destroy', $message->id) }}" method="POST" class="inline" onsubmit="return confirm('¿Estás seguro de que deseas eliminar este mensaje?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="inline-flex items-center px-3 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:outline-none focus:border-red-700 focus:ring focus:ring-red-200 active:bg-red-800 disabled:opacity-25 transition">
                        <i class="fas fa-trash mr-2"></i> Eliminar
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
