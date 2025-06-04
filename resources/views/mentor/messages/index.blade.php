@extends('mentor.layouts.app')

@section('title', 'Mensajes - MentorHub')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/mentor-messages.css') }}">
@endpush

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Encabezado -->
    <div class="flex flex-wrap items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Mensajes</h1>
        <a href="{{ route('mentor.messages.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors duration-150 inline-flex items-center">
            <i class="fas fa-paper-plane mr-2"></i> Nuevo Mensaje
        </a>
    </div>

    <!-- Mensajes de alerta -->
    @include('partials.alerts')

    <!-- Pestañas de navegación -->
    <div class="border-b border-gray-200 mb-6">
        <ul class="flex flex-wrap -mb-px" id="messagesTabs" role="tablist">
            <li class="mr-2" role="presentation">
                <button class="inline-block p-4 border-b-2 border-blue-600 rounded-t-lg text-blue-600" id="inbox-tab" data-bs-toggle="tab" data-bs-target="#inbox" type="button" role="tab" aria-controls="inbox" aria-selected="true">
                    <i class="fas fa-inbox mr-2"></i> Bandeja de entrada
                    @if($unreadCount > 0)
                        <span class="ml-1 px-2 py-1 text-xs font-semibold text-white bg-red-600 rounded-full">{{ $unreadCount }}</span>
                    @endif
                </button>
            </li>
            <li class="mr-2" role="presentation">
                <button class="inline-block p-4 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300" id="sent-tab" data-bs-toggle="tab" data-bs-target="#sent" type="button" role="tab" aria-controls="sent" aria-selected="false">
                    <i class="fas fa-paper-plane mr-2"></i> Enviados
                </button>
            </li>
        </ul>
    </div>

    <!-- Contenido de las pestañas -->
    <div class="tab-content" id="messagesTabsContent">
        <!-- Pestaña de Bandeja de entrada -->
        <div class="tab-pane fade show active" id="inbox" role="tabpanel">
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="p-6">
                    @if($receivedMessages->isEmpty())
                        <div class="p-4 mb-4 text-blue-700 bg-blue-100 rounded-lg flex items-start">
                            <i class="fas fa-info-circle mt-1 mr-3"></i>
                            <span>No tienes mensajes en tu bandeja de entrada.</span>
                        </div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm text-left text-gray-700" id="inboxTable">
                                <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-3 w-[5%]"></th>
                                        <th class="px-4 py-3 w-[20%]">Remitente</th>
                                        <th class="px-4 py-3 w-[55%]">Asunto</th>
                                        <th class="px-4 py-3 w-[15%]">Fecha</th>
                                        <th class="px-4 py-3 w-[5%]">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($receivedMessages as $message)
                                        <tr class="{{ !$message->read ? 'bg-blue-50 font-semibold' : 'hover:bg-gray-50' }} border-b">
                                            <td class="px-4 py-3 text-center">
                                                @if(!$message->read)
                                                    <span class="text-blue-600">
                                                        <i class="fas fa-circle fa-xs"></i>
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="px-4 py-3">
                                                <div class="flex items-center">
                                                    <img src="{{ $message->sender->profile_photo_url }}" 
                                                         class="rounded-full mr-3" 
                                                         width="40" 
                                                         height="40" 
                                                         alt="{{ $message->sender->name }}">
                                                    <div>
                                                        <div>{{ $message->sender->name }}</div>
                                                        <div class="text-xs text-gray-500">{{ $message->sender->role }}</div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-4 py-3">
                                                <a href="{{ route('mentor.messages.show', $message->id) }}" class="hover:text-blue-600">
                                                    <div class="font-medium">{{ $message->subject }}</div>
                                                    <p class="text-gray-500 text-sm mt-1">
                                                        {{ Str::limit($message->content, 100) }}
                                                    </p>
                                                </a>
                                            </td>
                                            <td class="px-4 py-3 text-gray-500 text-sm">{{ $message->created_at->format('d/m/Y H:i') }}</td>
                                            <td class="px-4 py-3">
                                                <div class="dropdown">
                                                    <button class="p-1 rounded-full hover:bg-gray-200 transition-colors duration-150" type="button" id="dropdownMenuButton{{ $message->id }}" data-bs-toggle="dropdown" aria-expanded="false">
                                                        <i class="fas fa-ellipsis-v text-gray-500"></i>
                                                    </button>
                                                    <ul class="dropdown-menu dropdown-menu-end shadow-lg rounded-md py-1" aria-labelledby="dropdownMenuButton{{ $message->id }}">
                                                        <li>
                                                            <a class="dropdown-item px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" href="{{ route('mentor.messages.show', $message->id) }}">
                                                                <i class="fas fa-eye mr-2"></i> Ver
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a class="dropdown-item px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" href="{{ route('mentor.messages.reply', $message->id) }}">
                                                                <i class="fas fa-reply mr-2"></i> Responder
                                                            </a>
                                                        </li>
                                                        <li><hr class="dropdown-divider mx-2 my-1 border-gray-200"></li>
                                                        <li>
                                                            <form action="{{ route('mentor.messages.delete', $message->id) }}" method="POST">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="dropdown-item px-4 py-2 text-sm text-red-600 hover:bg-gray-100 w-full text-left">
                                                                    <i class="fas fa-trash-alt mr-2"></i> Eliminar
                                                                </button>
                                                            </form>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Pestaña de Enviados -->
        <div class="tab-pane fade" id="sent" role="tabpanel">
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="p-6">
                    @if($sentMessages->isEmpty())
                        <div class="p-4 mb-4 text-blue-700 bg-blue-100 rounded-lg flex items-start">
                            <i class="fas fa-info-circle mt-1 mr-3"></i>
                            <span>No has enviado ningún mensaje.</span>
                        </div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm text-left text-gray-700" id="sentTable">
                                <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-3 w-[20%]">Destinatario</th>
                                        <th class="px-4 py-3 w-[60%]">Asunto</th>
                                        <th class="px-4 py-3 w-[15%]">Fecha</th>
                                        <th class="px-4 py-3 w-[5%]">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($sentMessages as $message)
                                        <tr class="hover:bg-gray-50 border-b">
                                            <td class="px-4 py-3">
                                                <div class="flex items-center">
                                                    <img src="{{ $message->recipient->profile_photo_url }}" 
                                                         class="rounded-full mr-3" 
                                                         width="40" 
                                                         height="40" 
                                                         alt="{{ $message->recipient->name }}">
                                                    <div>
                                                        <div>{{ $message->recipient->name }}</div>
                                                        <div class="text-xs text-gray-500">{{ $message->recipient->role }}</div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-4 py-3">
                                                <a href="{{ route('mentor.messages.show', $message->id) }}" class="hover:text-blue-600">
                                                    <div class="font-medium">{{ $message->subject }}</div>
                                                    <p class="text-gray-500 text-sm mt-1">
                                                        {{ Str::limit(strip_tags($message->content), 100) }}
                                                    </p>
                                                </a>
                                            </td>
                                            <td class="px-4 py-3 text-gray-500 text-sm">{{ $message->created_at->format('d/m/Y H:i') }}</td>
                                            <td class="px-4 py-3">
                                                <div class="dropdown">
                                                    <button class="p-1 rounded-full hover:bg-gray-200 transition-colors duration-150" type="button" id="dropdownMenuButton{{ $message->id }}" data-bs-toggle="dropdown" aria-expanded="false">
                                                        <i class="fas fa-ellipsis-v text-gray-500"></i>
                                                    </button>
                                                    <ul class="dropdown-menu dropdown-menu-end shadow-lg rounded-md py-1" aria-labelledby="dropdownMenuButton{{ $message->id }}">
                                                        <li>
                                                            <a class="dropdown-item px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" href="{{ route('mentor.messages.show', $message->id) }}">
                                                                <i class="fas fa-eye mr-2"></i> Ver
                                                            </a>
                                                        </li>
                                                        <li><hr class="dropdown-divider mx-2 my-1 border-gray-200"></li>
                                                        <li>
                                                            <form action="{{ route('mentor.messages.destroy', $message->id) }}" method="POST">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="dropdown-item px-4 py-2 text-sm text-red-600 hover:bg-gray-100 w-full text-left">
                                                                    <i class="fas fa-trash-alt mr-2"></i> Eliminar
                                                                </button>
                                                            </form>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Inicializar DataTables con clases Tailwind
        $('#inboxTable, #sentTable').DataTable({
            language: {
                url: '//cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json'
            },
            order: [[3, 'desc']],
            columnDefs: [
                { orderable: false, targets: [0, 4] }
            ],
            // Clases de Tailwind para elementos de DataTables
            dom: '<"flex flex-col md:flex-row justify-between items-start md:items-center mb-4"<"flex-1"f><"flex items-center justify-end"l>>rt<"flex flex-col md:flex-row justify-between items-center"<"flex-1"i><"flex-1 mt-4 md:mt-0"p>>',
            responsive: true
        // Confirmación de eliminación para todos los formularios de eliminación
        $('form').on('submit', function(e) {
            if ($(this).find('button[type="submit"]').hasClass('text-red-600')) {
                e.preventDefault();
                
                if (confirm('¿Estás seguro de que deseas eliminar este mensaje? Esta acción no se puede deshacer.')) {
                    this.submit();
                }
            }
        });
    });
</script>
@endsection
