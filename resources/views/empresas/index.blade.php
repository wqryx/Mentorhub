<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Listado de Empresas') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3>Empresas</h3>

                    <a href="{{ route('empresas.create') }}" class="text-blue-500 underline">
                        Crear nueva empresa
                    </a>

                    <ul>
                        @foreach($empresas as $empresa)
                            <li>{{ $empresa->nombre }} - {{ $empresa->direccion }} - {{ $empresa->telefono }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
