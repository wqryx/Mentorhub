<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="bg-white shadow-sm rounded-lg p-6 text-gray-900">
                <h3 class="text-xl mb-2">Bienvenido, {{ auth()->user()->name }}</h3>
                <p class="text-gray-600">Tu rol: <strong>{{ auth()->user()->role->name ?? 'Sin rol' }}</strong></p>
            </div>

            {{-- Solo para el admin --}}
            @if(auth()->user()->role->name === 'admin')
                <div class="bg-white shadow-sm rounded-lg p-6">
                    <h4 class="text-lg font-semibold mb-4">Panel de Administración</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                        <a href="{{ route('usuarios.index') }}"
                           class="bg-indigo-600 text-white text-center py-4 rounded hover:bg-indigo-700 transition">
                            👥 Gestionar Usuarios
                        </a>

                        <a href="{{ route('empresas.index') }}"
                           class="bg-green-600 text-white text-center py-4 rounded hover:bg-green-700 transition">
                            🏢 Gestionar Empresas
                        </a>

                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
