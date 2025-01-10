<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class WaterSampleUpdateRequest extends FormRequest
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
            "tipo_muestra" => 'required',
            "id_identificacion_muestra" => 'required|exists:identificacion_muestras,id',
            "caracteristicas" => 'required',
            "muestreador" => 'required',
            "ph" => "required",
            "tratada_biologicamente" => "boolean",
            "cloro" => "required",
            "valor_cloro" => "sometimes:tipo_muestreo,Simple|required_if:cloro,Presente,Ausente",
            "ph_cromo_hexavalente" => "required",
            "tipo_muestreo" => "required",
            "fecha_muestreo" => "required|date",
            "hora_muestreo" => "required|date_format:H:i",
            "fecha_final_muestreo" => "required_if:tipo_muestreo,Compuesto_4,Compuesto_6|date",
            "hora_final_muestreo" => "required_if:tipo_muestreo,Compuesto_4,Compuesto_6|date_format:H:i",
            "fecha_composicion" => "required_if:tipo_muestreo,Compuesto_4,Compuesto_6|date",
            "hora_composicion" => "required_if:tipo_muestreo,Compuesto_4,Compuesto_6|date_format:H:i",
            "flujo_1" => "required_if:tipo_muestreo,Compuesto_4,Compuesto_6",
            "flujo_2" => "required_if:tipo_muestreo,Compuesto_4,Compuesto_6",
            "flujo_3" => "required_if:tipo_muestreo,Compuesto_4,Compuesto_6",
            "flujo_4" => "required_if:tipo_muestreo,Compuesto_4,Compuesto_6",
            "flujo_5" => "required_if:tipo_muestreo,Compuesto_6",
            "flujo_6" => "required_if:tipo_muestreo,Compuesto_6",
            "parametros" => "required",
            "otros" => "sometimes|required",
            "preservacion_correcta" => "required",
        ];
    }

    public function messages()
    {
        return [
            "tipo_muestra.required" => "Ingrese el tipo de muestra ",
            "id_identificacion_muestra.required" => "Elija la identificacion de muestra ",
            "id_identificacion_muestra.exists" => "La identificacion de muestra no existe",
            "caracteristicas.required" => "Ingrese las caracteristicas",
            "muestreador.required" => "Ingrese el muestreador",
            "ph.required" => "Ingrese el pH ",
            "tratada_biologicamente.boolean" => "Tratada biologicamente debe ser verdadero o falso",
            "cloro" => "Ingrese el cloro ",
            "valor_cloro.required_if" => "El valor del cloro es requerido cuando el muestreo es Simple y el cloro es Ausente o Presente",
            "ph_cromo_hexavalente.required" => "Ingrese el pH de cromo hexavalente",
            "tipo_muestreo.required" => "Elija el tipo de muestreo",
            "fecha_muestreo.required" => "Ingrese la fecha de muestreo",
            "fecha_muestreo.date" => "Formato de fecha incorrecto en fecha de muestreo",
            "hora_muestreo.required" => "Ingrese la hora de muestreo",
            "hora_muestreo.date_format" => "Formato de hora incorrecto para la hora de muestreo",
            "fecha_final_muestreo.required_if" => "La fecha de fin de muestreo es requerida cuando el tipo de muestreo es Compuesto",
            "fecha_final_muestreo.date" => "Formato de fecha final de muestreo incorrecto",
            "hora_final_muestreo.required_if" => "La hora de fin de muestreo es requerida si el tipo de muestreo es compuesto",
            "hora_final_muestreo.date_format" => "Formato incorrecto de la hora de fin de muestreo ",
            "fecha_composicion.required_if" => "La fecha de composicion es requerida cuando el tipo de muestreo es Compuesto",
            "fecha_composicion.date" => "Formato de fecha de composicion incorrecto",
            "hora_composicion.required_if" => "La hora de composicion es requerida si el tipo de muestreo es compuesto",
            "hora_composicion.date_format" => "Formato incorrecto de la hora de composicion",
            "flujo_1.required_if" => "El flujo 1 de la muestra es requerido para las muestras compuestas",
            "flujo_2.required_if" => "El flujo 2 de la muestra es requerido para las muestras compuestas",
            "flujo_3.required_if" => "El flujo 3 de la muestra es requerido para las muestras compuestas",
            "flujo_4.required_if" => "El flujo 4 de la muestra es requerido para las muestras compuestas",
            "flujo_5.required_if" => "El flujo 5 de la muestra es requerido para las muestras compuestas",
            "flujo_6.required_if" => "El flujo 6 de la muestra es requerido para las muestras compuestas",
            "parametros.required" => "Ingrese los parametros",
            "otros.required" => "Indique los parametros en el campo otros",
            "preservacion_correcta.required" => "Elija una opcion de preservada correctamente",
        ];
    }
}
