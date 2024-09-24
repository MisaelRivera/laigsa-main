<?php

namespace App\Http\Controllers;

use App\Models\WaterSample;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Order;
use App\Models\Rule;

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
        $request->validate([
            'samples.*.tipo_muestra' => 'required',
            'samples.*.identificacion_muestra' => 'required',
            'samples.*.caracteristicas' => 'required',
            'samples.*.ph' => 'required',
        ]);
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
