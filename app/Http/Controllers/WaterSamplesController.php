<?php

namespace App\Http\Controllers;

use App\Http\Requests\WaterSampleStoreRequest;
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
        $order = Order::with('cliente.identificaciones_muestra')->where('folio', $folio)->first();
        $data = [
            'order' => $order,
            'numeroMuestras' => (int) $numero_muestras,
            'inicioMuestras' => (int) $inicio_muestras,
        ];
      
        if ($order->aguas_alimentos === 'Aguas') {
            $data['parametersProp'] = Rule::where('aguas', 1)
                ->get();
            return Inertia::render('samples/CreateWater', $data);
        } else {

        }
    }

    public function createV2 ($folio, $numero_muestras, $inicio_muestras)
    {
        $order = Order::with('cliente.identificaciones_muestra')->where('folio', $folio)->first();
        $data = [
            'order' => $order,
            'numeroMuestras' => (int) $numero_muestras,
            'inicioMuestras' => (int) $inicio_muestras,
        ];
      
        if ($order->aguas_alimentos === 'Aguas') {
            $data['parametersProp'] = Rule::where('aguas', 1)
                ->get();
            return Inertia::render('samples/CreateWaterV2', $data);
        } else {

        }
    }

    public function store (Request $request)
    {
        $inicio_muestras = $request->query('inicio_muestras');
        $numero_muestras = $request->query('numero_muestras');
        $idOrden = $request->query('id_orden');
        $orden = Order::fing($idOrden);
        $samples = [];
        for ($i = $inicio_muestras + 1; $i <= $inicio_muestras + $numero_muestras; $i++) {
    
            // Create an instance of the request and set the iteration
            $waterSampleRequest = new WaterSampleStoreRequest();
            $waterSampleRequest->setIteration($i);
            // Use Validator::make to validate the data
            $validator = Validator::make($waterSampleRequest->values($request), $waterSampleRequest->rules(), $waterSampleRequest->messages());
    
            // If validation fails, handle the errors
            if ($validator->fails()) {
                return redirect()
                    ->back()
                    ->withErrors($validator);
            }

            $validatedData = $validator->validate();

            if ($validatedData["parametros_$i"] === "Otro") {
                $validatedData["parametros_$i"] = $request->input("otros_$i");
                $validatedData["otros_$i"] = 1;
            }
            $validatedData["id_orden_$i"] = (int)$idOrden;
            $validatedData["numero_muestra_$i"] = $i;
            $sample = removeDynamicPostfixFromKeys($validatedData);
            array_push($samples, $sample);
        }
        foreach ($samples as $sampleInstance) {
            WaterSample::create($sampleInstance);
        }

        if ($orden->v_libreta_resultados) {
            return redirect()
            ->route('orders.show_v2', ['id' => $idOrden])
            ->with('message', 'La orden y sus muestras se han creado correctamente');
        } else {
            return redirect()
                ->route('orders.show', ['id' => $idOrden])
                ->with('message', 'La orden y sus muestras se han creado correctamente');
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
