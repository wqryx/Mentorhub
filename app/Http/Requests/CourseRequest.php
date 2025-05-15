<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class CourseRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Autorizar a mentores y administradores para crear/editar cursos
        return Auth::check() && (Auth::user()->isMentor() || Auth::user()->isAdmin());
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [
            'title' => 'required|string|min:5|max:255',
            'category_id' => 'required|exists:categories,id',
            'description' => 'required|string|min:50',
            'learning_objectives' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'difficulty_level' => 'required|in:principiante,intermedio,avanzado',
            'status' => 'nullable|in:draft,published,archived',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'promotional_video_url' => 'nullable|string|max:255',
            'promotional_video' => 'nullable|file|mimes:mp4,mov,avi|max:20480',
            'duration_minutes' => 'nullable|integer|min:1',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:tags,id',
            'is_featured' => 'boolean',
        ];

        // Si es una creación nueva, hacer el campo featured_image requerido
        if ($this->isMethod('POST')) {
            $rules['featured_image'] = 'required|image|mimes:jpeg,png,jpg|max:2048';
        }

        return $rules;
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'title.required' => 'El título del curso es obligatorio.',
            'title.min' => 'El título debe tener al menos 5 caracteres.',
            'category_id.required' => 'Debes seleccionar una categoría.',
            'category_id.exists' => 'La categoría seleccionada no es válida.',
            'description.required' => 'La descripción del curso es obligatoria.',
            'description.min' => 'La descripción debe tener al menos 50 caracteres.',
            'price.required' => 'El precio es obligatorio.',
            'price.numeric' => 'El precio debe ser un valor numérico.',
            'price.min' => 'El precio no puede ser negativo.',
            'difficulty_level.required' => 'Debes seleccionar un nivel de dificultad.',
            'difficulty_level.in' => 'El nivel de dificultad seleccionado no es válido.',
            'featured_image.required' => 'La imagen destacada es obligatoria.',
            'featured_image.image' => 'El archivo debe ser una imagen.',
            'featured_image.mimes' => 'La imagen debe ser de tipo: jpeg, png, jpg.',
            'featured_image.max' => 'La imagen no debe pesar más de 2MB.',
            'promotional_video.mimes' => 'El video debe ser de tipo: mp4, mov, avi.',
            'promotional_video.max' => 'El video no debe pesar más de 20MB.',
            'tags.*.exists' => 'Una de las etiquetas seleccionadas no es válida.',
        ];
    }
}
