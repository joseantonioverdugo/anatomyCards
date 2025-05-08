<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;

class UpdateOptionRequest extends FormRequest
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
        // Obtener y registrar el ID de la ruta para depuración
        $optionId = $this->route('option');
        
        return [
            'flashcard_id' => 'required|integer',
            'title' => 'required|string|max:255',
            'option_number' => [
                'required',
                'integer',
                Rule::unique('options', 'option_number')
                    ->where(function ($query) {
                        return $query->where('flashcard_id', $this->input('flashcard_id'));
                    })
                    ->ignore($optionId),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'flashcard_id.required' => 'El flashcard_id es obligatorio.',
            'flashcard_id.integer' => 'El flashcard_id debe ser un número entero.',
            'title.required' => 'El título es obligatorio.',
            'title.string' => 'El título debe ser una cadena de texto.',
            'title.max' => 'El título no puede tener más de 255 caracteres.',
            'option_number.required' => 'El option_number es obligatorio.',
            'option_number.integer' => 'El option_number debe ser un número entero.',
            'option_number.unique' => 'El número de opción ya está en uso para esta tarjeta.',
        ];
    }
}

