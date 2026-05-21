<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateEstudianteRequest extends FormRequest
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
     */
    public function rules(): array
    {
        $estudianteId = $this->route('estudiante');

        return [
            'carnet' => [
                'required',
                'string',
                'regex:/^[A-Z]{2}\d{6}$/',
                Rule::unique('estudiantes', 'carnet')->ignore($estudianteId),
            ],
            'nombre' => [
                'required',
                'string',
                'max:150',
                'regex:/^[a-záéíóúñA-ZÁÉÍÓÚÑ\s]+$/',
            ],
            'email' => 'nullable|email|max:100',
            'telefono' => 'nullable|string|max:15|regex:/^[0-9\-\+\(\)\s]+$/',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'carnet.required' => 'El carnet es obligatorio.',
            'carnet.regex' => 'El carnet debe tener el formato: 2 letras mayúsculas + 6 números (Ej: CH252968, MD259867).',
            'carnet.unique' => 'Este carnet ya está registrado por otro estudiante.',
            'nombre.required' => 'El nombre es obligatorio.',
            'nombre.regex' => 'El nombre solo puede contener letras y espacios (sin números).',
            'nombre.max' => 'El nombre no puede exceder 150 caracteres.',
            'email.email' => 'El email debe ser una dirección válida.',
            'telefono.regex' => 'El teléfono solo puede contener números, espacios y caracteres: - + ( )',
        ];
    }

    /**
     * Preparar datos antes de validación
     */
    protected function prepareForValidation()
    {
        if ($this->has('carnet')) {
            $this->merge([
                'carnet' => strtoupper($this->carnet),
            ]);
        }
    }
}
