<?php

namespace App\Http\Controllers;

use App\Models\WaterSample;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Order;
use App\Models\Rule;
use Illuminate\Support\Facades\Validator;

class WaterSamplesController extends Controller
{
    public function create ($folio, $numero_muestras, $inicio_muestras)
    {
        $order = Order::with('cliente')->where('folio', $folio)->first();
        $data = [
            'order' => $order,
            'numeroMuestras' => (int) $numero_muestras,
            'identificacionesMuestra' => $order->cliente->nombreIdentificacionesMuestra(),
            'inicioMuestras' => (int) $inicio_muestras,
        ];
      
        if ($order->aguas_alimentos === 'Aguas') {
            $data['parametersProp'] = Rule::where('aguas', 1)
                ->get();
            return Inertia::render('samples/CreateWater', $data);
        } else {

        }
    }

    public function store (Request $request)
    {
        $inicio_muestras = $request->query('inicio_muestras');
        $numero_muestras = $request->query('numero_muestras');
        for ($i = $inicio_muestras + 1; $i <= $inicio_muestras + $numero_muestras; $i++) {
            $validator = Validator::make([
                "tipo_muestra_$i" => $request->input("tipo_muestra_$i"),
                "identificacion_muestra_$i" => $request->input("identificacion_muestra_$i"),
                "caracteristicas_$i" => $request->input("caracteristicas_$i"),
                "ph_$i" => $request->input("ph_$i"),
            ], [
               "tipo_muestra_$i" => 'required',
                "identificacion_muestra_$i" => 'required',
                "caracteristicas_$i" => 'required',
                "ph_$i" => "required",
            ],[
                "tipo_muestra_$i.required" => "Ingrese el tipo de muestra $i",
                "identificacion_muestra_$i.required" => "Ingrese la identificacion de muestra $i",
                "caracteristicas_$i.required" => "Ingrese las caracteristicas $i",
                "ph_$i.required" => "Ingrese el ph $i",
            ]);

            if ($validator->fails()) {
                return back()
                    ->withErrors($validator);
            }
        }
    }

    public function destroy (WaterSample $waterSample)
    {
        $sampleNumber = $waterSample->numero_muestra;
        $order = Order::findOrFail($waterSample->id_orden);
        $waterSample->delete();
        return redirect()
            ->route('orders.show', [
                'id' => $order->id
            ])
            ->with('message', "Se ha eliminado la muestra $sampleNumber");
    }
}
