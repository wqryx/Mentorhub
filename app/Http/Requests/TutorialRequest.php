<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TutorialRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Check if the user is authorized to create/edit tutorials
        return auth()->check() && (auth()->user()->isAdmin() || 
                                auth()->user()->isMentor() || 
                                auth()->user()->isEstudiante());
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
            'excerpt' => 'nullable|string|max:500',
            'content' => 'required|string|min:50',
            'status' => 'required|in:draft,published,archived',
            'is_premium' => 'boolean',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:tags,id',
        ];
        
        // Apply different validation rules based on the HTTP method
        if ($this->isMethod('POST')) {
            // For new tutorials, featured image is required
            $rules['featured_image'] = 'required|image|mimes:jpeg,png,jpg,gif|max:2048';
            // If there's a video upload
            $rules['video'] = 'nullable|mimetypes:video/avi,video/mpeg,video/mp4|max:20480';
        } else {
            // For updating tutorials, the image is optional
            $rules['featured_image'] = 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048';
            // If there's a video upload update
            $rules['video'] = 'nullable|mimetypes:video/avi,video/mpeg,video/mp4|max:20480';
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
            'title.required' => 'El título del tutorial es obligatorio.',
            'title.min' => 'El título debe tener al menos 5 caracteres.',
            'category_id.required' => 'Debes seleccionar una categoría.',
            'category_id.exists' => 'La categoría seleccionada no es válida.',
            'content.required' => 'El contenido del tutorial es obligatorio.',
            'content.min' => 'El contenido debe tener al menos 50 caracteres.',
            'featured_image.required' => 'La imagen destacada es obligatoria.',
            'featured_image.image' => 'El archivo debe ser una imagen.',
            'featured_image.mimes' => 'La imagen debe ser de tipo: jpeg, png, jpg, gif.',
            'featured_image.max' => 'La imagen no debe pesar más de 2MB.',
            'video.mimetypes' => 'El video debe ser de tipo: avi, mpeg, mp4.',
            'video.max' => 'El video no debe pesar más de 20MB.',
            'tags.*.exists' => 'Una de las etiquetas seleccionadas no es válida.',
        ];
    }
}
