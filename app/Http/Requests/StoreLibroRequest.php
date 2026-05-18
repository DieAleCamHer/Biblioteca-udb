<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreLibroRequest extends FormRequest
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
        $anioActual = date('Y');
        
        return [
            'categoria_id' => 'required|exists:categorias,id',
            'titulo' => 'required|string|max:200',
            'autor' => 'required|string|max:150',
            'isbn' => 'required|string|size:13|unique:libros,isbn',
            'anio_publicacion' => [
                'required',
                'integer',
                'digits:4',
                'min:1450',
                'max:' . $anioActual,
            ],
            'stock' => 'required|integer|min:0',
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
            'categoria_id.required' => 'La categoría es obligatoria.',
            'categoria_id.exists' => 'La categoría seleccionada no existe.',
            'titulo.required' => 'El título es obligatorio.',
            'titulo.max' => 'El título no puede exceder 200 caracteres.',
            'autor.required' => 'El autor es obligatorio.',
            'autor.max' => 'El autor no puede exceder 150 caracteres.',
            'isbn.required' => 'El ISBN es obligatorio.',
            'isbn.size' => 'El ISBN debe tener exactamente 13 caracteres.',
            'isbn.unique' => 'Este ISBN ya está registrado en el sistema.',
            'anio_publicacion.required' => 'El año de publicación es obligatorio.',
            'anio_publicacion.integer' => 'El año de publicación debe ser un número entero.',
            'anio_publicacion.digits' => 'El año de publicación debe tener 4 dígitos.',
            'anio_publicacion.min' => 'El año de publicación no puede ser anterior a 1450.',
            'anio_publicacion.max' => 'El año de publicación no puede ser mayor al año actual.',
            'stock.required' => 'El stock es obligatorio.',
            'stock.integer' => 'El stock debe ser un número entero.',
            'stock.min' => 'El stock no puede ser negativo.',
        ];
    }
}
