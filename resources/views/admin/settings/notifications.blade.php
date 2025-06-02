@extends('admin.settings.layout')

@section('content')
<div class="px-4 py-5 sm:p-6">
    <form action="{{ route('admin.settings.notifications.update') }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="space-y-6">
            <!-- Email Notifications -->
            <div class="bg-white shadow px-4 py-5 sm:rounded-lg sm:p-6">
                <div class="md:grid md:grid-cols-3 md:gap-6">
                    <div class="md:col-span-1">
                        <h3 class="text-lg font-medium leading-6 text-gray-900">Notificaciones por Correo</h3>
                        <p class="mt-1 text-sm text-gray-500">Configura las preferencias de notificaciones por correo electrónico.</p>
                    </div>
                    <div class="mt-5 md:mt-0 md:col-span-2">
                        <div class="space-y-5">
                            <div class="flex items-start">
                                <div class="flex items-center h-5">
                                    <input id="enable_email" name="enable_email" type="checkbox" class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300 rounded" 
                                           {{ old('enable_email', $settings['enable_email_notifications'] ?? true) ? 'checked' : '' }}>
                                </div>
                                <div class="ml-3 text-sm">
                                    <label for="enable_email" class="font-medium text-gray-700">Activar notificaciones por correo</label>
                                    <p class="text-gray-500">Recibir notificaciones por correo electrónico.</p>
                                </div>
                            </div>
                            
                            <div class="pl-7">
                                <label for="notification_email" class="block text-sm font-medium text-gray-700">Correo para notificaciones</label>
                                <input type="email" name="notification_email" id="notification_email" 
                                       value="{{ old('notification_email', $settings['notification_email'] ?? '') }}"
                                       class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                @error('notification_email')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Notification Types -->
            <div class="bg-white shadow px-4 py-5 sm:rounded-lg sm:p-6">
                <div class="md:grid md:grid-cols-3 md:gap-6">
                    <div class="md:col-span-1">
                        <h3 class="text-lg font-medium leading-6 text-gray-900">Tipos de Notificaciones</h3>
                        <p class="mt-1 text-sm text-gray-500">Selecciona qué notificaciones deseas recibir.</p>
                    </div>
                    <div class="mt-5 md:mt-0 md:col-span-2">
                        <div class="space-y-5">
                            <div class="flex items-start">
                                <div class="flex items-center h-5">
                                    <input id="new_user_notification" name="new_user_notification" type="checkbox" 
                                           class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300 rounded"
                                           {{ old('new_user_notification', $settings['notify_new_users'] ?? true) ? 'checked' : '' }}>
                                </div>
                                <div class="ml-3 text-sm">
                                    <label for="new_user_notification" class="font-medium text-gray-700">Nuevos Usuarios</label>
                                    <p class="text-gray-500">Recibir notificación cuando un nuevo usuario se registre.</p>
                                </div>
                            </div>
                            
                            <div class="flex items-start">
                                <div class="flex items-center h-5">
                                    <input id="new_course_notification" name="new_course_notification" type="checkbox" 
                                           class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300 rounded"
                                           {{ old('new_course_notification', $settings['notify_new_courses'] ?? true) ? 'checked' : '' }}>
                                </div>
                                <div class="ml-3 text-sm">
                                    <label for="new_course_notification" class="font-medium text-gray-700">Nuevos Cursos</label>
                                    <p class="text-gray-500">Recibir notificación cuando se cree un nuevo curso.</p>
                                </div>
                            </div>
                            
                            <div class="flex items-start">
                                <div class="flex items-center h-5">
                                    <input id="new_payment_notification" name="new_payment_notification" type="checkbox" 
                                           class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300 rounded"
                                           {{ old('new_payment_notification', $settings['notify_new_payments'] ?? true) ? 'checked' : '' }}>
                                </div>
                                <div class="ml-3 text-sm">
                                    <label for="new_payment_notification" class="font-medium text-gray-700">Nuevos Pagos</label>
                                    <p class="text-gray-500">Recibir notificación cuando se reciba un nuevo pago.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Actions -->
            <div class="flex justify-end">
                <button type="button" class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Cancelar
                </button>
                <button type="submit" class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Guardar Cambios
                </button>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
    // Habilitar/deshabilitar el campo de correo según el estado del checkbox
    document.addEventListener('DOMContentLoaded', function() {
        const emailCheckbox = document.getElementById('enable_email');
        const emailField = document.getElementById('notification_email');
        
        function toggleEmailField() {
            emailField.disabled = !emailCheckbox.checked;
        }
        
        // Configurar el estado inicial
        toggleEmailField();
        
        // Configurar el evento change
        emailCheckbox.addEventListener('change', toggleEmailField);
    });
</script>
@endpush
@endsection
