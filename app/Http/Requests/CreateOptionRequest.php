<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateOptionRequest extends FormRequest
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
            'flashcard_id' => 'required|integer',
            'title' => 'required|string',
            'option_number' => [
            'required',
            'integer',
                Rule::unique('options', 'option_number')
                ->where(function ($query) {
                    return $query->where('flashcard_id', $this->input('flashcard_id'));
                }),
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
            'option_number.required' => 'El option_number es obligatorio.',
            'option_number.integer' => 'El option_number debe ser un número entero.',
            'option_number.unique' => 'El option_number ya existe para este flashcard.',
        ];
    }
}
