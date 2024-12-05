<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreMethodRequest extends FormRequest
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
        $methodId = $this->route('method') ? $this->route('method')->id_metodo : null;
        return [
            'nombre' => [
                'required',
                'string',
                'min:8',
                Rule::unique('metodos', 'nombre')->ignore($methodId)
            ]
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
