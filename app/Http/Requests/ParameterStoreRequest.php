<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ParameterStoreRequest extends FormRequest
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
            'parametro' => ['required', Rule::unique('parametros', 'parametro')->ignore($this->parameter)]
        ];
    }

    public function messages(): array {
        return [
            'parametro.required' => 'Ingrese el parametro',
            'parametro.unique' => 'El parametro ya existe'
        ];
    }
}
