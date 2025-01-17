<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Rule;
use App\Models\FoodSample;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\FoodSampleStoreRequest;
use Inertia\Inertia;

class FoodSamplesController extends Controller
{
    public function create ($folio, $numero_muestras, $inicio_muestras)
    {
        $order = Order::where('folio', $folio)->first();
        $data = [
            'order' => $order,
            'numeroMuestras' => (int) $numero_muestras,
            'inicioMuestras' => (int) $inicio_muestras,
            'parametersProp' => Rule::where('aguas', 0)
            ->get()
        ];

        return Inertia::render('samples/CreateFood', $data);
    }

    public function store (Request $request)
    {
        $inicio_muestras = $request->query('inicio_muestras');
        $numero_muestras = $request->query('numero_muestras');
        $idOrden = $request->query('id_orden');
        $orden = Order::find($idOrden);
        $samples = [];
        for ($i = $inicio_muestras + 1; $i <= $inicio_muestras + $numero_muestras; $i++) {
    
            // Create an instance of the request and set the iteration
            $foodSampleRequest = new FoodSampleStoreRequest();
            $foodSampleRequest->setIteration($i);
            // Use Validator::make to validate the data
            $validator = Validator::make($foodSampleRequest->values($request), $foodSampleRequest->rules(), $foodSampleRequest->messages());
    
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
            FoodSample::create($sampleInstance);
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
}
