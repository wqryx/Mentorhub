@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="bg-white rounded-lg shadow-lg p-6">
        <h2 class="text-2xl font-bold mb-6">Configuraciones</h2>

        <form method="POST" action="{{ route('settings.update') }}" class="space-y-6">
            @csrf
            @method('PATCH')

            <div>
                <label for="email" class="block text-sm font-medium text-gray-700">Correo Electrónico</label>
                <input type="email" name="email" id="email" 
                       value="{{ auth()->user()->email }}" 
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Notificaciones</label>
                <div class="mt-2 space-y-2">
                    <div class="flex items-center">
                        <input type="checkbox" name="notifications" id="notifications" 
                               {{ auth()->user()->notifications ? 'checked' : '' }}
                               class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                        <label for="notifications" class="ml-3 text-sm text-gray-600">
                            Recibir notificaciones por email
                        </label>
                    </div>
                </div>
            </div>

            <div class="flex justify-end">
                <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Guardar cambios
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
