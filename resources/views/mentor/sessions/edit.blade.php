@extends('layouts.mentor')

@section('title', 'Editar Sesión - MentorHub')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Editar Sesión</h1>
        <a href="{{ route('mentor.sessions.show', $session->id) }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i> Volver
        </a>
    </div>

    @include('partials.alerts')

    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Información de la Sesión</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('mentor.sessions.update', $session->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-3">
                            <label for="title" class="form-label">Título de la Sesión <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror" 
                                   id="title" name="title" value="{{ old('title', $session->title) }}" required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="mentee_id" class="form-label">Estudiante <span class="text-danger">*</span></label>
                                <select class="form-select @error('mentee_id') is-invalid @enderror" 
                                        id="mentee_id" name="mentee_id" required>
                                    <option value="">Seleccionar estudiante...</option>
                                    @foreach($mentees as $id => $name)
                                        <option value="{{ $id }}" {{ old('mentee_id', $session->mentee_id) == $id ? 'selected' : '' }}>
                                            {{ $name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('mentee_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="course_id" class="form-label">Curso (opcional)</label>
                                <select class="form-select @error('course_id') is-invalid @enderror" 
                                        id="course_id" name="course_id">
                                    <option value="">Seleccionar curso...</option>
                                    @if(isset($courses))
                                        @foreach($courses as $id => $title)
                                            <option value="{{ $id }}" {{ old('course_id', $session->course_id) == $id ? 'selected' : '' }}>
                                                {{ $title }}
                                            </option>
                                        @endforeach
                                    @endif
                                </select>
                                @error('course_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="scheduled_at" class="form-label">Fecha y Hora <span class="text-danger">*</span></label>
                                <input type="datetime-local" 
                                       class="form-control @error('scheduled_at') is-invalid @enderror" 
                                       id="scheduled_at" name="scheduled_at" 
                                       value="{{ old('scheduled_at', $session->scheduled_at->format('Y-m-d\TH:i')) }}" 
                                       min="{{ now()->format('Y-m-d\TH:i') }}"
                                       required>
                                @error('scheduled_at')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="duration" class="form-label">Duración (minutos) <span class="text-danger">*</span></label>
                                <select class="form-select @error('duration') is-invalid @enderror" 
                                        id="duration" name="duration" required>
                                    <option value="30" {{ old('duration', $session->duration) == 30 ? 'selected' : '' }}>30 minutos</option>
                                    <option value="45" {{ old('duration', $session->duration) == 45 ? 'selected' : '' }}>45 minutos</option>
                                    <option value="60" {{ old('duration', $session->duration) == 60 ? 'selected' : '' }}>1 hora</option>
                                    <option value="90" {{ old('duration', $session->duration) == 90 ? 'selected' : '' }}>1 hora 30 minutos</option>
                                    <option value="120" {{ old('duration', $session->duration) == 120 ? 'selected' : '' }}>2 horas</option>
                                </select>
                                @error('duration')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Descripción</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="4">{{ old('description', $session->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="meeting_url" class="form-label">URL de la Reunión</label>
                            <input type="url" class="form-control @error('meeting_url') is-invalid @enderror" 
                                   id="meeting_url" name="meeting_url" value="{{ old('meeting_url', $session->meeting_url) }}"
                                   placeholder="https://meet.google.com/xxx-xxxx-xxx">
                            <div class="form-text">Introduce un enlace a Zoom, Google Meet u otra plataforma de videoconferencia.</div>
                            @error('meeting_url')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="status" class="form-label">Estado <span class="text-danger">*</span></label>
                            <select class="form-select @error('status') is-invalid @enderror" 
                                    id="status" name="status" required>
                                <option value="scheduled" {{ old('status', $session->status) == 'scheduled' ? 'selected' : '' }}>Programada</option>
                                <option value="completed" {{ old('status', $session->status) == 'completed' ? 'selected' : '' }}>Completada</option>
                                <option value="cancelled" {{ old('status', $session->status) == 'cancelled' ? 'selected' : '' }}>Cancelada</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="notes" class="form-label">Notas</label>
                            <textarea class="form-control @error('notes') is-invalid @enderror" 
                                      id="notes" name="notes" rows="3">{{ old('notes', $session->notes) }}</textarea>
                            <div class="form-text">Notas adicionales sobre la sesión (solo visibles para ti).</div>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="{{ route('mentor.sessions.show', $session->id) }}" class="btn btn-secondary me-md-2">Cancelar</a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i> Guardar Cambios
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Inicializar select2 si está disponible
        if (typeof $.fn.select2 !== 'undefined') {
            $('#mentee_id').select2({
                placeholder: 'Seleccionar estudiante...',
                allowClear: true
            });
            
            $('#course_id').select2({
                placeholder: 'Seleccionar curso...',
                allowClear: true
            });
            
            $('#status').select2();
        }
    });
</script>
@endpush
