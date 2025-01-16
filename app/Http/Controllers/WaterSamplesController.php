<?php

namespace App\Http\Controllers;

use App\Http\Requests\WaterSampleStoreRequest;
use App\Http\Requests\WaterSampleUpdateRequest;
use App\Http\Requests\WaterSampleUpdateAllRequest;
use App\Models\WaterSample;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Order;
use App\Models\Rule;
use App\Models\RuleParameterCombinationWater;
use App\Models\SampleIdentification;
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

    public function edit (WaterSample $sample)
    {
        $sample->orden = $sample->orden()->get()[0];

        $identificacionesMuestras = SampleIdentification::where('id', $sample->id_identificacion_muestra)
            ->get()
            ->map(function ($sampleIdentification) {
                return [
                    'value' => $sampleIdentification->id,
                    'label' => $sampleIdentification->identificacion_muestra
                ];
            })->prepend(collect([
                'value' => null,
                'label' => 'Elija una identificacion de muestra'
            ]));

        return Inertia::render('samples/EditWaterSample', [
            'sample' => $sample,
            'identificacionesMuestras' => $identificacionesMuestras
        ]);
    }

    public function editAllWater ($folio) 
    {
        $order = Order::with(['cliente.identificaciones_muestra', 'muestras_aguas'])->where('folio', $folio)->first();
        
        $data = [
            'order' => $order,
            'parametersProp' => Rule::where('aguas', 1)
            ->get()
        ];
      
       return Inertia::render('samples/EditAllWater', $data);
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
            $data['rules'] = Rule::where('aguas', 1)
                ->get()
                ->map(function ($rule) {
                    return [
                        'value' => $rule->id,
                        'label' => $rule->norma
                    ];
                });
            return Inertia::render('samples/CreateWaterV2', $data);
        } else {

        }
    }

    public function getRuleParams ($ruleId)
    {
        $params = RuleParameterCombinationWater::with(['combinacionParametro', 'parametro', 'metodo', 'unidad'])
            ->where('id_norma', $ruleId)
            ->get()
            ->map(function ($item) {
                return [
                    'value' => $item->combinacionParametro->id,
                    'label' => $item->combinacionParametro->alias,
                ];
            });
        return response()->json($params);
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

    public function update (WaterSample $waterSample, WaterSampleUpdateRequest $request)
    {
        $editedSample = $request->validated();
        $editedSample['id_orden'] = $waterSample->id_orden;
        $editedSample = handleSingularCasesOnUpdateWaterSample($request, $editedSample);
        foreach ($editedSample as $key => $value) {
            $waterSample->{$key} = $value;
        }
        $waterSample->save();
        return redirect()
            ->route('orders.show', ['id' => $waterSample->id_orden])
            ->with('message', "Muestra editada correctamente");

    }

    public function updateAll ($id_orden, Request $request)
    {
        $samples = WaterSample::where('id_orden', $id_orden);
        $samplesValidated = [];
        for ($i = 0; $i < $samples->count(); $i++) {
            $waterSampleRequest = new WaterSampleUpdateAllRequest();
            $waterSampleRequest->setIteration($i);
            $validator = Validator::make(
                $waterSampleRequest->values($request),
                $waterSampleRequest->rules(),
                $waterSampleRequest->messages()
            );

            if ($validator->fails()) {
                return redirect()
                    ->back()
                    ->withErrors($validator);
            }

            $validatedData = $validator->validate();

            $sampleData = removeDynamicPostfixFromKeys($validatedData);
            $sampleData = handleSingularCasesOnUpdateAllWaterSamples($sampleData);
            array_push($samplesValidated, $sampleData);
        }
        
        $samples->each(function ($item, $index) use($samplesValidated) {
            foreach ($samplesValidated[$index] as $key => $value) {
                $item->{$key} = $value; 
            }
            $item->save();
        });
        
        return redirect()
            ->route('orders.show', ['id' => $id_orden])
            ->with('message', 'La orden y sus muestras se han editado correctamente');
    }

    public function handlePreservationSubmit (WaterSample $waterSample, Request $request)
    {
        $waterSample->preservacion_correcta = $request->input('preservacion_correcta');
        $waterSample->save();
        return redirect()
            ->route('orders.show', ['id' => $waterSample->id_orden])
            ->with('message', "La preservacion correcta de la muestra $waterSample->numero_muestra ha sido editada correctamente");
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


