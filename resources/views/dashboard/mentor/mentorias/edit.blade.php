@extends('layouts.mentor')

@section('title', 'Editar Mentoría - MentorHub')

@section('content')
<div class="container-fluid">
    <!-- Encabezado -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Editar Mentoría</h1>
        <a href="{{ route('mentor.mentorias.show', $mentoria->id) }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i> Volver a Detalles
        </a>
    </div>

    <!-- Mensajes de alerta -->
    @include('partials.alerts')

    <!-- Formulario de edición -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Información de la Mentoría</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('mentor.mentorias.update', $mentoria->id) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="title" class="form-label">Título <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('title') is-invalid @enderror" 
                               id="title" name="title" value="{{ old('title', $mentoria->title) }}" required>
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="category" class="form-label">Categoría <span class="text-danger">*</span></label>
                        <select class="form-select @error('category') is-invalid @enderror" 
                                id="category" name="category" required>
                            <option value="" disabled>Seleccionar categoría</option>
                            <option value="programming" {{ old('category', $mentoria->category) == 'programming' ? 'selected' : '' }}>Programación</option>
                            <option value="design" {{ old('category', $mentoria->category) == 'design' ? 'selected' : '' }}>Diseño</option>
                            <option value="business" {{ old('category', $mentoria->category) == 'business' ? 'selected' : '' }}>Negocios</option>
                            <option value="marketing" {{ old('category', $mentoria->category) == 'marketing' ? 'selected' : '' }}>Marketing</option>
                            <option value="other" {{ old('category', $mentoria->category) == 'other' ? 'selected' : '' }}>Otro</option>
                        </select>
                        @error('category')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">Descripción <span class="text-danger">*</span></label>
                    <textarea class="form-control @error('description') is-invalid @enderror" 
                              id="description" name="description" rows="4" required>{{ old('description', $mentoria->description) }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="duration" class="form-label">Duración (minutos) <span class="text-danger">*</span></label>
                        <input type="number" class="form-control @error('duration') is-invalid @enderror" 
                               id="duration" name="duration" value="{{ old('duration', $mentoria->duration) }}" min="15" max="180" required>
                        @error('duration')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="price" class="form-label">Precio (€)</label>
                        <input type="number" class="form-control @error('price') is-invalid @enderror" 
                               id="price" name="price" value="{{ old('price', $mentoria->price) }}" min="0" step="0.01">
                        @error('price')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label for="availability" class="form-label">Disponibilidad <span class="text-danger">*</span></label>
                    <div class="row">
                        @foreach(['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo'] as $index => $day)
                            @php
                                $dayNumber = $index + 1;
                                $dayAvailability = $mentoria->availability[$dayNumber] ?? null;
                                $isChecked = $dayAvailability !== null;
                                $startTime = $dayAvailability['start'] ?? '09:00';
                                $endTime = $dayAvailability['end'] ?? '18:00';
                            @endphp
                            <div class="col-md-6 col-lg-3 mb-2">
                                <div class="card">
                                    <div class="card-header py-2">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" 
                                                   id="day_{{ $index }}" name="days[]" value="{{ $dayNumber }}"
                                                   {{ $isChecked ? 'checked' : '' }}>
                                            <label class="form-check-label" for="day_{{ $index }}">
                                                {{ $day }}
                                            </label>
                                        </div>
                                    </div>
                                    <div class="card-body py-2">
                                        <div class="row">
                                            <div class="col-6">
                                                <label for="start_time_{{ $index }}" class="form-label small">Desde</label>
                                                <input type="time" class="form-control form-control-sm" 
                                                       id="start_time_{{ $index }}" name="start_time[{{ $dayNumber }}]" 
                                                       value="{{ $startTime }}" {{ !$isChecked ? 'disabled' : '' }}>
                                            </div>
                                            <div class="col-6">
                                                <label for="end_time_{{ $index }}" class="form-label small">Hasta</label>
                                                <input type="time" class="form-control form-control-sm" 
                                                       id="end_time_{{ $index }}" name="end_time[{{ $dayNumber }}]" 
                                                       value="{{ $endTime }}" {{ !$isChecked ? 'disabled' : '' }}>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="mb-3">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" 
                               {{ old('is_active', $mentoria->is_active) ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_active">
                            Activar esta mentoría (visible para estudiantes)
                        </label>
                    </div>
                </div>

                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                    <a href="{{ route('mentor.mentorias.show', $mentoria->id) }}" class="btn btn-light me-md-2">Cancelar</a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i> Guardar Cambios
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Lógica para habilitar/deshabilitar campos de horario según el checkbox del día
        const dayCheckboxes = document.querySelectorAll('input[name="days[]"]');
        
        dayCheckboxes.forEach(checkbox => {
            const dayIndex = checkbox.value;
            const timeInputs = document.querySelectorAll(`input[name^="start_time[${dayIndex}]"], input[name^="end_time[${dayIndex}]"]`);
            
            // Cambio de estado
            checkbox.addEventListener('change', function() {
                timeInputs.forEach(input => {
                    input.disabled = !this.checked;
                });
            });
        });
    });
</script>
@endsection
