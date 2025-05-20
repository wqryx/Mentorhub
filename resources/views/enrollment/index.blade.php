@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="bg-white rounded-lg shadow-lg p-6">
        <h1 class="text-2xl font-bold mb-6">Formulario de Inscripción</h1>

        <div class="space-y-6">
            <div class="bg-gray-50 p-4 rounded-lg">
                <h2 class="font-semibold mb-4">Información Personal</h2>
                <form id="enrollmentForm" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Datos Personales -->
                        <div>
                            <h3 class="font-medium mb-4">Datos Personales</h3>
                            <div class="space-y-4">
                                <div>
                                    <label for="course" class="block text-sm font-medium text-gray-700">Curso</label>
                                    <input type="text" name="course" id="course" 
                                           value="{{ old('course', $user->course) }}" 
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                                </div>
                                <div>
                                    <label for="cycle" class="block text-sm font-medium text-gray-700">Ciclo</label>
                                    <input type="text" name="cycle" id="cycle" 
                                           value="{{ old('cycle', $user->cycle) }}" 
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                                </div>
                                <div>
                                    <label for="academic_year" class="block text-sm font-medium text-gray-700">Año Académico</label>
                                    <input type="text" name="academic_year" id="academic_year" 
                                           value="{{ old('academic_year', $user->academic_year) }}" 
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                                </div>
                                <div>
                                    <label for="identification_type" class="block text-sm font-medium text-gray-700">Tipo de Identificación</label>
                                    <select name="identification_type" id="identification_type" 
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                                        <option value="">Selecciona...</option>
                                        <option value="DNI" {{ old('identification_type', $user->identification_type) === 'DNI' ? 'selected' : '' }}>DNI</option>
                                        <option value="Pasaporte" {{ old('identification_type', $user->identification_type) === 'Pasaporte' ? 'selected' : '' }}>Pasaporte</option>
                                    </select>
                                </div>
                                <div>
                                    <label for="identification_number" class="block text-sm font-medium text-gray-700">Número de Identificación</label>
                                    <input type="text" name="identification_number" id="identification_number" 
                                           value="{{ old('identification_number', $user->identification_number) }}" 
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                                </div>
                                <div>
                                    <label for="birth_date" class="block text-sm font-medium text-gray-700">Fecha de Nacimiento</label>
                                    <input type="date" name="birth_date" id="birth_date" 
                                           value="{{ old('birth_date', $user->birth_date) }}" 
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                                </div>
                            </div>
                        </div>

                        <!-- Contacto y Emergencia -->
                        <div>
                            <h3 class="font-medium mb-4">Contacto y Emergencia</h3>
                            <div class="space-y-4">
                                <div>
                                    <label for="phone" class="block text-sm font-medium text-gray-700">Teléfono</label>
                                    <input type="tel" name="phone" id="phone" 
                                           value="{{ old('phone', $user->phone) }}" 
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                                </div>
                                <div>
                                    <label for="address" class="block text-sm font-medium text-gray-700">Dirección</label>
                                    <textarea name="address" id="address" rows="3"
                                              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                                        {{ old('address', $user->address) }}
                                    </textarea>
                                </div>
                                <div>
                                    <label for="emergency_contact" class="block text-sm font-medium text-gray-700">Contacto de Emergencia</label>
                                    <input type="text" name="emergency_contact" id="emergency_contact" 
                                           value="{{ old('emergency_contact', $user->emergency_contact) }}" 
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                                </div>
                                <div>
                                    <label for="emergency_phone" class="block text-sm font-medium text-gray-700">Teléfono de Emergencia</label>
                                    <input type="tel" name="emergency_phone" id="emergency_phone" 
                                           value="{{ old('emergency_phone', $user->emergency_phone) }}" 
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Documentos -->
                    <div class="mt-8">
                        <h3 class="font-medium mb-4">Documentos</h3>
                        <div class="space-y-4">
                            <div>
                                <label for="photo" class="block text-sm font-medium text-gray-700">Foto de Perfil</label>
                                <input type="file" name="photo" id="photo" 
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                            </div>
                            <div>
                                <label for="documents" class="block text-sm font-medium text-gray-700">Documentos Adicionales</label>
                                <input type="file" name="documents[]" id="documents" multiple 
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                <p class="mt-1 text-sm text-gray-500">Puedes subir múltiples documentos (máximo 2MB por archivo)</p>
                            </div>
                        </div>
                    </div>

                    <!-- Botones -->
                    <div class="mt-8 flex justify-end space-x-4">
                        <button type="button" onclick="verifyEnrollment()" 
                                class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                            <i class="fas fa-check mr-2"></i> Verificar Datos
                        </button>
                        <button type="submit" id="submitBtn" disabled 
                                class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                            <i class="fas fa-paper-plane mr-2"></i> Enviar Inscripción
                        </button>
                    </div>
                </form>
            </div>

            <!-- Mensajes de estado -->
            <div id="statusMessages" class="hidden bg-gray-50 p-4 rounded-lg">
                <!-- Los mensajes se agregarán aquí dinámicamente -->
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    async function verifyEnrollment() {
        const formData = new FormData(document.getElementById('enrollmentForm'));
        
        try {
            const response = await fetch('{{ route('enrollment.verify') }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            });

            const result = await response.json();
            
            const statusDiv = document.getElementById('statusMessages');
            statusDiv.innerHTML = '';
            
            if (result.success) {
                statusDiv.innerHTML = `
                    <div class="bg-green-100 text-green-800 p-4 rounded-lg">
                        <i class="fas fa-check-circle mr-2"></i> Datos válidos
                    </div>
                `;
                document.getElementById('submitBtn').disabled = false;
            } else {
                statusDiv.innerHTML = `
                    <div class="bg-red-100 text-red-800 p-4 rounded-lg">
                        <i class="fas fa-exclamation-circle mr-2"></i> ${result.message}
                    </div>
                `;
                document.getElementById('submitBtn').disabled = true;
            }
            
            statusDiv.classList.remove('hidden');
        } catch (error) {
            console.error('Error:', error);
            alert('Error al verificar los datos. Por favor, inténtalo de nuevo.');
        }
    }

    // Agregar validación de tamaño de archivos
    document.getElementById('documents').addEventListener('change', function(e) {
        const files = e.target.files;
        for (let i = 0; i < files.length; i++) {
            if (files[i].size > 2048000) { // 2MB
                alert(`El archivo "${files[i].name}" es demasiado grande. El tamaño máximo permitido es 2MB.`);
                e.target.value = '';
                return;
            }
        }
    });
</script>
@endpush
@endsection
