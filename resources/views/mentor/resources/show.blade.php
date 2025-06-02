@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Detalles del Recurso</h5>
                    <div class="card-tools">
                        <a href="{{ route('mentor.resources') }}" class="btn btn-sm btn-primary">
                            <i class="fas fa-arrow-left"></i> Volver a Recursos
                        </a>
                        <a href="{{ route('mentor.resources.edit', $resource->id) }}" class="btn btn-sm btn-warning">
                            <i class="fas fa-edit"></i> Editar
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <h4>{{ $resource->title }}</h4>
                            
                            <div class="mb-3">
                                <span class="badge badge-primary">{{ $resourceTypes[$resource->type] ?? $resource->type }}</span>
                                @if($resource->is_public)
                                    <span class="badge badge-success">Público</span>
                                @else
                                    <span class="badge badge-secondary">Privado</span>
                                @endif
                                
                                @if($resource->is_premium)
                                    <span class="badge badge-warning">Premium</span>
                                @endif
                                
                                @if($resource->course)
                                    <span class="badge badge-info">{{ $resource->course->title }}</span>
                                @endif
                            </div>
                            
                            <div class="mb-4">
                                <p>{{ $resource->description }}</p>
                            </div>
                            
                            @if(in_array($resource->type, ['link', 'video']))
                                <div class="mb-4">
                                    <h6>URL:</h6>
                                    <a href="{{ $resource->url }}" target="_blank" class="btn btn-outline-primary">
                                        <i class="fas fa-external-link-alt"></i> Abrir enlace
                                    </a>
                                </div>
                            @endif
                            
                            @if($resource->file_path)
                                <div class="mb-4">
                                    <h6>Archivo:</h6>
                                    <a href="{{ route('mentor.resources.download', $resource->id) }}" class="btn btn-outline-primary">
                                        <i class="fas fa-download"></i> Descargar archivo
                                    </a>
                                </div>
                            @endif
                            
                            @if(!empty($resource->tags) && is_array($resource->tags))
                                <div class="mb-4">
                                    <h6>Etiquetas:</h6>
                                    <div>
                                        @foreach($resource->tags as $tag)
                                            <span class="badge badge-light">{{ $tag }}</span>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                        
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0">Estadísticas</h6>
                                </div>
                                <div class="card-body">
                                    <ul class="list-group list-group-flush">
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            Vistas
                                            <span class="badge badge-primary badge-pill">{{ $resource->views_count }}</span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            Descargas
                                            <span class="badge badge-primary badge-pill">{{ $resource->downloads_count }}</span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            Fecha de creación
                                            <span>{{ $resource->created_at->format('d/m/Y') }}</span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            Última actualización
                                            <span>{{ $resource->updated_at->format('d/m/Y') }}</span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            
                            <div class="card mt-3">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0">Acciones</h6>
                                </div>
                                <div class="card-body">
                                    <div class="d-flex flex-column">
                                        <a href="{{ route('mentor.resources.edit', $resource->id) }}" class="btn btn-warning mb-2">
                                            <i class="fas fa-edit"></i> Editar recurso
                                        </a>
                                        
                                        <button type="button" class="btn btn-danger mb-2" data-toggle="modal" data-target="#deleteResourceModal">
                                            <i class="fas fa-trash"></i> Eliminar recurso
                                        </button>
                                        
                                        @if($resource->file_path)
                                            <a href="{{ route('mentor.resources.download', $resource->id) }}" class="btn btn-primary">
                                                <i class="fas fa-download"></i> Descargar archivo
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Comentarios (si se implementan en el futuro) -->
                    @if(isset($resource->comments) && $resource->comments->count() > 0)
                    <div class="row mt-4">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="mb-0">Comentarios ({{ $resource->comments->count() }})</h6>
                                </div>
                                <div class="card-body">
                                    @foreach($resource->comments as $comment)
                                        <div class="media mb-3">
                                            <img src="{{ $comment->author->profile_image ?? asset('images/default-avatar.png') }}" class="mr-3 rounded-circle" alt="Avatar" style="width: 40px; height: 40px;">
                                            <div class="media-body">
                                                <h6 class="mt-0">{{ $comment->author->name }} <small class="text-muted">{{ $comment->created_at->diffForHumans() }}</small></h6>
                                                <p>{{ $comment->content }}</p>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de confirmación para eliminar recurso -->
<div class="modal fade" id="deleteResourceModal" tabindex="-1" role="dialog" aria-labelledby="deleteResourceModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteResourceModalLabel">Confirmar eliminación</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>¿Estás seguro de que deseas eliminar este recurso? Esta acción no se puede deshacer.</p>
                <p><strong>{{ $resource->title }}</strong></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <form action="{{ route('mentor.resources.destroy', $resource->id) }}" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Eliminar</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
