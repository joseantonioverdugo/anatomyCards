<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateFlashcardRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'nullable|string|max:255',
            'category_id' => 'nullable|exists:categories,id',
            'subcategory_id' => 'nullable|exists:subcategories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'El título es obligatorio.',
            'category_id.nullable' => 'La categoría es opcional.',
            'category_id.exists' => 'La categoría seleccionada no es válida.',
            'subcategory_id.nullable' => 'La subcategoría es opcional.',
            'subcategory_id.exists' => 'La subcategoría seleccionada no es válida.',
            'image.nullable' => 'La imagen es opcional.',
            'image.image' => 'El archivo debe ser una imagen.',
            'image.mimes' => 'La imagen debe ser de tipo: jpeg, png, jpg, webp.',
            'image.max' => 'La imagen no debe pesar más de 2MB.',
        ];
    }
}
