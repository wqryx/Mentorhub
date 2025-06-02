@extends('layouts.admin')

@section('title', 'Gestión de Mensajes')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/admin-messages.css') }}">
@endpush

@section('content')
<div class="container-fluid">
    <!-- Encabezado -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Gestión de Mensajes</h1>
        <div>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#newMessageModal">
                <i class="fas fa-plus"></i> Nuevo Mensaje
            </button>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success" role="alert">
            {{ session('success') }}
        </div>
    @endif

    <!-- Mensajes -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Mensajes</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="messagesTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Remitente</th>
                            <th>Destinatario</th>
                            <th>Asunto</th>
                            <th>Fecha</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($messages as $message)
                            <tr>
                                <td>{{ $message->sender->name }}</td>
                                <td>{{ $message->receiver->name }}</td>
                                <td>{{ $message->subject }}</td>
                                <td>{{ $message->created_at->format('d/m/Y H:i') }}</td>
                                <td>
                                    @if($message->read_at)
                                        <span class="badge bg-success">Leído</span>
                                    @else
                                        <span class="badge bg-warning">No leído</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <button type="button" class="btn btn-sm btn-info view-message" data-id="{{ $message->id }}">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <form action="{{ route('admin.messages.destroy', $message) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('¿Estás seguro de eliminar este mensaje?')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">No hay mensajes disponibles</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="mt-4">
                {{ $messages->links() }}
            </div>
        </div>
    </div>
</div>

<!-- Modal Ver Mensaje -->
<div class="modal fade" id="viewMessageModal" tabindex="-1" aria-labelledby="viewMessageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewMessageModalLabel">Detalle del Mensaje</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="messageDetails">
                    <div class="mb-3">
                        <strong>Remitente:</strong> <span id="messageSender"></span>
                    </div>
                    <div class="mb-3">
                        <strong>Destinatario:</strong> <span id="messageReceiver"></span>
                    </div>
                    <div class="mb-3">
                        <strong>Asunto:</strong> <span id="messageSubject"></span>
                    </div>
                    <div class="mb-3">
                        <strong>Fecha:</strong> <span id="messageDate"></span>
                    </div>
                    <div class="mb-3">
                        <strong>Mensaje:</strong>
                        <div id="messageContent" class="p-3 bg-light rounded"></div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary" id="replyButton">Responder</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Nuevo Mensaje -->
<div class="modal fade" id="newMessageModal" tabindex="-1" aria-labelledby="newMessageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="newMessageModalLabel">Nuevo Mensaje</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.messages.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="recipient" class="form-label">Destinatario</label>
                        <select class="form-select" id="recipient" name="recipient_id" required>
                            <option value="">Seleccionar destinatario...</option>
                            <!-- Aquí se cargarían los usuarios desde la base de datos -->
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="subject" class="form-label">Asunto</label>
                        <input type="text" class="form-control" id="subject" name="subject" required>
                    </div>
                    <div class="mb-3">
                        <label for="content" class="form-label">Mensaje</label>
                        <textarea class="form-control" id="content" name="content" rows="5" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Enviar Mensaje</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Inicializar DataTable
        $('#messagesTable').DataTable({
            language: {
                url: '//cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json'
            }
        });
        
        // Ver mensaje
        $('.view-message').on('click', function() {
            const messageId = $(this).data('id');
            
            // Aquí se haría una petición AJAX para obtener los detalles del mensaje
            // Por ahora, simulamos datos de ejemplo
            $('#messageSender').text('Nombre del Remitente');
            $('#messageReceiver').text('Nombre del Destinatario');
            $('#messageSubject').text('Asunto del Mensaje');
            $('#messageDate').text('01/01/2023 12:00');
            $('#messageContent').html('Contenido del mensaje...');
            
            // Mostrar modal
            $('#viewMessageModal').modal('show');
        });
        
        // Responder mensaje
        $('#replyButton').on('click', function() {
            $('#viewMessageModal').modal('hide');
            
            // Prellenar datos en el modal de nuevo mensaje
            $('#recipient').val('ID_DEL_REMITENTE');
            $('#subject').val('RE: Asunto del Mensaje');
            
            // Mostrar modal de nuevo mensaje
            $('#newMessageModal').modal('show');
        });
    });
</script>
@endsection
