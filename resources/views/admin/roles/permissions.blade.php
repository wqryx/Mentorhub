@extends('layouts.admin')

@section('title', 'Gestión de Permisos')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h2 class="mb-0">Gestión de Permisos</h2>
                    <div class="d-flex gap-2">
                        <a href="{{ route('admin.roles.index') }}" class="btn btn-outline-primary">
                            <i class="fas fa-arrow-left me-1"></i> Volver a Roles
                        </a>
                        <button type="button" class="btn btn-outline-success" id="generatePermissions">
                            <i class="fas fa-magic me-1"></i> Generar Permisos
                        </button>
                        <button type="button" class="btn btn-primary" id="savePermissions">
                            <i class="fas fa-save me-1"></i> Guardar Cambios
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    {{-- Mensajes de éxito/error --}}
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif

                    {{-- Instrucciones --}}
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        Selecciona los permisos que deseas asignar a cada rol. Los cambios se guardarán al hacer clic en "Guardar Cambios".
                    </div>

                    {{-- Tabla de permisos --}}
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th style="width: 30%; min-width: 250px;">Permiso</th>
                                    @foreach($roles as $role)
                                        @php
                                            $isHighlighted = isset($highlightRoleId) && $role->id == $highlightRoleId;
                                            $thClass = $isHighlighted ? 'bg-warning bg-opacity-25' : '';
                                        @endphp
                                        <th class="text-center {{ $thClass }}" style="min-width: 120px;" data-role-id="{{ $role->id }}">
                                            <div class="d-flex flex-column align-items-center">
                                                <span>{{ $role->display_name ?? $role->name }}</span>
                                                @if($role->is_system)
                                                    <small class="text-muted">
                                                        <i class="fas fa-lock me-1"></i>Sistema
                                                    </small>
                                                @endif
                                            </div>
                                        </th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($permissions as $group => $groupPermissions)
                                    <tr class="table-secondary">
                                        <td colspan="{{ count($roles) + 1 }}">
                                            <strong>{{ $group }}</strong>
                                        </td>
                                    </tr>
                                    @include('admin.roles._permission_rows', ['permissions' => $groupPermissions, 'roles' => $roles])
                                @empty
                                    <tr>
                                        <td colspan="{{ count($roles) + 1 }}" class="text-center">
                                            No se encontraron permisos.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de confirmación -->
<div class="modal fade" id="confirmModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirmar acción</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <p id="confirmMessage">¿Estás seguro de que deseas continuar?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="confirmAction">Confirmar</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Inicializar tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });

        // Manejar el clic en el botón de guardar
        const saveButton = document.getElementById('savePermissions');
        if (saveButton) {
            saveButton.addEventListener('click', savePermissions);
        }

        // Función para guardar los permisos
        function savePermissions() {
            const permissionsData = [];
            const checkboxes = document.querySelectorAll('.permission-checkbox:not(:disabled)');
            
            // Agrupar los permisos por ID
            const permissionsMap = new Map();
            
            checkboxes.forEach(checkbox => {
                const permissionId = checkbox.dataset.permissionId;
                const roleId = checkbox.dataset.roleId;
                
                if (!permissionsMap.has(permissionId)) {
                    permissionsMap.set(permissionId, {
                        id: permissionId,
                        roles: []
                    });
                }
                
                if (checkbox.checked) {
                    permissionsMap.get(permissionId).roles.push(roleId);
                }
            });
            
            // Convertir el mapa a array
            const permissions = Array.from(permissionsMap.values());
            
            // Mostrar indicador de carga
            const originalText = saveButton.innerHTML;
            saveButton.disabled = true;
            saveButton.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Guardando...';
            
            // Enviar los datos al servidor
            fetch('{{ route("admin.permissions.sync") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ permissions })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showToast('¡Éxito!', 'Los permisos se han guardado correctamente.', 'success');
                } else {
                    throw new Error(data.message || 'Error al guardar los permisos');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('Error', error.message || 'Ocurrió un error al guardar los permisos', 'error');
            })
            .finally(() => {
                // Restaurar el botón
                saveButton.disabled = false;
                saveButton.innerHTML = originalText;
            });
        }
        
        // Función para mostrar notificaciones toast
        function showToast(title, message, type = 'info') {
            // Usando Toastr si está disponible, de lo contrario usar alert
            if (window.toastr) {
                toastr[type](message, title);
            } else if (window.Swal) {
                Swal.fire({
                    title: title,
                    text: message,
                    icon: type,
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000
                });
            } else {
                alert(`[${type.toUpperCase()}] ${title}: ${message}`);
            }
        }
        
        // Manejar el clic en el botón de generar permisos
        const generateButton = document.getElementById('generatePermissions');
        if (generateButton) {
            generateButton.addEventListener('click', function() {
                // Mostrar modal de confirmación
                const confirmModal = new bootstrap.Modal(document.getElementById('confirmModal'));
                document.getElementById('confirmMessage').textContent = '¿Estás seguro de que deseas generar automáticamente los permisos? Esta acción creará permisos para todos los modelos del sistema.';
                
                // Configurar acción de confirmación
                document.getElementById('confirmAction').onclick = function() {
                    confirmModal.hide();
                    generateSystemPermissions();
                };
                
                confirmModal.show();
            });
        }
        
        // Función para generar permisos del sistema
        function generateSystemPermissions() {
            // Mostrar indicador de carga
            const originalText = generateButton.innerHTML;
            generateButton.disabled = true;
            generateButton.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Generando...';
            
            // Enviar solicitud al servidor
            fetch('{{ route("admin.permissions.generate") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showToast('¡Éxito!', 'Los permisos se han generado correctamente. Recargando página...', 'success');
                    // Recargar la página después de un breve retraso
                    setTimeout(() => {
                        window.location.reload();
                    }, 1500);
                } else {
                    throw new Error(data.message || 'Error al generar los permisos');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('Error', error.message || 'Ocurrió un error al generar los permisos', 'error');
            })
            .finally(() => {
                // Restaurar el botón
                generateButton.disabled = false;
                generateButton.innerHTML = originalText;
            });
        }
    });
</script>
@endsection
