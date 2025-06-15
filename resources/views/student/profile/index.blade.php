@extends('student.layouts.app')

@section('title', 'Mi Perfil de Estudiante')

@section('content')
<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-6">
    <!-- Encabezado -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Mi Perfil</h1>
            <p class="mt-1 text-sm text-gray-500">Administra tu información personal y preferencias</p>
        </div>
    </div>

    <!-- Mensajes de éxito -->
    @if(session('success'))
        <div class="bg-green-50 border-l-4 border-green-400 p-4 mb-6 rounded-md">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-green-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-green-800">
                        {{ session('success') }}
                    </p>
                </div>
            </div>
        </div>
    @endif

    <!-- Tarjeta principal del perfil -->
    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <!-- Encabezado de la tarjeta -->
        <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
            <h3 class="text-lg leading-6 font-medium text-gray-900">Información del Perfil</h3>
            <p class="mt-1 max-w-2xl text-sm text-gray-500">Actualiza tu información personal y preferencias académicas.</p>
        </div>
        
        <!-- Formulario -->
        <div class="px-4 py-5 sm:p-6">
            <form action="{{ route('student.profile.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                    <!-- Sección de foto de perfil -->
                    <div class="sm:col-span-6">
                        <label class="block text-sm font-medium text-gray-700">Foto de perfil</label>
                        <div class="mt-1 flex items-center">
                            <span class="inline-block h-20 w-20 rounded-full overflow-hidden bg-gray-100">
                                <img id="profile-preview" class="h-full w-full object-cover" 
                                     src="{{ Auth::user()->profile_photo_url }}" 
                                     alt="{{ Auth::user()->name }}">
                            </span>
                            <input type="file" name="photo" id="photo" class="hidden" accept="image/*">
                            <button type="button" onclick="document.getElementById('photo').click()" 
                                    class="ml-5 bg-white py-2 px-3 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                Cambiar
                            </button>
                            <p class="ml-5 text-sm text-gray-500">
                                JPG, GIF o PNG. Tamaño máximo de 2MB
                            </p>
                        </div>
                        @error('photo')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Secciones adicionales irán aquí -->
                                         <!-- Información Personal -->
                    <div class="sm:col-span-6 pt-6 border-t border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Información Personal</h3>
                        <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                            <!-- Nombres -->
                            <div class="sm:col-span-3">
                                <label for="first_name" class="block text-sm font-medium text-gray-700">Nombres</label>
                                <div class="mt-1">
                                    <input type="text" name="first_name" id="first_name" 
                                           value="{{ old('first_name', explode(' ', Auth::user()->name)[0] ?? '') }}" 
                                           class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                </div>
                                @error('first_name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Apellidos -->
                            <div class="sm:col-span-3">
                                <label for="last_name" class="block text-sm font-medium text-gray-700">Apellidos</label>
                                <div class="mt-1">
                                    <input type="text" name="last_name" id="last_name" 
                                           value="{{ old('last_name', implode(' ', array_slice(explode(' ', Auth::user()->name), 1)) ?? '') }}" 
                                           class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                </div>
                                @error('last_name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Teléfono -->
                            <div class="sm:col-span-3">
                                <label for="phone" class="block text-sm font-medium text-gray-700">Teléfono</label>
                                <div class="mt-1 relative rounded-md shadow-sm">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <span class="text-gray-500 sm:text-sm">+51</span>
                                    </div>
                                    <input type="text" name="phone" id="phone" 
                                           value="{{ old('phone', Auth::user()->phone ?? '') }}" 
                                           class="focus:ring-blue-500 focus:border-blue-500 block w-full pl-12 sm:text-sm border-gray-300 rounded-md" 
                                           placeholder="987 654 321">
                                </div>
                                @error('phone')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Fecha de Nacimiento -->
                            <div class="sm:col-span-3">
                                <label for="birthdate" class="block text-sm font-medium text-gray-700">Fecha de Nacimiento</label>
                                <div class="mt-1">
                                    <input type="date" name="birthdate" id="birthdate" 
                                           value="{{ old('birthdate', Auth::user()->birthdate ? \Carbon\Carbon::parse(Auth::user()->birthdate)->format('Y-m-d') : '') }}" 
                                           class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                </div>
                                @error('birthdate')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Biografía -->
                            <div class="sm:col-span-6">
                                <label for="bio" class="block text-sm font-medium text-gray-700">Acerca de mí</label>
                                <div class="mt-1">
                                    <textarea id="bio" name="bio" rows="3" 
                                              class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md"
                                              placeholder="Cuéntanos un poco sobre ti">{{ old('bio', Auth::user()->bio ?? '') }}</textarea>
                                </div>
                                <p class="mt-1 text-sm text-gray-500">Breve descripción que será visible en tu perfil.</p>
                                @error('bio')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                                            <!-- Información Académica -->
                    <div class="sm:col-span-6 pt-6 border-t border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Información Académica</h3>
                        <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                            <!-- Universidad -->
                            <div class="sm:col-span-3">
                                <label for="university" class="block text-sm font-medium text-gray-700">Universidad</label>
                                <div class="mt-1">
                                    <input type="text" name="university" id="university" 
                                           value="{{ old('university', Auth::user()->university ?? '') }}" 
                                           class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md"
                                           placeholder="Ej: Universidad Nacional Mayor de San Marcos">
                                </div>
                                @error('university')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Carrera -->
                            <div class="sm:col-span-3">
                                <label for="career" class="block text-sm font-medium text-gray-700">Carrera</label>
                                <div class="mt-1">
                                    <input type="text" name="career" id="career" 
                                           value="{{ old('career', Auth::user()->career ?? '') }}" 
                                           class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md"
                                           placeholder="Ej: Ingeniería de Software">
                                </div>
                                @error('career')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Código de Estudiante -->
                            <div class="sm:col-span-2">
                                <label for="student_code" class="block text-sm font-medium text-gray-700">Código de Estudiante</label>
                                <div class="mt-1">
                                    <input type="text" name="student_code" id="student_code" 
                                           value="{{ old('student_code', Auth::user()->student_code ?? '') }}" 
                                           class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md"
                                           placeholder="Ej: 20210000">
                                </div>
                                @error('student_code')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Ciclo -->
                            <div class="sm:col-span-2">
                                <label for="cycle" class="block text-sm font-medium text-gray-700">Ciclo</label>
                                <div class="mt-1">
                                    <select name="cycle" id="cycle" class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                        <option value="">Seleccione un ciclo</option>
                                        @for($i = 1; $i <= 10; $i++)
                                            <option value="{{ $i }}" {{ old('cycle', Auth::user()->cycle) == $i ? 'selected' : '' }}>
                                                Ciclo {{ $i }}
                                            </option>
                                        @endfor
                                    </select>
                                </div>
                                @error('cycle')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Año de Ingreso -->
                            <div class="sm:col-span-2">
                                <label for="enrollment_year" class="block text-sm font-medium text-gray-700">Año de Ingreso</label>
                                <div class="mt-1">
                                    <select name="enrollment_year" id="enrollment_year" class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                        <option value="">Seleccione año</option>
                                        @for($year = date('Y'); $year >= date('Y') - 10; $year--)
                                            <option value="{{ $year }}" {{ old('enrollment_year', Auth::user()->enrollment_year) == $year ? 'selected' : '' }}>
                                                {{ $year }}
                                            </option>
                                        @endfor
                                    </select>
                                </div>
                                @error('enrollment_year')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Redes Sociales -->
                    <div class="sm:col-span-6 pt-6 border-t border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Redes Sociales</h3>
                        <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                            <!-- LinkedIn -->
                            <div class="sm:col-span-3">
                                <label for="linkedin" class="block text-sm font-medium text-gray-700">LinkedIn</label>
                                <div class="mt-1 flex rounded-md shadow-sm">
                                    <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500 text-sm">
                                        linkedin.com/in/
                                    </span>
                                    <input type="text" name="linkedin" id="linkedin" 
                                           value="{{ old('linkedin', Auth::user()->linkedin ?? '') }}" 
                                           class="focus:ring-blue-500 focus:border-blue-500 flex-1 block w-full rounded-none rounded-r-md sm:text-sm border-gray-300"
                                           placeholder="tu-usuario">
                                </div>
                                @error('linkedin')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- GitHub -->
                            <div class="sm:col-span-3">
                                <label for="github" class="block text-sm font-medium text-gray-700">GitHub</label>
                                <div class="mt-1 flex rounded-md shadow-sm">
                                    <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500 text-sm">
                                        github.com/
                                    </span>
                                    <input type="text" name="github" id="github" 
                                           value="{{ old('github', Auth::user()->github ?? '') }}" 
                                           class="focus:ring-blue-500 focus:border-blue-500 flex-1 block w-full rounded-none rounded-r-md sm:text-sm border-gray-300"
                                           placeholder="tu-usuario">
                                </div>
                                @error('github')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                                                        <!-- Twitter -->
                            <div class="sm:col-span-3">
                                <label for="twitter" class="block text-sm font-medium text-gray-700">Twitter</label>
                                <div class="mt-1 flex rounded-md shadow-sm">
                                    <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500 text-sm">
                                        twitter.com/
                                    </span>
                                    <input type="text" name="twitter" id="twitter" 
                                           value="{{ old('twitter', Auth::user()->twitter ?? '') }}" 
                                           class="focus:ring-blue-500 focus:border-blue-500 flex-1 block w-full rounded-none rounded-r-md sm:text-sm border-gray-300"
                                           placeholder="tu-usuario">
                                </div>
                                @error('twitter')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Instagram -->
                            <div class="sm:col-span-3">
                                <label for="instagram" class="block text-sm font-medium text-gray-700">Instagram</label>
                                <div class="mt-1 flex rounded-md shadow-sm">
                                    <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500 text-sm">
                                        instagram.com/
                                    </span>
                                    <input type="text" name="instagram" id="instagram" 
                                           value="{{ old('instagram', Auth::user()->instagram ?? '') }}" 
                                           class="focus:ring-blue-500 focus:border-blue-500 flex-1 block w-full rounded-none rounded-r-md sm:text-sm border-gray-300"
                                           placeholder="tu-usuario">
                                </div>
                                @error('instagram')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Botón de Guardar -->
                    <div class="px-4 py-3 bg-gray-50 text-right sm:px-6">
                        <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Guardar Cambios
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>


@push('scripts')
<script>
    // Preview de la imagen de perfil
    document.addEventListener('DOMContentLoaded', function() {
        const photoInput = document.getElementById('photo');
        if (photoInput) {
            photoInput.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const preview = document.getElementById('profile-preview');
                        if (preview) {
                            preview.src = e.target.result;
                        }
                    }
                    reader.readAsDataURL(file);
                }
            });
        }
    });
</script>
@endpush
@endsection