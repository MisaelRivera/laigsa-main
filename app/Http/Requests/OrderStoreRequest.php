<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class OrderStoreRequest extends FormRequest
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
            'folio' => 'required|unique:ordenes,folio|integer',
            'numero_muestras' => 'required|integer|min:0|max:30',
            'aguas_alimentos' => 'required|string',
            'cliente' => 'required|exists:clientes,cliente',
            'fecha_recepcion' => 'nullable|date',
            'hora_recepcion' => 'nullable',
            'numero_cotizacion' => 'nullable',
            'numero_termometro' => 'nullable',
            'temperatura' => 'nullable',
            'observaciones' => 'nullable',
            'aguas_alimentos' => ['required', Rule::in(['Aguas', 'Alimentos'])],
            'cesavedac' => 'required|boolean',
            'area_recepcion_muestras_limpia' => 'required|boolean',
        ];
    }

    public function messages()
    {
        return [
            'folio.unique' => 'El folio ingresado ya existe',
            'folio.required' => 'Ingrese el folio'
        ];
    }
}
