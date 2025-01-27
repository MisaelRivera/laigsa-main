<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;

class FoodSampleUpdateRequest extends FormRequest
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

    public function values (Request $request): array
    {
        return $request->only([
            'tipo_muestra',
            'identificacion_muestra',
            'caracteristicas',
            'muestreador',
            'tipo_muestreo',
            'latitud_grados',
            'latitud_minutos',
            'latitud_segundos',
            'latitud_orientacion',
            'longitud_grados',
            'longitud_minutos',
            'longitud_segundos',
            'longitud_orientacion',
            'fecha_muestreo',
            'hora_muestreo',
            'parametros',
            'otros_parametros'
        ]);
    }

    public function rules(): array
    {
        return [
            'tipo_muestra' => 'required',
            'identificacion_muestra' => 'required',
            'caracteristicas' => 'required',
            'muestreador' => 'required',
            'tipo_muestreo' => 'required',
            'latitud_grados' => 'sometimes|required|numeric',
            'latitud_minutos' => 'sometimes|required|numeric',
            'latitud_segundos' => 'sometimes|required|numeric',
            'latitud_orientacion' => 'sometimes|required',
            'longitud_grados' => 'sometimes|required|numeric',
            'longitud_minutos' => 'sometimes|required|numeric',
            'longitud_segundos' => 'sometimes|required|numeric',
            'longitud_orientacion' => 'sometimes|required',
            'fecha_muestreo' => 'required|date',
            'hora_muestreo' => 'required',
            'parametros' => 'required',
            'otros_parametros' => 'sometimes|required'
        ];
    }

    public function messages (): array
    {
        return [
            'tipo_muestra.required' => 'Ingrese el tipo de muestra',
            'identificacion_muestra.required' => 'Ingrese la identificacion de muestra',
            'caracteristicas.required' => 'Ingrese las caracteristicas',
            'muestreador.required' => 'Elija el muestreador',
            'tipo_muestreo.required' => 'Ingrese el tipo de muestreo',
            'latitud_grados.required' => 'Ingrese los grados de la latitud',
            'latitud_grados.numeric' => 'Los grados de la latitud deben ser numericos',
            'latitud_minutos.required' => 'Ingrese los minutos de la latitud',
            'latitud_minutos.numeric' => 'Los minutos de la latitud deben ser numericos',
            'latitud_segundos.required' => 'Ingrese los segundos de la latitud',
            'latitud_segundos.numeric' => 'Los segundos de la latitud deben ser numericos',
            'longitud_grados.required' => 'Ingrese los grados de la longitud',
            'longitud_grados.numeric' => 'Los grados de la longitud deben ser numericos',
            'longitud_minutos.required' => 'Ingrese los minutos de la longitud',
            'longitud_minutos.numeric' => 'Los minutos de la longitud deben ser numericos',
            'longitud_segundos.required' => 'Ingrese los segundos de la longitud',
            'longitud_segundos.numeric' => 'Los segundos de la  deben ser numericos',
            'fecha_muestreo.required' => 'Ingrese la fecha de muestreo',
            'fecha_muestreo.date' => 'Formato invalido de la fecha de muestreo',
            'hora_muestreo.required' => 'Ingrese la hora de muestreo',
            'parametros.required' => 'Elija los parametros',
            'otros_parametros'
        ];
    }
}
