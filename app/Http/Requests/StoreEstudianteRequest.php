<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreEstudianteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'carnet' => [
                'required',
                'string',
                'regex:/^[A-Z]{2}\d{6}$/',
                'unique:estudiantes,carnet',
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
            'carnet.unique' => 'Este carnet ya está registrado en el sistema.',
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
