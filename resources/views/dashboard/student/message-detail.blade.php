@extends('layouts.dashboard.student')

@section('dashboard-content')
<div class="container-fluid">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Detalle del mensaje</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
            <a href="{{ route('student.messages.index') }}" class="btn btn-sm btn-outline-secondary me-2">
                <i class="fas fa-arrow-left me-1"></i> Volver a mensajes
            </a>
            <div class="btn-group">
                <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#replyMessageModal">
                    <i class="fas fa-reply me-1"></i> Responder
                </button>
                <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteMessageModal">
                    <i class="fas fa-trash-alt me-1"></i> Eliminar
                </button>
            </div>
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
        <!-- Detalles del mensaje -->
        <div class="col-md-12 mb-4">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">{{ $message->subject }}</h5>
                        <small class="text-muted">{{ $message->created_at->format('d M Y, H:i') }}</small>
                    </div>
                </div>
                <div class="card-body">
                    <div class="d-flex mb-4">
                        <div class="avatar me-3">
                            @if($message->sender->profile_photo_path)
                                <img src="{{ asset('storage/' . $message->sender->profile_photo_path) }}" alt="{{ $message->sender->name }}" class="avatar-img rounded-circle">
                            @else
                                <div class="avatar-placeholder rounded-circle bg-primary">
                                    {{ strtoupper(substr($message->sender->name, 0, 1)) }}
                                </div>
                            @endif
                        </div>
                        <div>
                            <h6 class="mb-1">{{ $message->sender->name }}</h6>
                            <div class="d-flex align-items-center">
                                <span class="text-muted">Para: {{ $message->recipient->name }}</span>
                                @if($message->read && $message->read_at && $message->recipient_id == auth()->id())
                                    <span class="badge bg-info ms-2">Leído el {{ $message->read_at->format('d M Y, H:i') }}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    
                    <div class="message-content border-top pt-4">
                        {!! nl2br(e($message->content)) !!}
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Historial de conversación -->
        @if($conversation->count() > 1)
            <div class="col-md-12">
                <div class="card shadow-sm">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">Conversación</h5>
                    </div>
                    <div class="card-body">
                        <div class="conversation">
                            @foreach($conversation as $msg)
                                @if($msg->id != $message->id)
                                    <div class="conversation-item mb-4 {{ $msg->sender_id == auth()->id() ? 'text-end' : '' }}">
                                        <div class="d-flex {{ $msg->sender_id == auth()->id() ? 'justify-content-end' : '' }}">
                                            @if($msg->sender_id != auth()->id())
                                                <div class="avatar avatar-sm me-2">
                                                    @if($msg->sender->profile_photo_path)
                                                        <img src="{{ asset('storage/' . $msg->sender->profile_photo_path) }}" alt="{{ $msg->sender->name }}" class="avatar-img rounded-circle">
                                                    @else
                                                        <div class="avatar-placeholder rounded-circle bg-primary">
                                                            {{ strtoupper(substr($msg->sender->name, 0, 1)) }}
                                                        </div>
                                                    @endif
                                                </div>
                                            @endif
                                            
                                            <div class="conversation-bubble {{ $msg->sender_id == auth()->id() ? 'conversation-bubble-right' : 'conversation-bubble-left' }}">
                                                <div class="conversation-header">
                                                    <small class="fw-bold">{{ $msg->sender->name }}</small>
                                                    <small class="text-muted ms-2">{{ $msg->created_at->format('d M Y, H:i') }}</small>
                                                </div>
                                                <div class="conversation-text">
                                                    {!! nl2br(e($msg->content)) !!}
                                                </div>
                                            </div>
                                            
                                            @if($msg->sender_id == auth()->id())
                                                <div class="avatar avatar-sm ms-2">
                                                    @if($msg->sender->profile_photo_path)
                                                        <img src="{{ asset('storage/' . $msg->sender->profile_photo_path) }}" alt="{{ $msg->sender->name }}" class="avatar-img rounded-circle">
                                                    @else
                                                        <div class="avatar-placeholder rounded-circle bg-secondary">
                                                            {{ strtoupper(substr($msg->sender->name, 0, 1)) }}
                                                        </div>
                                                    @endif
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>

<!-- Modal para responder mensaje -->
<div class="modal fade" id="replyMessageModal" tabindex="-1" aria-labelledby="replyMessageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="replyMessageModalLabel">Responder mensaje</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('student.messages.reply', $message->id) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="reply-to" class="form-label">Para</label>
                        <input type="text" class="form-control" id="reply-to" value="{{ $message->sender_id == auth()->id() ? $message->recipient->name : $message->sender->name }}" disabled>
                    </div>
                    <div class="mb-3">
                        <label for="reply-subject" class="form-label">Asunto</label>
                        <input type="text" class="form-control" id="reply-subject" value="RE: {{ $message->subject }}" disabled>
                    </div>
                    <div class="mb-3">
                        <label for="content" class="form-label">Mensaje</label>
                        <textarea class="form-control" id="content" name="content" rows="6" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Enviar respuesta</button>
                </div>
            </form>
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
                <form action="{{ route('student.messages.destroy', $message->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Eliminar</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .avatar {
        width: 40px;
        height: 40px;
        position: relative;
    }
    
    .avatar-sm {
        width: 32px;
        height: 32px;
    }
    
    .avatar-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .avatar-placeholder {
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: bold;
    }
    
    .message-content {
        white-space: pre-line;
        line-height: 1.6;
    }
    
    .conversation-bubble {
        max-width: 80%;
        padding: 10px 15px;
        border-radius: 12px;
        position: relative;
        margin-bottom: 5px;
    }
    
    .conversation-bubble-left {
        background-color: #f0f2f5;
        border-top-left-radius: 0;
    }
    
    .conversation-bubble-right {
        background-color: #e3f2fd;
        border-top-right-radius: 0;
    }
    
    .conversation-header {
        margin-bottom: 5px;
        font-size: 0.85rem;
    }
    
    .conversation-text {
        white-space: pre-line;
    }
</style>
@endpush
