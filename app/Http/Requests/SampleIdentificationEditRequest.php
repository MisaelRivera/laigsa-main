<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class SampleIdentificationEditRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function values (Request $request)
    {
        return $request->only([
            'obsoleta',
            'latitud_grados',
            'latitud_minutos',
            'latitud_segundos',
            'latitud_orientacion',
            'longitud_grados',
            'longitud_minutos',
            'longitud_segundos',
            'longitud_orientacion',
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
            'obsoleta' => 'required|boolean',
            'latitud_grados' => 'sometimes|required',
            'latitud_minutos' => 'sometimes|required',
            'latitud_segundos' => 'sometimes|required',
            'latitud_orientacion' => 'sometimes|required',
            'longitud_grados' => 'sometimes|required',
            'longitud_minutos' => 'sometimes|required',
            'longitud_segundos' => 'sometimes|required',
            'longitud_orientacion' => 'sometimes|required',
        ];
    }

    public function messages()
    {
        return [
            'obsoleta.required' => 'Elija un valor para obsoleta',
            'obsoleta.boolean' => 'obsoleta debe ser un valor booleano',
            'latitud_grados' => 'Ingrese los grados',
            'latitud_minutos' => 'Ingrese los minutos',
            'latitud_segundos' => 'Ingrese los segundos',
            'latitud_orientacion' => 'Ingrese la orientacion',
            'longitud_grados' => 'Ingrese los grados',
            'longitud_minutos' => 'Ingrese los minutos',
            'longitud_segundos' => 'Ingrese los segundos',
            'longitud_orientacion' => 'Ingrese la orientacion',
        ];
    }
}
