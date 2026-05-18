<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePrestamoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Preparar datos antes de validación
     */
    protected function prepareForValidation()
    {
        // La fecha de préstamo siempre es la fecha/hora actual
        $this->merge([
            'fecha_prestamo' => now(),
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'libro_id' => 'required|exists:libros,id',
            'nombre_estudiante' => [
                'required',
                'string',
                'max:150',
                'regex:/^[a-záéíóúñA-ZÁÉÍÓÚÑ\s]+$/', // Solo letras y espacios
            ],
            'carnet_estudiante' => [
                'required',
                'string',
                'regex:/^[A-Z]{2}\d{6}$/', // 2 letras mayúsculas + 6 números
            ],
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'libro_id.required' => 'Debe seleccionar un libro.',
            'libro_id.exists' => 'El libro seleccionado no existe.',
            'nombre_estudiante.required' => 'El nombre del estudiante es obligatorio.',
            'nombre_estudiante.max' => 'El nombre no puede exceder 150 caracteres.',
            'nombre_estudiante.regex' => 'El nombre solo puede contener letras y espacios (sin números).',
            'carnet_estudiante.required' => 'El carnet es obligatorio.',
            'carnet_estudiante.regex' => 'El carnet debe tener el formato: 2 letras mayúsculas + 6 números (Ej: CH252968, MD259867).',
        ];
    }
}
