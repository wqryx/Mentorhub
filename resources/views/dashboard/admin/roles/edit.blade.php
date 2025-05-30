@extends('dashboard.admin.layouts.app')

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
                                    <input class="form-check-input" type="checkbox" id="selectAll" {{ count($rolePermissions) === count(collect($permissions)->flatten()) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="selectAll">
                                        Seleccionar Todos
                                    </label>
                                </div>
                            </div>
                            <div class="card-body">
                                @foreach($permissions as $group => $groupPermissions)
                                    <div class="mb-4">
                                        <h6 class="text-uppercase text-muted mb-3">{{ $group }}</h6>
                                        <div class="row">
                                            @foreach($groupPermissions as $permission)
                                                <div class="col-md-3 mb-2">
                                                    <div class="form-check">
                                                        <input class="form-check-input permission-checkbox" 
                                                               type="checkbox" 
                                                               name="permissions[]" 
                                                               value="{{ $permission->id }}"
                                                               id="permission_{{ $permission->id }}"
                                                               {{ in_array($permission->id, old('permissions', $rolePermissions)) ? 'checked' : '' }}
                                                               {{ $role->is_system && $permission->name === 'admin.access' ? 'disabled' : '' }}>
                                                        <label class="form-check-label" for="permission_{{ $permission->id }}">
                                                            {{ $permission->display_name ?? $permission->name }}
                                                        </label>
                                                    </div>
                                                    @if($permission->description)
                                                        <small class="text-muted d-block ps-4">
                                                            {{ $permission->description }}
                                                        </small>
                                                    @endif
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                    @if(!$loop->last)
                                        <hr>
                                    @endif
                                @endforeach
                                @error('permissions')
                                    <div class="text-danger mt-2">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('admin.roles.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-1"></i> Volver
                            </a>
                            <div>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-1"></i> Actualizar Rol
                                </button>
                                @if(!$role->is_system)
                                    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteRoleModal">
                                        <i class="fas fa-trash me-1"></i> Eliminar
                                    </button>
                                @endif
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de confirmación de eliminación -->
@if(!$role->is_system)
<div class="modal fade" id="deleteRoleModal" tabindex="-1" aria-labelledby="deleteRoleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteRoleModalLabel">Confirmar Eliminación</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                ¿Estás seguro de que deseas eliminar el rol <strong>{{ $role->display_name }}</strong>? Esta acción no se puede deshacer.
                
                @if($role->users_count > 0)
                    <div class="alert alert-warning mt-3 mb-0">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Este rol está asignado a {{ $role->users_count }} usuario(s). Si lo eliminas, estos usuarios perderán los permisos asociados.
                    </div>
                @endif
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <form action="{{ route('admin.roles.destroy', $role->id) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash me-1"></i> Eliminar Rol
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endif

@push('scripts')
<script>
    // Seleccionar/Deseleccionar todos los permisos
    document.getElementById('selectAll').addEventListener('change', function() {
        const checkboxes = document.querySelectorAll('.permission-checkbox:not([disabled])');
        checkboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
    });

    // Validar que al menos un permiso esté seleccionado
    document.querySelector('form').addEventListener('submit', function(e) {
        const checkboxes = document.querySelectorAll('.permission-checkbox:checked');
        if (checkboxes.length === 0) {
            e.preventDefault();
            alert('Por favor, selecciona al menos un permiso.');
        }
    });

    // Actualizar el estado de "Seleccionar todos" cuando se marcan/desmarcan permisos individuales
    document.querySelectorAll('.permission-checkbox').forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const allCheckboxes = document.querySelectorAll('.permission-checkbox:not([disabled])');
            const checkedCheckboxes = document.querySelectorAll('.permission-checkbox:checked:not([disabled])');
            
            document.getElementById('selectAll').checked = 
                allCheckboxes.length > 0 && checkedCheckboxes.length === allCheckboxes.length;
        });
    });
</script>
@endpush

@endsection
