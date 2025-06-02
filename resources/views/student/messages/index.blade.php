@extends('layouts.student')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/student-messages.css') }}">
@endpush

@section('dashboard-content')
<div class="container-fluid">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Mensajes</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
            <div class="btn-group me-2">
                <button type="button" class="btn btn-sm btn-outline-secondary {{ !request('type') || request('type') == 'inbox' ? 'active' : '' }}" id="inbox-btn" onclick="window.location='{{ route('student.messages.index', ['type' => 'inbox']) }}'">
                    <i class="fas fa-inbox me-1"></i> Recibidos
                </button>
                <button type="button" class="btn btn-sm btn-outline-secondary {{ request('type') == 'sent' ? 'active' : '' }}" id="sent-btn" onclick="window.location='{{ route('student.messages.index', ['type' => 'sent']) }}'">
                    <i class="fas fa-paper-plane me-1"></i> Enviados
                </button>
                <button type="button" class="btn btn-sm btn-outline-secondary {{ request('type') == 'unread' ? 'active' : '' }}" id="unread-btn" onclick="window.location='{{ route('student.messages.index', ['type' => 'unread']) }}'">
                    <i class="fas fa-envelope me-1"></i> No leídos
                </button>
            </div>
            <button type="button" class="btn btn-sm btn-primary" onclick="window.location='{{ route('student.messages.create') }}'">
                <i class="fas fa-plus me-1"></i> Nuevo mensaje
            </button>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row">
        <!-- Panel lateral con estadísticas y contactos -->
        <div class="col-md-3 mb-4">
            <!-- Estadísticas -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Resumen</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span>Total de mensajes</span>
                        <span class="badge bg-primary rounded-pill">{{ $stats['totalMessages'] }}</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span>No leídos</span>
                        <span class="badge bg-danger rounded-pill">{{ $stats['unreadMessages'] }}</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <span>Enviados</span>
                        <span class="badge bg-secondary rounded-pill">{{ $stats['sentMessages'] }}</span>
                    </div>
                </div>
                <div class="card-footer bg-white">
                    <button class="btn btn-sm btn-outline-secondary w-100" id="mark-all-read-btn" onclick="event.preventDefault(); document.getElementById('mark-all-read-form').submit();">
                        <i class="fas fa-check-double me-1"></i> Marcar todo como leído
                    </button>
                    <form id="mark-all-read-form" action="{{ route('student.messages.mark-all-read') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </div>
            </div>

            <!-- Contactos frecuentes -->
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Contactos</h5>
                </div>
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item list-group-item-action fw-bold">Mentores</li>
                        @foreach($mentors as $mentor)
                            <li class="list-group-item list-group-item-action d-flex align-items-center">
                                <div class="avatar avatar-sm me-2">
                                    @if($mentor->profile_photo_path)
                                        <img src="{{ asset('storage/' . $mentor->profile_photo_path) }}" alt="{{ $mentor->name }}" class="avatar-img rounded-circle">
                                    @else
                                        <div class="avatar-placeholder rounded-circle bg-primary">
                                            {{ strtoupper(substr($mentor->name, 0, 1)) }}
                                        </div>
                                    @endif
                                </div>
                                <a href="{{ route('student.messages.index', ['sender_id' => $mentor->id]) }}" class="text-decoration-none text-dark">
                                    {{ $mentor->name }}
                                </a>
                            </li>
                        @endforeach
                        
                        <li class="list-group-item list-group-item-action fw-bold">Administradores</li>
                        @foreach($admins as $admin)
                            <li class="list-group-item list-group-item-action d-flex align-items-center">
                                <div class="avatar avatar-sm me-2">
                                    @if($admin->profile_photo_path)
                                        <img src="{{ asset('storage/' . $admin->profile_photo_path) }}" alt="{{ $admin->name }}" class="avatar-img rounded-circle">
                                    @else
                                        <div class="avatar-placeholder rounded-circle bg-secondary">
                                            {{ strtoupper(substr($admin->name, 0, 1)) }}
                                        </div>
                                    @endif
                                </div>
                                <a href="{{ route('student.messages.index', ['sender_id' => $admin->id]) }}" class="text-decoration-none text-dark">
                                    {{ $admin->name }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>

        <!-- Lista de mensajes -->
        <div class="col-md-9">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            @if(request('type') == 'sent')
                                Mensajes enviados
                            @elseif(request('type') == 'unread')
                                Mensajes no leídos
                            @else
                                Bandeja de entrada
                            @endif
                        </h5>
                        <div class="input-group input-group-sm" style="width: 250px;">
                            <input type="text" class="form-control" id="message-search" placeholder="Buscar mensajes...">
                            <span class="input-group-text">
                                <i class="fas fa-search"></i>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="card-body p-0">
                    @if($messages->isEmpty())
                        <div class="text-center py-5">
                            <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                            <p class="mb-0 text-muted">No hay mensajes para mostrar</p>
                        </div>
                    @else
                        <div class="list-group list-group-flush message-list">
                            @foreach($messages as $message)
                                @php
                                    $isUnread = !$message->read && $message->recipient_id == auth()->id();
                                    $isSent = $message->sender_id == auth()->id();
                                    $otherUser = $isSent ? $message->recipient : $message->sender;
                                @endphp
                                <a href="{{ route('student.messages.show', $message->id) }}" class="list-group-item list-group-item-action message-item {{ $isUnread ? 'unread' : '' }}">
                                    <div class="d-flex w-100 justify-content-between align-items-center">
                                        <div class="d-flex align-items-center">
                                            <div class="avatar avatar-sm me-3">
                                                @if($otherUser->profile_photo_path)
                                                    <img src="{{ asset('storage/' . $otherUser->profile_photo_path) }}" alt="{{ $otherUser->name }}" class="avatar-img rounded-circle">
                                                @else
                                                    <div class="avatar-placeholder rounded-circle {{ $isSent ? 'bg-secondary' : 'bg-primary' }}">
                                                        {{ strtoupper(substr($otherUser->name, 0, 1)) }}
                                                    </div>
                                                @endif
                                            </div>
                                            <div>
                                                <div class="d-flex align-items-center">
                                                    <h6 class="mb-0 {{ $isUnread ? 'fw-bold' : '' }}">
                                                        {{ $isSent ? 'Para: ' . $otherUser->name : $otherUser->name }}
                                                    </h6>
                                                    @if($isUnread)
                                                        <span class="badge bg-danger ms-2">Nuevo</span>
                                                    @endif
                                                </div>
                                                <p class="mb-0 text-truncate" style="max-width: 500px;">
                                                    <span class="{{ $isUnread ? 'fw-bold' : 'text-muted' }}">
                                                        {{ $message->subject }}
                                                    </span>
                                                    - 
                                                    <span class="text-muted">
                                                        {{ Str::limit(strip_tags($message->content), 50) }}
                                                    </span>
                                                </p>
                                            </div>
                                        </div>
                                        <div class="text-end">
                                            <small class="text-muted">
                                                {{ $message->created_at->diffForHumans() }}
                                            </small>
                                            <div>
                                                <div class="dropdown">
                                                    <button class="btn btn-sm text-muted" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                        <i class="fas fa-ellipsis-v"></i>
                                                    </button>
                                                    <ul class="dropdown-menu dropdown-menu-end">
                                                        <li>
                                                            <a class="dropdown-item" href="{{ route('student.messages.show', $message->id) }}">
                                                                <i class="fas fa-eye me-2"></i> Ver
                                                            </a>
                                                        </li>
                                                        @if(!$isSent)
                                                            <li>
                                                                <a class="dropdown-item toggle-read" href="#" data-id="{{ $message->id }}" data-read="{{ $message->read ? 'true' : 'false' }}">
                                                                    @if($message->read)
                                                                        <i class="fas fa-envelope me-2"></i> Marcar como no leído
                                                                    @else
                                                                        <i class="fas fa-envelope-open me-2"></i> Marcar como leído
                                                                    @endif
                                                                </a>
                                                            </li>
                                                        @endif
                                                        <li><hr class="dropdown-divider"></li>
                                                        <li>
                                                            <a class="dropdown-item text-danger delete-message" href="#" data-id="{{ $message->id }}">
                                                                <i class="fas fa-trash-alt me-2"></i> Eliminar
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    @endif
                </div>
                @if($messages->hasPages())
                    <div class="card-footer bg-white">
                        {{ $messages->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Modal de confirmación de eliminación -->
<div class="modal fade" id="deleteMessageModal" tabindex="-1" aria-labelledby="deleteMessageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteMessageModalLabel">Confirmar eliminación</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                ¿Estás seguro de que deseas eliminar este mensaje? Esta acción no se puede deshacer.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <form id="delete-message-form" action="" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Eliminar</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Búsqueda de mensajes
        const messageSearch = document.getElementById('message-search');
        const messageItems = document.querySelectorAll('.message-item');
        
        messageSearch.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase().trim();
            
            messageItems.forEach(item => {
                const messageText = item.textContent.toLowerCase();
                if (messageText.includes(searchTerm)) {
                    item.style.display = 'block';
                } else {
                    item.style.display = 'none';
                }
            });
        });
        
        // Marcar como leído/no leído
        const toggleReadLinks = document.querySelectorAll('.toggle-read');
        
        toggleReadLinks.forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                const messageId = this.dataset.id;
                const isRead = this.dataset.read === 'true';
                
                fetch(`{{ route('student.messages.toggle-read', '') }}/${messageId}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({})
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Actualizar la interfaz
                        const messageItem = this.closest('.message-item');
                        const readBadge = messageItem.querySelector('.badge.bg-danger');
                        const messageSubject = messageItem.querySelector('.text-truncate span:first-child');
                        
                        if (data.read) {
                            // Marcar como leído
                            messageItem.classList.remove('unread');
                            this.innerHTML = '<i class="fas fa-envelope me-2"></i> Marcar como no leído';
                            this.dataset.read = 'true';
                            if (readBadge) readBadge.remove();
                            messageSubject.classList.remove('fw-bold');
                            messageSubject.classList.add('text-muted');
                        } else {
                            // Marcar como no leído
                            messageItem.classList.add('unread');
                            this.innerHTML = '<i class="fas fa-envelope-open me-2"></i> Marcar como leído';
                            this.dataset.read = 'false';
                            messageSubject.classList.add('fw-bold');
                            messageSubject.classList.remove('text-muted');
                            
                            if (!readBadge) {
                                const nameElement = messageItem.querySelector('h6');
                                nameElement.insertAdjacentHTML('afterend', '<span class="badge bg-danger ms-2">Nuevo</span>');
                            }
                        }
                        
                        // Actualizar contador de mensajes no leídos
                        const unreadBadge = document.querySelector('.card-body .badge.bg-danger');
                        if (unreadBadge) {
                            let count = parseInt(unreadBadge.textContent);
                            unreadBadge.textContent = data.read ? count - 1 : count + 1;
                        }
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
            });
        });
        
        // Eliminar mensaje
        const deleteLinks = document.querySelectorAll('.delete-message');
        const deleteForm = document.getElementById('delete-message-form');
        
        deleteLinks.forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                const messageId = this.dataset.id;
                deleteForm.action = `{{ route('student.messages.destroy', '') }}/${messageId}`;
                
                const deleteModal = new bootstrap.Modal(document.getElementById('deleteMessageModal'));
                deleteModal.show();
            });
        });
    });
</script>
@endpush

