@extends('layouts.admin')

@section('title', 'Editar Rol')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h2 class="mb-0">Editar Rol: {{ $role->display_name }}</h2>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.roles.update', $role->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Nombre del Rol *</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                           id="name" name="name" value="{{ old('name', $role->name) }}" 
                                           {{ $role->is_system ? 'readonly' : 'required' }}>
                                    <div class="form-text">Solo letras, números, guiones y guiones bajos. Sin espacios.</div>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="display_name" class="form-label">Nombre para Mostrar *</label>
                                    <input type="text" class="form-control @error('display_name') is-invalid @enderror" 
                                           id="display_name" name="display_name" 
                                           value="{{ old('display_name', $role->display_name) }}" required>
                                    @error('display_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Descripción</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="2">{{ old('description', $role->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="card mb-4">
                            <div class="card-header bg-light">
                                <h5 class="mb-0">Permisos</h5>
                                <div class="form-check mt-2">
                                    <input class="form-check-input" type="checkbox" id="select-all">
                                    <label class="form-check-label" for="select-all">
                                        Seleccionar todos
                                    </label>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    @foreach($permissions->groupBy('group') as $group => $groupPermissions)
                                        <div class="col-md-4 mb-4">
                                            <div class="card h-100">
                                                <div class="card-header bg-light">
                                                    <h6 class="mb-0">{{ ucfirst($group) }}</h6>
                                                </div>
                                                <div class="card-body">
                                                    @foreach($groupPermissions as $permission)
                                                        <div class="form-check">
                                                            <input class="form-check-input permission-checkbox" 
                                                                   type="checkbox" 
                                                                   id="permission-{{ $permission->id }}" 
                                                                   name="permissions[]" 
                                                                   value="{{ $permission->id }}"
                                                                   {{ in_array($permission->id, old('permissions', $rolePermissions)) ? 'checked' : '' }}>
                                                            <label class="form-check-label" for="permission-{{ $permission->id }}">
                                                                {{ $permission->display_name }}
                                                                <small class="d-block text-muted">{{ $permission->description }}</small>
                                                            </label>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('admin.roles.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-1"></i> Volver
                            </a>
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

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const selectAllCheckbox = document.getElementById('select-all');
        const permissionCheckboxes = document.querySelectorAll('.permission-checkbox');
        
        // Select all permissions
        selectAllCheckbox.addEventListener('change', function() {
            permissionCheckboxes.forEach(checkbox => {
                checkbox.checked = selectAllCheckbox.checked;
            });
        });
        
        // Update "select all" checkbox state based on individual checkboxes
        function updateSelectAllCheckbox() {
            let allChecked = true;
            permissionCheckboxes.forEach(checkbox => {
                if (!checkbox.checked) {
                    allChecked = false;
                }
            });
            selectAllCheckbox.checked = allChecked;
        }
        
        permissionCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', updateSelectAllCheckbox);
        });
        
        // Initial check
        updateSelectAllCheckbox();
    });
</script>
@endsection
