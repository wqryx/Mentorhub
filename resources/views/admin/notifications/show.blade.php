@extends('layouts.admin')

@section('title', 'Ver Notificación')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/admin-notification-detail.css') }}">
@endpush

@section('content')
<div class="container mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-semibold text-gray-900">Detalles de la Notificación</h1>
        <a href="{{ route('admin.notifications.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:outline-none focus:border-gray-700 focus:ring focus:ring-gray-200 active:bg-gray-800 disabled:opacity-25 transition">
            <i class="fas fa-arrow-left mr-2"></i> Volver a la Lista
        </a>
    </div>

    <div class="bg-white shadow-md rounded-lg overflow-hidden p-6">
        <div class="border-b pb-4 mb-4">
            <div class="flex justify-between items-start">
                <div>
                    <h2 class="text-xl font-semibold text-gray-900">{{ $notification->title }}</h2>
                    <p class="text-sm text-gray-600 mt-1">
                        <span class="font-medium">Tipo:</span> 
                        <span class="px-2 py-1 rounded-full text-xs 
                            @if($notification->type == 'info') bg-blue-100 text-blue-800 @endif
                            @if($notification->type == 'success') bg-green-100 text-green-800 @endif
                            @if($notification->type == 'warning') bg-yellow-100 text-yellow-800 @endif
                            @if($notification->type == 'error') bg-red-100 text-red-800 @endif
                        ">
                            {{ ucfirst($notification->type) }}
                        </span>
                    </p>
                </div>
                <div class="text-sm text-gray-500">
                    {{ $notification->created_at->format('d/m/Y H:i') }}
                </div>
            </div>
        </div>

        <div class="prose max-w-none mb-6">
            {!! nl2br(e($notification->message)) !!}
        </div>

        <div class="border-t pt-4">
            <h3 class="text-lg font-medium text-gray-900 mb-2">Información de Envío</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <p class="text-sm text-gray-600">
                        <span class="font-medium">Creado por:</span> 
                        {{ $notification->creator->name ?? 'Sistema' }}
                    </p>
                    <p class="text-sm text-gray-600">
                        <span class="font-medium">Estado:</span> 
                        @if($notification->is_sent)
                            <span class="text-green-600">Enviado</span>
                        @else
                            <span class="text-yellow-600">Pendiente</span>
                        @endif
                    </p>
                    @if($notification->is_sent && $notification->sent_at)
                    <p class="text-sm text-gray-600">
                        <span class="font-medium">Enviado el:</span> 
                        {{ $notification->sent_at->format('d/m/Y H:i') }}
                    </p>
                    @endif
                </div>
                <div>
                    <p class="text-sm text-gray-600 font-medium mb-1">Destinatarios:</p>
                    @if($notification->users->count() > 0)
                        <div class="max-h-40 overflow-y-auto">
                            <ul class="list-disc pl-5 text-sm text-gray-600">
                                @foreach($notification->users as $user)
                                    <li>{{ $user->name }} ({{ $user->email }})</li>
                                @endforeach
                            </ul>
                        </div>
                        <p class="text-xs text-gray-500 mt-1">Total: {{ $notification->users->count() }} destinatarios</p>
                    @else
                        <p class="text-sm text-gray-600">Sin destinatarios asignados</p>
                    @endif
                </div>
            </div>
        </div>

        <div class="mt-6 flex justify-between">
            <div>
                @if(!$notification->is_sent)
                <form action="{{ route('admin.notifications.send', $notification->id) }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="inline-flex items-center px-3 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:outline-none focus:border-green-700 focus:ring focus:ring-green-200 active:bg-green-800 disabled:opacity-25 transition">
                        <i class="fas fa-paper-plane mr-2"></i> Enviar Ahora
                    </button>
                </form>
                @endif
            </div>
            <div class="flex space-x-2">
                <a href="{{ route('admin.notifications.edit', $notification->id) }}" class="inline-flex items-center px-3 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:outline-none focus:border-blue-700 focus:ring focus:ring-blue-200 active:bg-blue-800 disabled:opacity-25 transition">
                    <i class="fas fa-edit mr-2"></i> Editar
                </a>
                <form action="{{ route('admin.notifications.destroy', $notification->id) }}" method="POST" class="inline" onsubmit="return confirm('¿Estás seguro de que deseas eliminar esta notificación?');">
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
