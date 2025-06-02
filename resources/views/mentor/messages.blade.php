@extends('layouts.mentor')

@section('title', 'Mensajes - MentorHub')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/mentor-messages.css') }}">
@endpush

@section('content')
<div class="container-fluid">
    <!-- Encabezado -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Mensajes</h1>
        <a href="{{ route('mentor.messages.create') }}" class="btn btn-primary">
            <i class="fas fa-paper-plane me-1"></i> Nuevo Mensaje
        </a>
    </div>

    <!-- Mensajes de alerta -->
    @include('partials.alerts')

    <!-- Pestañas de navegación -->
    <ul class="nav nav-tabs mb-4" id="messagesTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="inbox-tab" data-bs-toggle="tab" data-bs-target="#inbox" type="button">
                <i class="fas fa-inbox me-1"></i> Bandeja de entrada
                @if($unreadCount > 0)
                    <span class="badge bg-danger rounded-pill ms-1">{{ $unreadCount }}</span>
                @endif
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="sent-tab" data-bs-toggle="tab" data-bs-target="#sent" type="button">
                <i class="fas fa-paper-plane me-1"></i> Enviados
            </button>
        </li>
    </ul>

    <!-- Contenido de las pestañas -->
    <div class="tab-content" id="messagesTabsContent">
        <!-- Pestaña de Bandeja de entrada -->
        <div class="tab-pane fade show active" id="inbox" role="tabpanel">
            <div class="card shadow mb-4">
                <div class="card-body">
                    @if($receivedMessages->isEmpty())
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            No tienes mensajes en tu bandeja de entrada.
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover" id="inboxTable">
                                <thead>
                                    <tr>
                                        <th style="width: 5%"></th>
                                        <th style="width: 20%">Remitente</th>
                                        <th style="width: 55%">Asunto</th>
                                        <th style="width: 15%">Fecha</th>
                                        <th style="width: 5%">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($receivedMessages as $message)
                                        <tr class="{{ !$message->read ? 'table-active fw-bold' : '' }}">
                                            <td class="text-center">
                                                @if(!$message->read)
                                                    <span class="text-primary">
                                                        <i class="fas fa-circle fa-xs"></i>
                                                    </span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <img src="{{ $message->sender->profile_photo_url }}" 
                                                         class="rounded-circle me-2" 
                                                         width="40" 
                                                         height="40" 
                                                         alt="{{ $message->sender->name }}">
                                                    <div>
                                                        {{ $message->sender->name }}
                                                        <br>
                                                        <small class="text-muted">{{ $message->sender->role }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <a href="{{ route('mentor.messages.show', $message->id) }}" class="text-decoration-none text-dark">
                                                    {{ $message->subject }}
                                                    <p class="text-muted mb-0 small">
                                                        {{ Str::limit(strip_tags($message->content), 60) }}
                                                    </p>
                                                </a>
                                            </td>
                                            <td>{{ $message->created_at->format('d/m/Y H:i') }}</td>
                                            <td>
                                                <div class="dropdown">
                                                    <button class="btn btn-sm btn-light dropdown-toggle" type="button" id="dropdownMenuButton{{ $message->id }}" data-bs-toggle="dropdown" aria-expanded="false">
                                                        <i class="fas fa-ellipsis-v"></i>
                                                    </button>
                                                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton{{ $message->id }}">
                                                        <li>
                                                            <a class="dropdown-item" href="{{ route('mentor.messages.show', $message->id) }}">
                                                                <i class="fas fa-eye me-2"></i> Ver mensaje
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <form action="{{ route('mentor.messages.toggle-read', $message->id) }}" method="POST" class="d-inline">
                                                                @csrf
                                                                <button type="submit" class="dropdown-item">
                                                                    <i class="fas {{ $message->read ? 'fa-envelope' : 'fa-envelope-open' }} me-2"></i>
                                                                    Marcar como {{ $message->read ? 'no leído' : 'leído' }}
                                                                </button>
                                                            </form>
                                                        </li>
                                                        <li><hr class="dropdown-divider"></li>
                                                        <li>
                                                            <form action="{{ route('mentor.messages.destroy', $message->id) }}" method="POST" class="d-inline delete-form">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="dropdown-item text-danger">
                                                                    <i class="fas fa-trash-alt me-2"></i> Eliminar
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
            <div class="card shadow mb-4">
                <div class="card-body">
                    @if($sentMessages->isEmpty())
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            No has enviado ningún mensaje.
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover" id="sentTable">
                                <thead>
                                    <tr>
                                        <th style="width: 20%">Destinatario</th>
                                        <th style="width: 60%">Asunto</th>
                                        <th style="width: 15%">Fecha</th>
                                        <th style="width: 5%">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($sentMessages as $message)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <img src="{{ $message->recipient->profile_photo_url }}" 
                                                         class="rounded-circle me-2" 
                                                         width="40" 
                                                         height="40" 
                                                         alt="{{ $message->recipient->name }}">
                                                    <div>
                                                        {{ $message->recipient->name }}
                                                        <br>
                                                        <small class="text-muted">{{ $message->recipient->role }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <a href="{{ route('mentor.messages.show', $message->id) }}" class="text-decoration-none text-dark">
                                                    {{ $message->subject }}
                                                    <p class="text-muted mb-0 small">
                                                        {{ Str::limit(strip_tags($message->content), 60) }}
                                                    </p>
                                                </a>
                                            </td>
                                            <td>{{ $message->created_at->format('d/m/Y H:i') }}</td>
                                            <td>
                                                <div class="dropdown">
                                                    <button class="btn btn-sm btn-light dropdown-toggle" type="button" id="dropdownMenuButton{{ $message->id }}" data-bs-toggle="dropdown" aria-expanded="false">
                                                        <i class="fas fa-ellipsis-v"></i>
                                                    </button>
                                                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton{{ $message->id }}">
                                                        <li>
                                                            <a class="dropdown-item" href="{{ route('mentor.messages.show', $message->id) }}">
                                                                <i class="fas fa-eye me-2"></i> Ver mensaje
                                                            </a>
                                                        </li>
                                                        <li><hr class="dropdown-divider"></li>
                                                        <li>
                                                            <form action="{{ route('mentor.messages.destroy', $message->id) }}" method="POST" class="d-inline delete-form">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="dropdown-item text-danger">
                                                                    <i class="fas fa-trash-alt me-2"></i> Eliminar
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
        // Inicializar DataTables
        $('#inboxTable, #sentTable').DataTable({
            language: {
                url: '//cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json'
            },
            order: [[3, 'desc']],
            columnDefs: [
                { orderable: false, targets: [0, 4] }
            ]
        });
        
        // Confirmación de eliminación
        $('.delete-form').on('submit', function(e) {
            e.preventDefault();
            
            if (confirm('¿Estás seguro de que deseas eliminar este mensaje? Esta acción no se puede deshacer.')) {
                this.submit();
            }
        });
    });
</script>
@endsection
