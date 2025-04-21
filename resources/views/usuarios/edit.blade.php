<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Editar Usuario') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-md mx-auto bg-white p-6 rounded shadow">
            <form method="POST" action="{{ route('usuarios.update', $usuario) }}">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label class="block">Nombre</label>
                    <input type="text" name="name" class="w-full border rounded px-3 py-2" value="{{ $usuario->name }}" required>
                </div>

                <div class="mb-4">
                    <label class="block">Email</label>
                    <input type="email" name="email" class="w-full border rounded px-3 py-2" value="{{ $usuario->email }}" required>
                </div>

                <div class="mb-4">
                    <label class="block">Rol</label>
                    <select name="role_id" class="w-full border rounded px-3 py-2" required>
                        @foreach($roles as $rol)
                            <option value="{{ $rol->id }}" {{ $usuario->role_id == $rol->id ? 'selected' : '' }}>
                                {{ ucfirst($rol->name) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                    Actualizar
                </button>
            </form>
        </div>
    </div>
</x-app-layout>
