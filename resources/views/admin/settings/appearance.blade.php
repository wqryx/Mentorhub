@extends('admin.settings.layout')

@section('title', 'Apariencia')

@push('styles')
<style>
    .theme-option {
        transition: all 0.2s ease-in-out;
    }
    .theme-option:hover {
        transform: translateY(-2px);
    }
    .theme-option input:checked + div {
        border-color: #3b82f6;
        box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.5);
    }
</style>
@endpush

@section('content')
    <form id="appearanceForm" action="{{ route('admin.settings.appearance.update') }}" method="POST" class="space-y-8">
        @csrf
        @method('PUT')
        <!-- Hidden fields to ensure boolean values are always sent -->
        <input type="hidden" name="enable_animations" value="{{ $settings['enable_animations'] ? '1' : '0' }}">
        <input type="hidden" name="sidebar_collapsed" value="{{ $settings['sidebar_collapsed'] ? '1' : '0' }}">
        <input type="hidden" name="header_fixed" value="{{ $settings['header_fixed'] ? '1' : '0' }}">
        <input type="hidden" name="footer_fixed" value="{{ $settings['footer_fixed'] ? '1' : '0' }}">
        <input type="hidden" name="theme" value="{{ $current_theme }}">
        
        <!-- Mensajes de error -->
        @if($errors->any())
            <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded relative" role="alert">
                <strong class="font-bold">¡Error!</strong>
                <span class="block sm:inline">
                    @foreach($errors->all() as $error)
                        {{ $error }}
                    @endforeach
                </span>
            </div>
        @endif
        
        @if(session('success'))
            <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded relative" role="alert">
                <strong class="font-bold">¡Éxito!</strong>
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif
        
        <!-- Sección de Tema -->
        <div class="bg-white shadow rounded-lg p-6">
            <div class="mb-6">
                <h3 class="text-lg font-medium text-gray-900">Tema</h3>
                <p class="mt-1 text-sm text-gray-500">Elige entre el tema claro u oscuro para el panel de administración.</p>
            </div>

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                @foreach($themes as $value => $label)
                    <label class="theme-option block cursor-pointer">
                        <input type="radio" name="theme" value="{{ $value }}" 
                               class="sr-only" 
                               {{ $current_theme === $value ? 'checked' : '' }}>
                        <div class="p-4 border-2 rounded-lg {{ $current_theme === $value ? 'border-blue-500' : 'border-gray-200' }}">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-12 w-12 rounded-full flex items-center justify-center 
                                    {{ $value === 'light' ? 'bg-gray-100' : 'bg-gray-800' }}">
                                    @if($value === 'light')
                                        <i class="fas fa-sun text-yellow-500 text-xl"></i>
                                    @else
                                        <i class="fas fa-moon text-white text-xl"></i>
                                    @endif
                                </div>
                                <div class="ml-4">
                                    <p class="text-base font-medium text-gray-900">{{ $label }}</p>
                                    <p class="text-sm text-gray-500">
                                        {{ $value === 'light' ? 'Tema claro y brillante' : 'Tema oscuro y relajante' }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </label>
                @endforeach
            </div>
            @error('theme')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror

        </div>

        <!-- Sección de Opciones de Interfaz -->
        <div class="bg-white shadow rounded-lg p-6">
            <div class="mb-6">
                <h3 class="text-lg font-medium text-gray-900">Opciones de Interfaz</h3>
                <p class="mt-1 text-sm text-gray-500">Personaliza el comportamiento de la interfaz de administración.</p>
            </div>

            <div class="space-y-6">
                <div class="relative flex items-start">
                    <div class="flex items-center h-5">
                        <input id="enable_animations" name="enable_animations" type="checkbox" 
                               class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300 rounded"
                               value="1" {{ $settings['enable_animations'] ? 'checked' : '' }}
                               onchange="this.value = this.checked ? '1' : '0';">
                    </div>
                    <div class="ml-3 text-sm">
                        <label for="enable_animations" class="font-medium text-gray-700">Habilitar animaciones</label>
                        <p class="text-gray-500">Activa las transiciones y animaciones en la interfaz para una experiencia más fluida.</p>
                    </div>
                </div>

                <div class="relative flex items-start">
                    <div class="flex items-center h-5">
                        <input id="sidebar_collapsed" name="sidebar_collapsed" type="checkbox" 
                               class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300 rounded"
                               value="1" {{ $settings['sidebar_collapsed'] ? 'checked' : '' }}
                               onchange="this.value = this.checked ? '1' : '0';">
                    </div>
                    <div class="ml-3 text-sm">
                        <label for="sidebar_collapsed" class="font-medium text-gray-700">Barra lateral colapsada</label>
                        <p class="text-gray-500">Muestra la barra lateral colapsada por defecto al cargar la página.</p>
                    </div>
                </div>

                <div class="relative flex items-start">
                    <div class="flex items-center h-5">
                        <input id="header_fixed" name="header_fixed" type="checkbox" 
                               class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300 rounded"
                               value="1" {{ $settings['header_fixed'] ? 'checked' : '' }}
                               onchange="this.value = this.checked ? '1' : '0';">
                    </div>
                    <div class="ml-3 text-sm">
                        <label for="header_fixed" class="font-medium text-gray-700">Encabezado fijo</label>
                        <p class="text-gray-500">Mantén el encabezado visible en la parte superior al hacer scroll.</p>
                    </div>
                </div>

                <div class="relative flex items-start">
                    <div class="flex items-center h-5">
                        <input id="footer_fixed" name="footer_fixed" type="checkbox" 
                               class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300 rounded"
                               value="1" {{ $settings['footer_fixed'] ? 'checked' : '' }}
                               onchange="this.value = this.checked ? '1' : '0';">
                    </div>
                    <div class="ml-3 text-sm">
                        <label for="footer_fixed" class="font-medium text-gray-700">Pie de página fijo</label>
                        <p class="text-gray-500">Mantén el pie de página visible en la parte inferior de la ventana.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Acciones del formulario -->
        <div class="flex justify-end space-x-3">
            <button type="button" 
                    class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                Restablecer valores por defecto
            </button>
            <button type="submit" 
                    class="inline-flex justify-center py-2 px-6 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                Guardar cambios
            </button>
        </div>
    </form>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('appearanceForm');
            const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
            const themeContainer = document.getElementById('theme-container');
            
            // Función para actualizar los campos ocultos
            function updateHiddenFields() {
                // Actualizar el tema seleccionado
                const selectedTheme = document.querySelector('input[name="theme"]:checked');
                if (selectedTheme) {
                    form.querySelector('input[name="theme"]').value = selectedTheme.value;
                }
                
                // Actualizar checkboxes
                document.querySelectorAll('input[type="checkbox"]').forEach(checkbox => {
                    const hiddenInput = form.querySelector(`input[type="hidden"][name="${checkbox.name}"]`);
                    if (hiddenInput) {
                        hiddenInput.value = checkbox.checked ? '1' : '0';
                    }
                });
            }
            
            // Aplicar tema inmediatamente al cambiar la selección
            document.querySelectorAll('input[name="theme"]').forEach(radio => {
                radio.addEventListener('change', function() {
                    updateThemePreview(this.value);
                    // Enviar el formulario automáticamente al cambiar el tema
                    form.dispatchEvent(new Event('submit'));
                });
            });

            // Manejar el botón de restablecer valores por defecto
            document.querySelector('button[type="button"]').addEventListener('click', function() {
                if (confirm('¿Estás seguro de que deseas restablecer los valores por defecto?')) {
                    // Establecer valores por defecto
                    document.querySelector('input[value="light"]').checked = true;
                    document.getElementById('enable_animations').checked = true;
                    document.getElementById('sidebar_collapsed').checked = false;
                    document.getElementById('header_fixed').checked = true;
                    document.getElementById('footer_fixed').checked = false;
                    
                    // Actualizar campos ocultos
                    updateHiddenFields();
                    
                    // Asegurar que el tema por defecto sea 'light' si no está definido
                    const currentTheme = document.querySelector('input[name="theme"]:checked').value;
                    if (!in_array(currentTheme, ['light', 'dark'])) {
                        document.querySelector('input[value="light"]').checked = true;
                    }
                    
                    // Enviar formulario
                    form.submit();
                }
            });

            // Actualizar la vista previa del tema
            function updateThemePreview(theme) {
                if (!theme) return;
                
                // Guardar en localStorage para persistencia
                localStorage.setItem('admin_theme', theme);
                
                // Actualizar el atributo data-theme y clases en el body
                if (themeContainer) {
                    // Actualizar atributos
                    themeContainer.setAttribute('data-theme', theme);
                    themeContainer.className = themeContainer.className.replace(/theme-\w+/, '') + ' theme-' + theme;
                    
                    // Actualizar variables CSS
                    const root = document.documentElement;
                    const isDark = theme === 'dark';
                    
                    // Actualizar variables de tema
                    root.style.setProperty('--theme-primary', isDark ? '#5a7bfc' : '#4361ee');
                    root.style.setProperty('--theme-bg', isDark ? '#1a1a1a' : '#ffffff');
                    root.style.setProperty('--theme-text', isDark ? '#f8f9fa' : '#212529');
                    root.style.setProperty('--theme-border', isDark ? '#495057' : '#dee2e6');
                    
                    // Actualizar clases de tema en el documento
                    document.documentElement.classList.remove('light', 'dark');
                    document.documentElement.classList.add(theme);
                }
                
                // Actualizar el tema en el formulario
                const themeInput = form.querySelector('input[name="theme"]');
                if (themeInput) {
                    themeInput.value = theme;
                }
                
                // Actualizar el radio button seleccionado
                document.querySelectorAll('input[name="theme"]').forEach(radio => {
                    radio.checked = (radio.value === theme);
                });
                
                // Forzar actualización de estilos
                updateThemeVariables(theme);
                
                // Disparar evento personalizado para otros componentes
                document.dispatchEvent(new CustomEvent('theme-changed', { detail: { theme } }));
                
                // Forzar actualización del layout
                document.body.offsetHeight;
            }
            
            // Actualizar variables CSS según el tema
            function updateThemeVariables(theme) {
                const root = document.documentElement;
                const isDark = theme === 'dark';
                
                // Actualizar variables CSS personalizadas
                root.style.setProperty('--bg-primary', isDark ? '#1a1a1a' : '#ffffff');
                root.style.setProperty('--bg-secondary', isDark ? '#2d2d2d' : '#f8f9fa');
                root.style.setProperty('--bg-tertiary', isDark ? '#3d3d3d' : '#e9ecef');
                root.style.setProperty('--text-primary', isDark ? '#f8f9fa' : '#212529');
                root.style.setProperty('--text-secondary', isDark ? '#adb5bd' : '#6c757d');
                root.style.setProperty('--border-color', isDark ? '#495057' : '#dee2e6');
                root.style.setProperty('--primary', isDark ? '#5a7bfc' : '#4361ee');
                root.style.setProperty('--primary-hover', isDark ? '#4361ee' : '#3a56d4');
                root.style.setProperty('--sidebar-bg', isDark ? '#1e293b' : '#2c3e50');
                root.style.setProperty('--sidebar-text', isDark ? '#e2e8f0' : '#ecf0f1');
                root.style.setProperty('--sidebar-hover', isDark ? '#334155' : '#34495e');
                root.style.setProperty('--header-bg', isDark ? '#1a1a1a' : '#ffffff');
                root.style.setProperty('--header-text', isDark ? '#f8f9fa' : '#212529');
            }

            // Manejar cambios en los checkboxes
            document.querySelectorAll('input[type="checkbox"]').forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    const hiddenInput = form.querySelector(`input[type="hidden"][name="${this.name}"]`);
                    if (hiddenInput) {
                        hiddenInput.value = this.checked ? '1' : '0';
                    }
                });
            });

            // Manejar el envío del formulario
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                
                // Mostrar indicador de carga
                const submitButton = form.querySelector('button[type="submit"]');
                const originalButtonText = submitButton.innerHTML;
                submitButton.disabled = true;
                submitButton.innerHTML = 'Guardando...';
                
                // Actualizar campos ocultos
                updateHiddenFields();
                
                // Enviar formulario vía AJAX
                fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                        'X-CSRF-TOKEN': csrfToken,
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    },
                    body: new URLSearchParams(new FormData(form))
                })
                .then(response => {
                    if (!response.ok) {
                        return response.json().then(err => { throw err; });
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        // Mostrar mensaje de éxito
                        showAlert('success', data.message);
                        
                        // Actualizar la vista con los nuevos valores
                        if (data.theme) {
                            document.querySelector(`input[name="theme"][value="${data.theme}"]`).checked = true;
                            updateThemePreview(data.theme);
                        }
                        
                        // Recargar la página para asegurar que todos los cambios se apliquen
                        setTimeout(() => {
                            window.location.reload();
                        }, 1000);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    let errorMessage = 'Ocurrió un error al guardar los cambios';
                    
                    if (error.errors) {
                        // Mostrar errores de validación
                        const errorMessages = Object.values(error.errors).flat();
                        errorMessage = errorMessages.join('\n');
                    } else if (error.message) {
                        errorMessage = error.message;
                    }
                    
                    showAlert('error', errorMessage);
                })
                .finally(() => {
                    // Restaurar el botón
                    submitButton.disabled = false;
                    submitButton.innerHTML = originalButtonText;
                });
            });

            // Función para mostrar alertas
            function showAlert(type, message) {
                // Eliminar alertas existentes
                const existingAlerts = document.querySelectorAll('.alert-message');
                existingAlerts.forEach(alert => alert.remove());
                
                const alertDiv = document.createElement('div');
                alertDiv.className = `alert-message mb-4 p-4 rounded relative ${
                    type === 'success' ? 'bg-green-100 border-green-400 text-green-700' : 
                    'bg-red-100 border-red-400 text-red-700'
                }`;
                alertDiv.role = 'alert';
                
                alertDiv.innerHTML = `
                    <strong class="font-bold">${type === 'success' ? '¡Éxito!' : '¡Error!'}</strong>
                    <span class="block sm:inline">${message}</span>
                    <span class="absolute top-0 bottom-0 right-0 px-4 py-3">
                        <svg class="fill-current h-6 w-6 ${type === 'success' ? 'text-green-500' : 'text-red-500'}" 
                             role="button" 
                             xmlns="http://www.w3.org/2000/svg" 
                             viewBox="0 0 20 20"
                             onclick="this.parentElement.parentElement.remove()">
                            <path d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z"/>
                        </svg>
                    </span>`;
                
                // Insertar el mensaje antes del formulario
                form.parentNode.insertBefore(alertDiv, form);
                
                // Desaparecer después de 5 segundos
                setTimeout(() => {
                    alertDiv.remove();
                }, 5000);
            }

            // Aplicar el tema guardado al cargar la página
            const savedTheme = localStorage.getItem('admin_theme') || '{{ $current_theme }}' || 'light';
            
            // Asegurarse de que el tema sea válido
            const validTheme = ['light', 'dark'].includes(savedTheme) ? savedTheme : 'light';
            
            // Aplicar el tema
            updateThemePreview(validTheme);
            
            // Asegurarse de que el radio button correcto esté seleccionado
            const themeRadio = document.querySelector(`input[name="theme"][value="${validTheme}"]`);
            if (themeRadio) {
                themeRadio.checked = true;
            }
        });
    </script>
    @endpush
@endsection
