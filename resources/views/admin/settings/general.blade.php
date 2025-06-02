@extends('admin.settings.layout')

@section('content')
<div class="px-4 py-5 sm:p-6">
    <form action="{{ route('admin.settings.general.update') }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="space-y-6">
            <!-- Site Information -->
            <div class="bg-white shadow px-4 py-5 sm:rounded-lg sm:p-6">
                <div class="md:grid md:grid-cols-3 md:gap-6">
                    <div class="md:col-span-1">
                        <h3 class="text-lg font-medium leading-6 text-gray-900">Información del Sitio</h3>
                        <p class="mt-1 text-sm text-gray-500">Configura la información básica de tu sitio web.</p>
                    </div>
                    <div class="mt-5 md:mt-0 md:col-span-2">
                        <div class="space-y-6">
                            <div>
                                <label for="site_name" class="block text-sm font-medium text-gray-700">Nombre del Sitio</label>
                                <input type="text" name="site_name" id="site_name" value="{{ old('site_name', $settings['site_name'] ?? config('app.name')) }}" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                @error('site_name')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="site_description" class="block text-sm font-medium text-gray-700">Descripción del Sitio</label>
                                <textarea name="site_description" id="site_description" rows="3" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">{{ old('site_description', $settings['site_description'] ?? '') }}</textarea>
                                @error('site_description')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="contact_email" class="block text-sm font-medium text-gray-700">Email de Contacto</label>
                                <input type="email" name="contact_email" id="contact_email" value="{{ old('contact_email', $settings['contact_email'] ?? '') }}" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                @error('contact_email')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Date & Time -->
            <div class="bg-white shadow px-4 py-5 sm:rounded-lg sm:p-6">
                <div class="md:grid md:grid-cols-3 md:gap-6">
                    <div class="md:col-span-1">
                        <h3 class="text-lg font-medium leading-6 text-gray-900">Fecha y Hora</h3>
                        <p class="mt-1 text-sm text-gray-500">Configura la zona horaria y el formato de fecha/hora.</p>
                    </div>
                    <div class="mt-5 md:mt-0 md:col-span-2">
                        <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                            <div class="sm:col-span-3">
                                <label for="timezone" class="block text-sm font-medium text-gray-700">Zona Horaria</label>
                                <select id="timezone" name="timezone" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                    @foreach(timezone_identifiers_list() as $timezone)
                                        <option value="{{ $timezone }}" {{ (old('timezone', $settings['timezone'] ?? config('app.timezone')) === $timezone) ? 'selected' : '' }}>
                                            {{ $timezone }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('timezone')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div class="sm:col-span-3">
                                <label for="date_format" class="block text-sm font-medium text-gray-700">Formato de Fecha</label>
                                <select id="date_format" name="date_format" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                    <option value="d/m/Y" {{ (old('date_format', $settings['date_format'] ?? 'd/m/Y') === 'd/m/Y') ? 'selected' : '' }}>DD/MM/YYYY</option>
                                    <option value="m/d/Y" {{ (old('date_format', $settings['date_format'] ?? '') === 'm/d/Y') ? 'selected' : '' }}>MM/DD/YYYY</option>
                                    <option value="Y-m-d" {{ (old('date_format', $settings['date_format'] ?? '') === 'Y-m-d') ? 'selected' : '' }}>YYYY-MM-DD</option>
                                </select>
                                @error('date_format')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div class="sm:col-span-3">
                                <label for="time_format" class="block text-sm font-medium text-gray-700">Formato de Hora</label>
                                <select id="time_format" name="time_format" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                    <option value="H:i" {{ (old('time_format', $settings['time_format'] ?? 'H:i') === 'H:i') ? 'selected' : '' }}>24 horas (14:30)</option>
                                    <option value="h:i A" {{ (old('time_format', $settings['time_format'] ?? '') === 'h:i A') ? 'selected' : '' }}>12 horas (2:30 PM)</option>
                                </select>
                                @error('time_format')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
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
@endsection
