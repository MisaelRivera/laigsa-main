<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class SampleIdentificationStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function values(Request $request)
    {
        return $request->only([
            'identificacion_muestra',
            'siralab',
            'id_cliente',
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
            'identificacion_muestra' => 'required',
            'id_cliente' => 'required|exists:clientes,id',
            'siralab' => 'required|boolean',
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
            'identificacion_muestra.required' => 'Ingrese la identificacion de muestra',
            'id_cliente.required' => 'Elija un cliente',
            'id_cliente.exists' => 'El cliente elegido no existe',
            'siralab.required' => 'Elija un valor para siralab',
            'latitud_grados.required' => 'Ingrese un valor',
            'latitud_minutos.required' => 'Ingrese un valor',
            'latitud_segundos.required' => 'Ingrese un valor',
            'latitud_orientacion.required' => 'Elija un valor',
            'longitud_grados.required' => 'Ingrese un valor',
            'longitud_minutos.required' => 'Ingrese un valor',
            'longitud_segundos.required' => 'Ingrese un valor',
            'longitud_orientacion.required' => 'Elija un valor',
        ];
    }
}
