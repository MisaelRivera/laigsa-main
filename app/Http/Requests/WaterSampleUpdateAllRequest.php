<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class WaterSampleUpdateAllRequest extends FormRequest
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
            "tipo_muestra_{$this->i}", "id_identificacion_muestra_{$this->i}",
                "caracteristicas_{$this->i}", "muestreador_{$this->i}", "ph_{$this->i}",
                "tratada_biologicamente_{$this->i}", "cloro_{$this->i}", "valor_cloro_{$this->i}",
                "ph_cromo_hexavalente_{$this->i}", "tipo_muestreo_{$this->i}",
                "fecha_muestreo_{$this->i}", "hora_muestreo_{$this->i}",
                "fecha_final_muestreo_{$this->i}", "hora_final_muestreo_{$this->i}",
                "fecha_composicion_{$this->i}", "hora_composicion_{$this->i}",
                "flujo_1_{$this->i}", "flujo_2_{$this->i}", "flujo_3_{$this->i}", 
                "flujo_4_{$this->i}", "flujo_5_{$this->i}", "flujo_6_{$this->i}", "parametros_{$this->i}",
                "otros_{$this->i}", "preservacion_correcta_{$this->i}"
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
            "id_identificacion_muestra_{$this->i}" => 'required|exists:identificacion_muestras,id',
            "caracteristicas_{$this->i}" => 'required',
            "muestreador_{$this->i}" => 'required',
            "ph_{$this->i}" => "required",
            "tratada_biologicamente_{$this->i}" => "boolean",
            "cloro_{$this->i}" => "required",
            "valor_cloro_{$this->i}" => "sometimes:tipo_muestreo_{$this->i},Simple|required_if:cloro_{$this->i},Presente,Ausente",
            "ph_cromo_hexavalente_{$this->i}" => "required",
            "tipo_muestreo_{$this->i}" => "required",
            "fecha_muestreo_{$this->i}" => "required|date",
            "hora_muestreo_{$this->i}" => "required|date_format:H:i",
            "fecha_final_muestreo_{$this->i}" => "required_if:tipo_muestreo_{$this->i},Compuesto_4,Compuesto_6|date",
            "hora_final_muestreo_{$this->i}" => "required_if:tipo_muestreo_{$this->i},Compuesto_4,Compuesto_6|date_format:H:i",
            "fecha_composicion_{$this->i}" => "required_if:tipo_muestreo_{$this->i},Compuesto_4,Compuesto_6|date",
            "hora_composicion_{$this->i}" => "required_if:tipo_muestreo_{$this->i},Compuesto_4,Compuesto_6|date_format:H:i",
            "flujo_1_{$this->i}" => "required_if:tipo_muestreo_{$this->i},Compuesto_4,Compuesto_6",
            "flujo_2_{$this->i}" => "required_if:tipo_muestreo_{$this->i},Compuesto_4,Compuesto_6",
            "flujo_3_{$this->i}" => "required_if:tipo_muestreo_{$this->i},Compuesto_4,Compuesto_6",
            "flujo_4_{$this->i}" => "required_if:tipo_muestreo_{$this->i},Compuesto_4,Compuesto_6",
            "flujo_5_{$this->i}" => "required_if:tipo_muestreo_{$this->i},Compuesto_6",
            "flujo_6_{$this->i}" => "required_if:tipo_muestreo_{$this->i},Compuesto_6",
            "parametros_{$this->i}" => "required",
            "otros_{$this->i}" => "sometimes|required",
            "preservacion_correcta_{$this->i}" => "required",
        ];
    }

    public function messages()
    {
        return [
            "tipo_muestra_{$this->i}.required" => "Ingrese el tipo de muestra " . $this->i,
            "id_identificacion_muestra_{$this->i}.required" => "Elija la identificacion de muestra " . $this->i,
            "id_identificacion_muestra_{$this->i}.exists" => "La identificacion de muestra no existe " . $this->i . " no existe",
            "caracteristicas_{$this->i}.required" => "Ingrese las caracteristicas " . $this->i,
            "muestreador_{$this->i}.required" => "Ingrese el muestreador " . $this->i,
            "ph_{$this->i}.required" => "Ingrese el pH " . $this->i,
            "tratada_biologicamente_{$this->i}.boolean" => "Tratada biologicamente " . $this->i . " debe ser verdadero o falso",
            "cloro_{$this->i}" => "Ingrese el cloro " . $this->i,
            "valor_cloro_{$this->i}.required_if" => "El valor del cloro " . $this->i . " es requerido cuando el muestreo es Simple y el cloro es Ausente o Presente",
            "ph_cromo_hexavalente_{$this->i}.required" => "Ingrese el pH de cromo hexavalente " . $this->i,
            "tipo_muestreo_{$this->i}.required" => "Elija el tipo de muestreo " . $this->i,
            "fecha_muestreo_{$this->i}.required" => "Ingrese la fecha de muestreo " . $this->i,
            "fecha_muestreo_{$this->i}.date" => "Formato de fecha incorrecto en fecha de muestreo " . $this->i,
            "hora_muestreo_{$this->i}.required" => "Ingrese la hora de muestreo " . $this->i,
            "hora_muestreo_{$this->i}.date_format" => "Formato de hora incorrecto para la hora de muestreo " . $this->i,
            "fecha_final_muestreo_{$this->i}.required_if" => "La fecha de fin de muestreo " . $this->i . " es requerida cuando el tipo de muestreo es Compuesto",
            "fecha_final_muestreo_{$this->i}.date" => "Formato de fecha final de muestreo " . $this->i . " incorrecto",
            "hora_final_muestreo_{$this->i}.required_if" => "La hora de fin de muestreo " . $this->i . " es requerida si el tipo de muestreo es compuesto",
            "hora_final_muestreo_{$this->i}.date_format" => "Formato incorrecto de la hora de fin de muestreo " . $this->i,
            "fecha_composicion_{$this->i}.required_if" => "La fecha de composicion " . $this->i . " es requerida cuando el tipo de muestreo es Compuesto",
            "fecha_composicion_{$this->i}.date" => "Formato de fecha de composicion " . $this->i . " incorrecto",
            "hora_composicion_{$this->i}.required_if" => "La hora de composicion " . $this->i . " es requerida si el tipo de muestreo es compuesto",
            "hora_composicion_{$this->i}.date_format" => "Formato incorrecto de la hora de composicion " . $this->i,
            "flujo_1_{$this->i}.required_if" => "El flujo 1 de la muestra " . $this->i . " es requerido para las muestras compuestas",
            "flujo_2_{$this->i}.required_if" => "El flujo 2 de la muestra " . $this->i . " es requerido para las muestras compuestas",
            "flujo_3_{$this->i}.required_if" => "El flujo 3 de la muestra " . $this->i . " es requerido para las muestras compuestas",
            "flujo_4_{$this->i}.required_if" => "El flujo 4 de la muestra " . $this->i . " es requerido para las muestras compuestas",
            "flujo_5_{$this->i}.required_if" => "El flujo 5 de la muestra " . $this->i . " es requerido para las muestras compuestas",
            "flujo_6_{$this->i}.required_if" => "El flujo 6 de la muestra " . $this->i . " es requerido para las muestras compuestas",
            "parametros_{$this->i}.required" => "Ingrese los parametros " . $this->i,
            "otros_{$this->i}.required" => "Indique los parametros en el campo otros " . $this->i,
            "preservacion_correcta_{$this->i}.required" => "Elija una opcion de preservada correctamente " . $this->i,
        ];
    }
}
