<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMethodRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'nombre' => 'required|string|min:8|unique:metodos,nombre'
        ];

    }

    public function messages()
    {
        return [
            'nombre.required' => 'Ingrese el nombre',
            'nombre.string' => 'Ingrese una cadena de texto',
            'nombre.min' => 'El metodo debe contener al menos :min caracteres',
            'nombre.unique' => 'El valor ingresado ya existe'
        ];
    }
}
