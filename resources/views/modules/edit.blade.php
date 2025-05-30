@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row mb-4">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('courses.index') }}">Cursos</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('courses.show', $course) }}">{{ $course->title }}</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('courses.modules.index', $course) }}">Módulos</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('courses.modules.show', [$course, $module]) }}">{{ $module->name }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Editar módulo</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h4 class="card-title mb-0">Editar módulo: {{ $module->name }}</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('courses.modules.update', [$course, $module]) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-3">
                            <label for="name" class="form-label">Nombre del módulo <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $module->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="slug" class="form-label">Slug</label>
                            <div class="input-group">
                                <span class="input-group-text">{{ str_replace(' ', '-', strtolower($course->slug)) }}-</span>
                                <input type="text" class="form-control @error('slug') is-invalid @enderror" id="slug" name="slug" value="{{ old('slug', $module->slug) }}" aria-describedby="slugHelp">
                            </div>
                            <div id="slugHelp" class="form-text">Identificador único para URLs. Cámbialo solo si es necesario.</div>
                            @error('slug')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Descripción <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="4" required>{{ old('description', $module->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="order" class="form-label">Orden</label>
                                <input type="number" class="form-control @error('order') is-invalid @enderror" id="order" name="order" min="1" value="{{ old('order', $module->order) }}">
                                <div class="form-text">Posición de este módulo en el curso.</div>
                                @error('order')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="duration_hours" class="form-label">Duración (horas)</label>
                                <input type="number" class="form-control @error('duration_hours') is-invalid @enderror" id="duration_hours" name="duration_hours" min="0" step="0.5" value="{{ old('duration_hours', $module->duration_hours) }}">
                                @error('duration_hours')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="image" class="form-label">Imagen de portada</label>
                            
                            @if($module->image)
                                <div class="mb-2">
                                    <img src="{{ asset('storage/' . $module->image) }}" alt="{{ $module->name }}" class="img-thumbnail" style="max-height: 150px;">
                                    <div class="form-check mt-2">
                                        <input class="form-check-input" type="checkbox" id="remove_image" name="remove_image" value="1">
                                        <label class="form-check-label" for="remove_image">
                                            Eliminar imagen actual
                                        </label>
                                    </div>
                                </div>
                            @endif
                            
                            <input type="file" class="form-control @error('image') is-invalid @enderror" id="image" name="image" accept="image/*">
                            <div class="form-text">Deja en blanco para mantener la imagen actual. Formato recomendado: 16:9, máximo 2MB.</div>
                            @error('image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" id="is_published" name="is_published" value="1" {{ old('is_published', $module->is_published) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_published">Publicado</label>
                            <div class="form-text">Si está marcado, el módulo será visible para todos los estudiantes inscritos en el curso.</div>
                        </div>
                        
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="{{ route('courses.modules.show', [$course, $module]) }}" class="btn btn-outline-secondary me-md-2">Cancelar</a>
                            <button type="submit" class="btn btn-primary">Actualizar módulo</button>
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
        const nameInput = document.getElementById('name');
        const slugInput = document.getElementById('slug');
        const removeImageCheck = document.getElementById('remove_image');
        const imageInput = document.getElementById('image');
        
        // Deshabilitar la carga de imagen si se marca eliminar
        if (removeImageCheck) {
            removeImageCheck.addEventListener('change', function() {
                if (this.checked) {
                    imageInput.disabled = true;
                } else {
                    imageInput.disabled = false;
                }
            });
        }
        
        // Generar slug automáticamente desde el nombre si está vacío
        nameInput.addEventListener('blur', function() {
            if (slugInput.value === '') {
                const slug = nameInput.value
                    .toLowerCase()
                    .replace(/[^\w\s-]/g, '')
                    .replace(/[\s_-]+/g, '-')
                    .replace(/^-+|-+$/g, '');
                
                slugInput.value = slug;
            }
        });
    });
</script>
@endpush
