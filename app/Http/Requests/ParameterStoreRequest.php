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
            'parametro' => ['required', Rule::unique('parametros', 'parametro')->ignore($this->parameter)],
            'abreviacion' => 'required',
            'arrange' => 'required',
            'subcontratado' => 'boolean',
            'compuesto' => 'boolean',
            'ema' => 'boolean',
            'cna' => 'boolean',
            'ssa' => 'boolean',
            'supervisar' => 'boolean',
            'fecha_resultado_final' => 'boolean',
            'parametro_campo' => 'boolean',
            'incertidumbre' => 'boolean',
        ];
    }

    public function messages(): array {
        return [
            'parametro.required' => 'Ingrese el parametro',
            'parametro.unique' => 'El parametro ya existe',
            'abreviacion' => 'Ingrese la abreviacion',
            'arrange' => 'Ingrese el orden de acomodo'
        ];
    }
}
