@extends('layouts.admin')

@section('title', 'Crear Mensaje')

@section('content')
<div class="container mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-semibold text-gray-900">Crear Nuevo Mensaje</h1>
        <a href="{{ route('admin.messages.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:outline-none focus:border-gray-700 focus:ring focus:ring-gray-200 active:bg-gray-800 disabled:opacity-25 transition">
            <i class="fas fa-arrow-left mr-2"></i> Volver a la Lista
        </a>
    </div>

    <div class="bg-white shadow-md rounded-lg overflow-hidden p-6">
        <form action="{{ route('admin.messages.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="mb-4">
                <label for="recipient_type" class="block text-sm font-medium text-gray-700 mb-1">Tipo de Destinatario</label>
                <div class="flex flex-wrap gap-4">
                    <label class="inline-flex items-center">
                        <input type="radio" name="recipient_type" value="user" class="form-radio h-5 w-5 text-blue-600" checked>
                        <span class="ml-2 text-gray-700">Usuario Específico</span>
                    </label>
                    <label class="inline-flex items-center">
                        <input type="radio" name="recipient_type" value="role" class="form-radio h-5 w-5 text-blue-600">
                        <span class="ml-2 text-gray-700">Rol Completo</span>
                    </label>
                    <label class="inline-flex items-center">
                        <input type="radio" name="recipient_type" value="all" class="form-radio h-5 w-5 text-blue-600">
                        <span class="ml-2 text-gray-700">Todos los Usuarios</span>
                    </label>
                </div>
                @error('recipient_type')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div id="user-select" class="mb-4">
                <label for="user_id" class="block text-sm font-medium text-gray-700 mb-1">Destinatario</label>
                <select name="user_id" id="user_id" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                    <option value="">Seleccionar Usuario</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                    @endforeach
                </select>
                @error('user_id')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div id="role-select" class="mb-4 hidden">
                <label for="role_id" class="block text-sm font-medium text-gray-700 mb-1">Rol de Destinatarios</label>
                <select name="role_id" id="role_id" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                    <option value="">Seleccionar Rol</option>
                    @foreach($roles as $role)
                        <option value="{{ $role->id }}">{{ $role->name }}</option>
                    @endforeach
                </select>
                @error('role_id')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="subject" class="block text-sm font-medium text-gray-700 mb-1">Asunto</label>
                <input type="text" name="subject" id="subject" class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" required value="{{ old('subject') }}">
                @error('subject')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="content" class="block text-sm font-medium text-gray-700 mb-1">Contenido del Mensaje</label>
                <textarea name="content" id="content" rows="6" class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" required>{{ old('content') }}</textarea>
                @error('content')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="attachments" class="block text-sm font-medium text-gray-700 mb-1">Archivos Adjuntos (opcional)</label>
                <input type="file" name="attachments[]" id="attachments" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100" multiple>
                <p class="text-xs text-gray-500 mt-1">Puede seleccionar múltiples archivos. Tamaño máximo: 10MB por archivo.</p>
                @error('attachments')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
                @error('attachments.*')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex justify-end">
                <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:outline-none focus:border-blue-700 focus:ring focus:ring-blue-200 active:bg-blue-800 disabled:opacity-25 transition">
                    <i class="fas fa-paper-plane mr-2"></i> Enviar Mensaje
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const recipientTypeRadios = document.querySelectorAll('input[name="recipient_type"]');
        const userSelect = document.getElementById('user-select');
        const roleSelect = document.getElementById('role-select');

        function updateRecipientFields() {
            const selectedType = document.querySelector('input[name="recipient_type"]:checked').value;
            
            if (selectedType === 'user') {
                userSelect.classList.remove('hidden');
                roleSelect.classList.add('hidden');
                document.getElementById('user_id').setAttribute('required', 'required');
                document.getElementById('role_id').removeAttribute('required');
            } else if (selectedType === 'role') {
                userSelect.classList.add('hidden');
                roleSelect.classList.remove('hidden');
                document.getElementById('user_id').removeAttribute('required');
                document.getElementById('role_id').setAttribute('required', 'required');
            } else {
                userSelect.classList.add('hidden');
                roleSelect.classList.add('hidden');
                document.getElementById('user_id').removeAttribute('required');
                document.getElementById('role_id').removeAttribute('required');
            }
        }

        recipientTypeRadios.forEach(radio => {
            radio.addEventListener('change', updateRecipientFields);
        });

        updateRecipientFields();
    });
</script>
@endpush
@endsection
