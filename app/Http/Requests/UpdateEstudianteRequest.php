<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateEstudianteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

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
            'email' => 'nullable|email:rfc,dns|max:100',
            'telefono' => [
                'nullable',
                'string',
                'regex:/^\d{4}-\d{4}$/',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'carnet.required' => 'El carnet es obligatorio.',
            'carnet.regex' => 'El carnet debe tener el formato: 2 letras mayúsculas + 6 números (Ej: CH252968).',
            'carnet.unique' => 'Este carnet ya está registrado por otro estudiante.',
            'nombre.required' => 'El nombre es obligatorio.',
            'nombre.regex' => 'El nombre solo puede contener letras y espacios.',
            'nombre.max' => 'El nombre no puede exceder 150 caracteres.',
            'email.email' => 'El email debe ser una dirección válida.',
            'telefono.regex' => 'El teléfono debe tener el formato: XXXX-XXXX (Ej: 7890-1234).',
        ];
    }

    protected function prepareForValidation()
    {
        if ($this->has('carnet')) {
            $this->merge([
                'carnet' => strtoupper($this->carnet),
            ]);
        }
    }
}
