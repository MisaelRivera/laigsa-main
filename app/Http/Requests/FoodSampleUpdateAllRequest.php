<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class FoodSampleUpdateAllRequest extends FormRequest
{
    protected $i;

    public function setIteration($i)
    {
        $this->i = $i;
    }
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
            "tipo_muestra_{$this->i}", 
            "identificacion_muestra_{$this->i}",
            "caracteristicas_{$this->i}",
            "muestreador_{$this->i}",
            "tipo_muestreo_{$this->i}",
            "latitud_grados_{$this->i}",
            "latitud_minutos_{$this->i}",
            "latitud_segundos_{$this->i}",
            "latitud_orientacion_{$this->i}",
            "longitud_grados_{$this->i}",
            "longitud_minutos_{$this->i}",
            "longitud_segundos_{$this->i}",
            "longitud_orientacion_{$this->i}",
            "fecha_muestreo_{$this->i}",
            "hora_muestreo_{$this->i}",
            "parametros_{$this->i}",
            "otros_{$this->i}",
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
            "tipo_muestra_{$this->i}" => 'required',
            "identificacion_muestra_{$this->i}" => 'required',
            "caracteristicas_{$this->i}" => 'required',
            "muestreador_{$this->i}" => 'required',
            "tipo_muestreo_{$this->i}" => 'required',
            "latitud_grados_{$this->i}" => 'sometimes|required|numeric',
            "latitud_minutos_{$this->i}" => 'sometimes|required|numeric',
            "latitud_segundos_{$this->i}" => 'sometimes|required|numeric',
            "latitud_orientacion_{$this->i}" => 'sometimes|required',
            "longitud_grados_{$this->i}" => 'sometimes|required|numeric',
            "longitud_minutos_{$this->i}" => 'sometimes|required|numeric',
            "longitud_segundos_{$this->i}" => 'sometimes|required|numeric',
            "longitud_orientacion_{$this->i}" => 'sometimes|required',
            "fecha_muestreo_{$this->i}" => 'required|date',
            "hora_muestreo_{$this->i}" => 'required',
            "parametros_{$this->i}" => 'required',
            "otros_parametros_{$this->i}" => 'sometimes|required'
        ];
    }

    public function messages()
    {
        return [
            "tipo_muestra_{$this->i}.required" => "Ingrese el tipo de muestra " . ($this->i + 1),
            "identificacion_muestra_{$this->i}.required" => "Ingrese la identificacion de muestra " . ($this->i + 1),
            "caracteristicas_{$this->i}.required" => "Ingrese las caracteristicas " . ($this->i + 1),
            "muestreador_{$this->i}.required" => "Ingrese el muestreador " . ($this->i + 1),
            "tipo_muestreo_{$this->i}.required" => "Elija el tipo de muestreo " . ($this->i + 1),
            "fecha_muestreo_{$this->i}.required" => "Ingrese la fecha de muestreo " . ($this->i + 1),
            "fecha_muestreo_{$this->i}.date" => "Formato de fecha incorrecto en fecha de muestreo " . ($this->i + 1),
            "hora_muestreo_{$this->i}.required" => "Ingrese la hora de muestreo " . ($this->i + 1),
            "hora_muestreo_{$this->i}.date_format" => "Formato de hora incorrecto para la hora de muestreo " . ($this->i + 1),
            "latitud_grados_{$this->i}.required" => "Ingrese los grados de la latitud " . $this->i,
            "latitud_minutos_{$this->i}.required" => "Ingrese los minutos de la latitud " . $this->i,
            "latitud_segundos_{$this->i}.required" => "Ingrese los segundos de la latitud " . $this->i,
            "latitud_orientacion_{$this->i}.required" => "Ingrese la orientacion de la latitud " . $this->i,
            "longitud_grados_{$this->i}.required" => "Ingrese los grados de la longitud " . $this->i,
            "longitud_minutos_{$this->i}.required" => "Ingrese los minutos de la longitud " . $this->i,
            "longitud_segundos_{$this->i}.required" => "Ingrese los segundos de la longitud " . $this->i,
            "longitud_orientacion_{$this->i}.required" => "Ingrese la orientacion de la longitud " . $this->i,
            "parametros_{$this->i}.required" => "Ingrese los parametros " . $this->i,
            "otros_{$this->i}.required" => "Indique los parametros en el campo otros " . $this->i,
        ];
    }
}
