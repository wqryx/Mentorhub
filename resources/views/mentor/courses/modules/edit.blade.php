@extends('mentor.layouts.app')

@section('title', 'Editar Módulo: ' . $module->title . ' - Curso: ' . $course->title . ' - MentorHub')

@push('styles')
<style>
    .slug-prefix {
        line-height: 2.25rem; /* Match input height */
        font-size: .875rem; /* Match input font size */
    }
</style>
@endpush

@section('content')
<div class="container px-4 py-8 mx-auto">
    {{-- Breadcrumbs --}}
    <nav class="mb-4 text-sm" aria-label="Breadcrumb">
        <ol class="inline-flex p-0 list-none">
            <li class="flex items-center">
                <a href="{{ route('mentor.dashboard') }}" class="text-gray-500 hover:text-blue-600">
                    <i class="fas fa-home mr-1"></i> Dashboard
                </a>
            </li>
            <li class="flex items-center">
                <span class="mx-2 text-gray-400">/</span>
                <a href="{{ route('mentor.courses.index') }}" class="text-gray-500 hover:text-blue-600">Mis Cursos</a>
            </li>
            <li class="flex items-center">
                <span class="mx-2 text-gray-400">/</span>
                <a href="{{ route('mentor.courses.show', $course->id) }}" class="text-gray-500 hover:text-blue-600">{{ $course->title }}</a>
            </li>
            <li class="flex items-center">
                <span class="mx-2 text-gray-400">/</span>
                <a href="{{ route('mentor.courses.modules.index', $course->id) }}" class="text-gray-500 hover:text-blue-600">Módulos</a>
            </li>
            <li class="flex items-center">
                <span class="mx-2 text-gray-400">/</span>
                <span class="font-semibold text-gray-700">Editar Módulo</span>
            </li>
        </ol>
    </nav>

    <div class="flex items-center justify-between mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Editar Módulo: <span class="text-blue-600">{{ $module->title }}</span></h1>
        <a href="{{ route('mentor.courses.modules.index', $course->id) }}" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
            <i class="fas fa-arrow-left mr-2"></i>Volver a Módulos
        </a>
    </div>

    <div class="p-6 bg-white rounded-lg shadow-md">
        <form action="{{ route('mentor.courses.modules.update', ['course' => $course->id, 'module' => $module->id]) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                {{-- Title --}}
                <div class="md:col-span-2">
                    <label for="title" class="block text-sm font-medium text-gray-700">Título del Módulo <span class="text-red-500">*</span></label>
                    <input type="text" name="title" id="title" value="{{ old('title', $module->title) }}" required autofocus
                           class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('title') border-red-500 @enderror">
                    @error('title')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Order --}}
                <div>
                    <label for="order" class="block text-sm font-medium text-gray-700">Orden <span class="text-red-500">*</span></label>
                    <input type="number" name="order" id="order" value="{{ old('order', $module->order) }}" required min="1"
                           class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('order') border-red-500 @enderror">
                    @error('order')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Slug --}}
                <div>
                    <label for="slug" class="block text-sm font-medium text-gray-700">URL Amigable (Slug)</label>
                    <div class="flex mt-1 rounded-md shadow-sm">
                        <span class="inline-flex items-center px-3 text-gray-500 bg-gray-100 border border-r-0 border-gray-300 slug-prefix rounded-l-md sm:text-sm">
                            {{ route('mentor.courses.show', $course->id, false) }}/modules/
                        </span>
                        <input type="text" name="slug" id="slug" value="{{ old('slug', $module->slug) }}" placeholder="se-auto-generara-del-titulo"
                               class="flex-1 block w-full min-w-0 border-gray-300 rounded-none focus:ring-blue-500 focus:border-blue-500 rounded-r-md sm:text-sm @error('slug') border-red-500 @enderror">
                    </div>
                    @error('slug')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Description --}}
            <div class="mt-6">
                <label for="description" class="block text-sm font-medium text-gray-700">Descripción</label>
                <textarea name="description" id="description" rows="6"
                          class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('description') border-red-500 @enderror">{{ old('description', $module->description) }}</textarea>
                <p class="mt-1 text-xs text-gray-500">Puedes usar Markdown para formatear el texto.</p>
                @error('description')
                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>

            {{-- Statuses --}}
            <div class="mt-6 space-y-4">
                <div class="flex items-start">
                    <div class="flex items-center h-5">
                        <input id="is_active" name="is_active" type="checkbox" value="1" {{ old('is_active', $module->is_active) ? 'checked' : '' }}
                               class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                    </div>
                    <div class="ml-3 text-sm">
                        <label for="is_active" class="font-medium text-gray-700">Módulo Activo</label>
                        <p class="text-xs text-gray-500">Los módulos inactivos no serán visibles para los estudiantes.</p>
                    </div>
                </div>
                @error('is_active')
                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                @enderror

                <div class="flex items-start">
                    <div class="flex items-center h-5">
                        <input id="is_free" name="is_free" type="checkbox" value="1" {{ old('is_free', $module->is_free) ? 'checked' : '' }}
                               class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                    </div>
                    <div class="ml-3 text-sm">
                        <label for="is_free" class="font-medium text-gray-700">Módulo Gratuito</label>
                        <p class="text-xs text-gray-500">Marcar si este módulo es de acceso gratuito.</p>
                    </div>
                </div>
                @error('is_free')
                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>

            {{-- Action Buttons --}}
            <div class="pt-5 mt-6 border-t border-gray-200">
                <div class="flex justify-end space-x-3">
                    <a href="{{ route('mentor.courses.modules.index', $course->id) }}"
                       class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Cancelar
                    </a>
                    <button type="submit"
                            class="inline-flex justify-center px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <i class="fas fa-save mr-2"></i>Actualizar Módulo
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const titleInput = document.getElementById('title');
        const slugInput = document.getElementById('slug');
        let userModifiedSlug = slugInput.value !== ''; // If slug is pre-filled, assume user might want to keep it

        // Function to generate slug from title
        function generateSlug(title) {
            return title.toLowerCase()
                .replace(/\s+/g, '-')           // Replace spaces with -
                .replace(/[^\w\-]+/g, '')       // Remove all non-word chars
                .replace(/\-\-+/g, '-')         // Replace multiple - with single -
                .replace(/^-+/, '')             // Trim - from start of text
                .replace(/-+$/, '');            // Trim - from end of text
        }

        // Auto-generate slug when title changes, if user hasn't manually edited slug
        if (titleInput && slugInput) {
            titleInput.addEventListener('input', function () {
                if (!userModifiedSlug) {
                    slugInput.value = generateSlug(this.value);
                }
            });

            // Detect if user manually changes the slug
            slugInput.addEventListener('input', function() {
                userModifiedSlug = true;
            });
        }
    });
</script>
@endpush
