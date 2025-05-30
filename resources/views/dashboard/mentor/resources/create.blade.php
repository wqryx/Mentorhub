@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Crear Nuevo Recurso</h5>
                    <div class="card-tools">
                        <a href="{{ route('mentor.resources') }}" class="btn btn-sm btn-primary">
                            <i class="fas fa-arrow-left"></i> Volver a Recursos
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('mentor.resources.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="title">Título <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title') }}" required>
                                    @error('title')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="type">Tipo de Recurso <span class="text-danger">*</span></label>
                                    <select class="form-control @error('type') is-invalid @enderror" id="type" name="type" required>
                                        <option value="">Seleccionar tipo</option>
                                        @foreach($resourceTypes as $value => $label)
                                            <option value="{{ $value }}" {{ old('type') == $value ? 'selected' : '' }}>{{ $label }}</option>
                                        @endforeach
                                    </select>
                                    @error('type')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="description">Descripción</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3">{{ old('description') }}</textarea>
                            @error('description')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="course_id">Curso Relacionado</label>
                                    <select class="form-control @error('course_id') is-invalid @enderror" id="course_id" name="course_id">
                                        <option value="">Ninguno (Recurso General)</option>
                                        @foreach($courses as $id => $title)
                                            <option value="{{ $id }}" {{ old('course_id') == $id ? 'selected' : '' }}>{{ $title }}</option>
                                        @endforeach
                                    </select>
                                    @error('course_id')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="tags">Etiquetas</label>
                                    <input type="text" class="form-control @error('tags') is-invalid @enderror" id="tags" name="tags" value="{{ old('tags') }}" placeholder="Separadas por comas (ej: programación, javascript, tutorial)">
                                    @error('tags')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="row" id="url-input" style="display: none;">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="url">URL <span class="text-danger">*</span></label>
                                    <input type="url" class="form-control @error('url') is-invalid @enderror" id="url" name="url" value="{{ old('url') }}">
                                    @error('url')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="row" id="file-input" style="display: none;">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="file">Archivo <span class="text-danger">*</span></label>
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input @error('file') is-invalid @enderror" id="file" name="file">
                                        <label class="custom-file-label" for="file">Seleccionar archivo</label>
                                    </div>
                                    <small class="form-text text-muted">Tamaño máximo: 10MB</small>
                                    @error('file')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" class="custom-control-input" id="is_public" name="is_public" value="1" {{ old('is_public') ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="is_public">Recurso Público</label>
                                    </div>
                                    <small class="form-text text-muted">Si está activado, el recurso será visible para todos los estudiantes.</small>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" class="custom-control-input" id="is_premium" name="is_premium" value="1" {{ old('is_premium') ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="is_premium">Recurso Premium</label>
                                    </div>
                                    <small class="form-text text-muted">Si está activado, solo los estudiantes con suscripción premium podrán acceder.</small>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group mt-4">
                            <button type="submit" class="btn btn-primary">Guardar Recurso</button>
                            <a href="{{ route('mentor.resources') }}" class="btn btn-secondary">Cancelar</a>
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
        // Mostrar/ocultar campos según el tipo de recurso
        $('#type').change(function() {
            var type = $(this).val();
            
            // Ocultar todos los campos específicos
            $('#url-input').hide();
            $('#file-input').hide();
            
            // Mostrar campos según el tipo seleccionado
            if (type === 'link' || type === 'video') {
                $('#url-input').show();
                $('#url').prop('required', true);
                $('#file').prop('required', false);
            } else if (type === 'document' || type === 'presentation' || type === 'exercise') {
                $('#file-input').show();
                $('#file').prop('required', true);
                $('#url').prop('required', false);
            }
        });
        
        // Ejecutar al cargar la página para mostrar los campos correctos
        $('#type').trigger('change');
        
        // Mostrar nombre del archivo seleccionado
        $('.custom-file-input').on('change', function() {
            var fileName = $(this).val().split('\\').pop();
            $(this).next('.custom-file-label').addClass("selected").html(fileName);
        });
    });
</script>
@endsection
