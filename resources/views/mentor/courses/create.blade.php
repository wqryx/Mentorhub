@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/mentor-courses.css') }}">
@endpush

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Crear Nuevo Curso</h5>
                    <div class="card-tools">
                        <a href="{{ route('mentor.courses.index') }}" class="btn btn-sm btn-primary">
                            <i class="fas fa-arrow-left"></i> Volver a Mis Cursos
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('mentor.courses.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label for="title">Título del Curso <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title') }}" required>
                                    @error('title')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                                
                                <div class="form-group">
                                    <label for="short_description">Descripción Corta <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('short_description') is-invalid @enderror" id="short_description" name="short_description" value="{{ old('short_description') }}" required maxlength="255">
                                    <small class="text-muted">Máximo 255 caracteres. Esta descripción aparecerá en las tarjetas de cursos.</small>
                                    @error('short_description')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                                
                                <div class="form-group">
                                    <label for="description">Descripción Completa <span class="text-danger">*</span></label>
                                    <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="6" required>{{ old('description') }}</textarea>
                                    @error('description')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                                
                                <div class="form-group">
                                    <label for="what_will_learn">Lo que aprenderás</label>
                                    <textarea class="form-control @error('what_will_learn') is-invalid @enderror" id="what_will_learn" name="what_will_learn" rows="4">{{ old('what_will_learn') }}</textarea>
                                    <small class="text-muted">Describe lo que los estudiantes aprenderán en este curso. Puedes usar formato de lista con viñetas (- item).</small>
                                    @error('what_will_learn')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                                
                                <div class="form-group">
                                    <label for="requirements">Requisitos previos</label>
                                    <textarea class="form-control @error('requirements') is-invalid @enderror" id="requirements" name="requirements" rows="4">{{ old('requirements') }}</textarea>
                                    <small class="text-muted">Describe los conocimientos previos que necesitan los estudiantes. Puedes usar formato de lista con viñetas (- item).</small>
                                    @error('requirements')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="image">Imagen del Curso</label>
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input @error('image') is-invalid @enderror" id="image" name="image" accept="image/*">
                                        <label class="custom-file-label" for="image">Seleccionar imagen</label>
                                    </div>
                                    <small class="form-text text-muted">Tamaño recomendado: 1280x720 pixeles (16:9)</small>
                                    @error('image')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                    <div class="mt-2">
                                        <img id="image-preview" src="{{ asset('images/course-placeholder.jpg') }}" class="img-fluid rounded" alt="Vista previa">
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label for="speciality_id">Especialidad <span class="text-danger">*</span></label>
                                    <select class="form-control @error('speciality_id') is-invalid @enderror" id="speciality_id" name="speciality_id" required>
                                        <option value="">Seleccionar especialidad</option>
                                        @foreach($specialities as $id => $name)
                                            <option value="{{ $id }}" {{ old('speciality_id') == $id ? 'selected' : '' }}>{{ $name }}</option>
                                        @endforeach
                                    </select>
                                    @error('speciality_id')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                                
                                <div class="form-group">
                                    <label for="level">Nivel <span class="text-danger">*</span></label>
                                    <select class="form-control @error('level') is-invalid @enderror" id="level" name="level" required>
                                        <option value="beginner" {{ old('level') == 'beginner' ? 'selected' : '' }}>Principiante</option>
                                        <option value="intermediate" {{ old('level') == 'intermediate' ? 'selected' : '' }}>Intermedio</option>
                                        <option value="advanced" {{ old('level') == 'advanced' ? 'selected' : '' }}>Avanzado</option>
                                    </select>
                                    @error('level')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                                
                                <div class="form-group">
                                    <label for="price">Precio <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">€</span>
                                        </div>
                                        <input type="number" class="form-control @error('price') is-invalid @enderror" id="price" name="price" value="{{ old('price', '0') }}" min="0" step="0.01" required>
                                    </div>
                                    <small class="form-text text-muted">Establece 0 para cursos gratuitos</small>
                                    @error('price')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                                
                                <div class="form-group">
                                    <label for="duration">Duración (horas) <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control @error('duration') is-invalid @enderror" id="duration" name="duration" value="{{ old('duration', '1') }}" min="1" required>
                                    @error('duration')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                                
                                <div class="form-group">
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" class="custom-control-input" id="is_published" name="is_published" value="1" {{ old('is_published') ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="is_published">Publicar inmediatamente</label>
                                    </div>
                                    <small class="form-text text-muted">Si no está marcado, el curso quedará como borrador.</small>
                                </div>
                                
                                <div class="form-group">
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" class="custom-control-input" id="is_featured" name="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="is_featured">Curso destacado</label>
                                    </div>
                                    <small class="form-text text-muted">Los cursos destacados aparecen en la página principal.</small>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group mt-4">
                            <button type="submit" class="btn btn-primary">Crear Curso</button>
                            <a href="{{ route('mentor.courses.index') }}" class="btn btn-secondary">Cancelar</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Vista previa de la imagen
        $('#image').change(function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    $('#image-preview').attr('src', e.target.result);
                }
                reader.readAsDataURL(file);
                
                // Actualizar etiqueta del input
                $('.custom-file-label').text(file.name);
            }
        });
        
        // Inicializar editor de texto enriquecido si está disponible
        if (typeof ClassicEditor !== 'undefined') {
            ClassicEditor.create(document.querySelector('#description'))
                .catch(error => {
                    console.error(error);
                });
                
            ClassicEditor.create(document.querySelector('#what_will_learn'))
                .catch(error => {
                    console.error(error);
                });
                
            ClassicEditor.create(document.querySelector('#requirements'))
                .catch(error => {
                    console.error(error);
                });
        }
    });
</script>
@endsection
