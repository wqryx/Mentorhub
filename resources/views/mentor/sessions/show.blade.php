@extends('layouts.mentor')

@section('title', 'Detalles de la Sesión - MentorHub')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Detalles de la Sesión</h1>
        <div>
            <a href="{{ route('mentor.sessions.edit', $session->id) }}" class="btn btn-primary">
                <i class="fas fa-edit me-1"></i> Editar
            </a>
            <a href="{{ route('mentor.sessions.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-1"></i> Volver
            </a>
        </div>
    </div>

    @include('partials.alerts')

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">{{ $session->title }}</h6>
                    <span class="badge bg-{{ $session->status === 'completed' ? 'success' : ($session->status === 'cancelled' ? 'danger' : 'primary') }}">
                        {{ ucfirst($session->status) }}
                    </span>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h5 class="font-weight-bold">Información General</h5>
                            <hr>
                            <p><strong><i class="far fa-calendar-alt me-2"></i>Fecha y Hora:</strong> 
                                {{ $session->start_time->format('l, d M Y - H:i') }}</p>
                            <p><strong><i class="far fa-clock me-2"></i>Duración:</strong> 
                                {{ $session->duration }} minutos</p>
                            <p><strong><i class="fas fa-user-graduate me-2"></i>Estudiante:</strong> 
                                {{ $session->mentee->name }}</p>
                            @if($session->course)
                                <p><strong><i class="fas fa-book me-2"></i>Curso:</strong> 
                                    {{ $session->course->title }}</p>
                            @endif
                        </div>
                        <div class="col-md-6">
                            <h5 class="font-weight-bold">Enlaces de la Reunión</h5>
                            <hr>
                            @if($session->meeting_url)
                                <p>
                                    <strong><i class="fas fa-video me-2"></i>Enlace de la Reunión:</strong><br>
                                    <a href="{{ $session->meeting_url }}" target="_blank" class="text-primary">
                                        {{ $session->meeting_url }}
                                    </a>
                                </p>
                                <a href="{{ $session->meeting_url }}" target="_blank" class="btn btn-primary mb-3">
                                    <i class="fas fa-video me-1"></i> Unirse a la Reunión
                                </a>
                            @else
                                <div class="alert alert-warning">
                                    <i class="fas fa-exclamation-triangle me-2"></i>
                                    No se ha proporcionado un enlace de reunión para esta sesión.
                                </div>
                            @endif
                        </div>
                    </div>

                    @if($session->description)
                        <div class="mb-4">
                            <h5 class="font-weight-bold">Descripción</h5>
                            <hr>
                            <p class="text-justify">{{ $session->description }}</p>
                        </div>
                    @endif

                    @if($session->notes)
                        <div class="mb-4">
                            <h5 class="font-weight-bold">Notas Adicionales</h5>
                            <hr>
                            <p class="text-justify">{{ $session->notes }}</p>
                        </div>
                    @endif

                    @if($session->status === 'scheduled')
                        <div class="d-flex gap-2 mt-4">
                            <form action="{{ route('mentor.sessions.update-status') }}" method="POST" class="d-inline">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="session_id" value="{{ $session->id }}">
                                <input type="hidden" name="status" value="completed">
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-check-circle me-1"></i> Marcar como Completada
                                </button>
                            </form>

                            <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#cancelModal">
                                <i class="fas fa-times-circle me-1"></i> Cancelar Sesión
                            </button>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Sección de Reseñas -->
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Reseñas</h6>
                </div>
                <div class="card-body">
                    @if($session->reviews->isEmpty())
                        <p class="text-muted">No hay reseñas para esta sesión.</p>
                    @else
                        @foreach($session->reviews as $review)
                            <div class="mb-4 pb-3 border-bottom">
                                <div class="d-flex justify-content-between mb-2">
                                    <div>
                                        <strong>{{ $review->author->name }}</strong>
                                        <div class="text-warning">
                                            @for($i = 1; $i <= 5; $i++)
                                                <i class="fas fa-star{{ $i <= $review->rating ? '' : '-o' }}"></i>
                                            @endfor
                                        </div>
                                        <small class="text-muted">
                                            {{ $review->created_at->diffForHumans() }}
                                        </small>
                                    </div>
                                </div>
                                <p class="mb-0">{{ $review->comment }}</p>
                            </div>
                        @endforeach
                    @endif

                    @if($session->status === 'completed' && !$session->reviews->where('author_id', auth()->id())->first())
                        <button class="btn btn-outline-primary btn-sm mt-2" data-bs-toggle="modal" data-bs-target="#reviewModal">
                            <i class="fas fa-plus me-1"></i> Añadir Reseña
                        </button>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Tarjeta de Información del Estudiante -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Información del Estudiante</h6>
                </div>
                <div class="card-body text-center">
                    <img src="{{ $session->mentee->profile_photo_url ?? asset('img/default-avatar.png') }}" 
                         class="rounded-circle mb-3" 
                         width="120" 
                         height="120" 
                         alt="{{ $session->mentee->name }}">
                    <h5>{{ $session->mentee->name }}</h5>
                    <p class="text-muted mb-2">{{ $session->mentee->email }}</p>
                    
                    @if(isset($session->mentee->profile) && $session->mentee->profile->bio)
                        <p class="mt-3 text-justify">
                            <strong>Biografía:</strong><br>
                            {{ Str::limit($session->mentee->profile->bio, 150) }}
                        </p>
                    @endif
                    
                    <a href="{{ route('mentor.students.show', $session->mentee->id) }}" 
                       class="btn btn-outline-primary btn-sm mt-2">
                        <i class="fas fa-user-graduate me-1"></i> Ver Perfil Completo
                    </a>
                </div>
            </div>

            <!-- Próximas Sesiones con este Estudiante -->
            @if(isset($upcomingSessions) && $upcomingSessions->isNotEmpty())
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Próximas Sesiones</h6>
                    </div>
                    <div class="list-group list-group-flush">
                        @foreach($upcomingSessions as $upcoming)
                            @if($upcoming->id !== $session->id)
                                <a href="{{ route('mentor.sessions.show', $upcoming->id) }}" 
                                   class="list-group-item list-group-item-action">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1">{{ $upcoming->title }}</h6>
                                        <small>{{ $upcoming->scheduled_at->format('d/m') }}</small>
                                    </div>
                                    <small class="text-muted">
                                        {{ $upcoming->scheduled_at->format('H:i') }} - 
                                        {{ $upcoming->scheduled_at->addMinutes($upcoming->duration)->format('H:i') }}
                                    </small>
                                </a>
                            @endif
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal de Cancelación -->
<div class="modal fade" id="cancelModal" tabindex="-1" aria-labelledby="cancelModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="cancelModalLabel">Confirmar Cancelación</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <form action="{{ route('mentor.sessions.update-status') }}" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" name="session_id" value="{{ $session->id }}">
                <input type="hidden" name="status" value="cancelled">
                <div class="modal-body">
                    <p>¿Estás seguro de que deseas cancelar esta sesión?</p>
                    <div class="mb-3">
                        <label for="cancelReason" class="form-label">Razón de la cancelación (opcional)</label>
                        <textarea class="form-control" id="cancelReason" name="notes" rows="3" 
                                 placeholder="Proporciona una razón para la cancelación"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-danger">Confirmar Cancelación</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal de Reseña -->
<div class="modal fade" id="reviewModal" tabindex="-1" aria-labelledby="reviewModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="reviewModalLabel">Añadir Reseña</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <form action="{{ route('mentor.sessions.review', $session->id) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Calificación</label>
                        <div class="rating-stars">
                            <input type="hidden" name="rating" id="ratingValue" value="5">
                            <div class="stars">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="fas fa-star star" data-rating="{{ $i }}"></i>
                                @endfor
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="comment" class="form-label">Comentario</label>
                        <textarea class="form-control" id="comment" name="comment" rows="4" 
                                 placeholder="¿Cómo fue la sesión?" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Guardar Reseña</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .rating-stars {
        font-size: 1.5rem;
        margin: 10px 0;
    }
    .rating-stars .star {
        color: #ddd;
        cursor: pointer;
        transition: color 0.2s;
    }
    .rating-stars .star.selected,
    .rating-stars .star.hovered {
        color: #ffc107;
    }
    .card {
        transition: transform 0.2s, box-shadow 0.2s;
    }
    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Calificación con estrellas
        const stars = document.querySelectorAll('.star');
        if (stars.length > 0) {
            // Inicializar todas las estrellas como seleccionadas por defecto
            stars.forEach(s => {
                if (parseInt(s.getAttribute('data-rating')) <= 5) {
                    s.classList.add('selected');
                }
            });
            
            stars.forEach(star => {
                star.addEventListener('click', function() {
                    const rating = this.getAttribute('data-rating');
                    document.getElementById('ratingValue').value = rating;
                    
                    // Actualizar la visualización de estrellas
                    stars.forEach(s => {
                        if (parseInt(s.getAttribute('data-rating')) <= rating) {
                            s.classList.add('selected');
                        } else {
                            s.classList.remove('selected');
                        }
                    });
                });

                // Efecto hover
                star.addEventListener('mouseover', function() {
                    const rating = this.getAttribute('data-rating');
                    stars.forEach(s => {
                        if (parseInt(s.getAttribute('data-rating')) <= rating) {
                            s.classList.add('hovered');
                        } else {
                            s.classList.remove('hovered');
                        }
                    });
                });

                star.addEventListener('mouseout', function() {
                    stars.forEach(s => s.classList.remove('hovered'));
                });
            });
        }
    });
</script>
@endpush
