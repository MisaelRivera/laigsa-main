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
use App\Api\OrdersApi;
use Illuminate\Support\Facades\Validator;

class WaterSamplesController extends Controller
{
    public $createFields = [
        'tipo_muestra', 'id_identificacion_muestra', 'caracteristicas',
        'muestreador', 'ph', 'tratada_biologicamente', 'cloro', 'valor_cloro',
        'ph_cromo_hexavalente', 'tipo_muestreo', 'fecha_muestreo', 'hora_muestreo',
        'fecha_final_muestreo', 'hora_final_muestreo', 'fecha_composicion', 'hora_composicion',
        'flujo_1', 'flujo_2', 'flujo_3', 'flujo_4', 'flujo_5', 'flujo_6',
        'parametros', 'otros', 'preservacion_correcta', 'offset', 'offset2'
    ];

    public $createFieldsV2 = [
        'tipo_muestra', 'id_identificacion_muestra', 'caracteristicas',
        'muestreador', 'ph', 'tratada_biologicamente', 'cloro', 'valor_cloro',
        'ph_cromo_hexavalente', 'tipo_muestreo', 'fecha_muestreo', 'hora_muestreo',
        'fecha_final_muestreo', 'hora_final_muestreo', 'fecha_composicion', 'hora_composicion',
        'flujo_1', 'flujo_2', 'flujo_3', 'flujo_4', 'flujo_5', 'flujo_6',
        'parametros', 'otros', 'norma', 'parametros_seleccionados', 'preservacion_correcta', 'offset', 'tiene_incertidumbre', 'incertidumbre'
    ];

    public $oldParams = [
        [ 'value' => null, 'label' => 'Elija un parametro' ],
        "NOM-001-SEMARNAT-2021",
        "NOM-001-SEMARNAT-2021- incluir DBO5, Solidos Sedimentables, Materia Flotante, Coliformes Fecales",
        "Nom-001-semarnat-1996",
        "Nom-001-semarnat-1996/color verd, cloruros, e. coli, enterococos fecales. Contratar toxicidad vibrio fisheri,  cot",
        "Nom-001-semarnat-1996/sin met y cn", "NOM-127-SSA1-2021 Norma completa",
        "NOM-127-SSA1-2021, Parte de la Norma",
        "Nom-127-ssa1-1994. Parte de la norma",
        "Nom-127-ssa1-1994. Parte de la norma/con olor y sabor",
        "Nom-127-ssa1-1994. Norma completa/con olor y sabor",
        "Nom-002-semarnat-1996",
        "Nom-003-semarnat-1996",
        "CT, As, Pb, Fluor",
        "CF, CT (purificada)",
        "CT (purificada)",
        "Salmonella. Contratar toxicidad",
        "Dureza, alcalinidad, ph, conductividad, metales.",
        "E. Coli, cf, ct de nom-127-ssa1-1994.",
        "Mesofilicos aerobios",
        "Ph, cn",
        "Sst, ss, dqo, ntk, nitratos, nitritos, fosforo total, nitrogeno total",
        "Nom-004-semarnat-2002",
        "Nom-004: ph, conductividad, sulfatos, nitratos, cloruros, dt, sdt, cf, ca, na, k",
        "Nom-127: cn",
        "Nom-127-ssa1-1994/ contratar: btex, trihalometanos, fenoles, yodo residual",
        "Ph, cn",
        "Otro"
    ];

    public function create (Order $order, $numero_muestras)
    {
        $order->load(['cliente.identificaciones_muestra', 'muestras_aguas']);
        $data = [
            'order' => $order,
            'numeroMuestras' => (int)$numero_muestras,
            'inicioMuestras' => $order->muestras_aguas->count(),
            'parametersProp' => Rule::where('aguas', 1)
                ->get(),
            'previousRouteName' => getPreviousURL(),
            'createFields' => $this->createFields,
            'oldParams' => $this->oldParams,
        ];

        return Inertia::render('samples/CreateWater', $data);
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
        $sample->tipo_muestreo_show = str_replace(' ', '_', $sample->tipo_muestreo);
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

    public function createV2 (Order $order, $numero_muestras)
    {
        $order = $order->load('cliente.identificaciones_muestra');
        $data = [
            'order' => $order,
            'numeroMuestras' => (int) $numero_muestras,
            'inicioMuestras' => (int) $order->muestras_aguas->count(),
            'createFields' => $this->createFieldsV2,
            'oldParams' => $this->oldParams,
            'previousRouteName' => getPreviousURL()
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

    public function getAllParams ()
    {
        $params = RuleParameterCombinationWater::with(['combinacionParametro', 'parametro', 'metodo', 'unidad'])
            ->get()
            ->map(function ($param) {
                return [
                    'value' => $param->combinacionParametro->id,
                    'label' => $param->combinacionParametro->alias
                ];
            });
        return response()->json($params);
    }

    public function store (Order $order, $numero_muestras, Request $request)
    {
        $order->load('muestras_aguas');
        $samples = [];

        $existentSamples = $order->muestras_aguas->count();
        for ($i = $existentSamples + 1; $i <= $numero_muestras; $i++) {

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

            $validatedData["id_orden_$i"] = $order->id;
            $validatedData["numero_muestra_$i"] = $i;
            $sample = removeDynamicPostfixFromKeys($validatedData);
            array_push($samples, $sample);
        }

        foreach ($samples as $sampleInstance) {
            WaterSample::create($sampleInstance);
        }

        return redirect()
            ->route('orders.show', ['order' => $order->id])
            ->with('message', 'La orden y sus muestras se han creado correctamente');
    }

    /*public function addSamples (Order $order, $numero_muestras)
    {
        $muestrasExistentes = $order->muestras_aguas->count();
        $order->numero_muestras = $muestrasExistentes + $numero_muestras;
        $order->save();
        $route = (int)$order->v_libreta_resultados === 0 ? 'water_samples.create':'water_samples.create_v2';
        
        return redirect()
            ->route($route, ['folio' => $order->folio]);
    }*/

    public function storeV2 (Order $order, $numero_muestras, Request $request)
    {
        $order->load('muestras_aguas');
        $samples = [];

        $existentSamples = $order->muestras_aguas->count();
        for ($i = $existentSamples + 1; $i <= $numero_muestras; $i++) {

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
            $parametrosSeleccionados = $request->input("parametros_seleccionados_$i");
            $validatedData = $validator->validate();

            if (count($parametrosSeleccionados) === 0) {
                return redirect()
                        ->back()
                        ->withErrors([
                            'errors' => "Elije parametros para la muestra $i"
                        ]);
            }

            if ($validatedData["parametros_$i"] === "Otro") {
                $validatedData["parametros_$i"] = $request->input("otros_$i");
                $validatedData["otros_$i"] = 1;
            }

            $validatedData["id_orden_$i"] = $order->id;
            $validatedData["numero_muestra_$i"] = $i;
            $sample = removeDynamicPostfixFromKeys($validatedData);
            array_push($samples, $sample);
        }

        foreach ($samples as $sampleInstance) {
            WaterSample::create($sampleInstance);
        }

        return redirect()
            ->route('orders.show', ['order' => $order->id])
            ->with('message', 'La orden y sus muestras se han creado correctamente');
    }

    public function update (WaterSample $waterSample, WaterSampleUpdateRequest $request)
    {
        $editedSample = $request->validated();
        $editedSample['id_orden'] = $waterSample->id_orden;
        $editedSample = handleSingularCasesOnUpdateWaterSample($request, $editedSample);
        foreach ($editedSample as $key => $value) {
            $waterSample->{$key} = $value;
            echo $waterSample->{$key} . '<br>';
        }
        die();
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

    public function destroy (WaterSample $waterSample)
    {
        $sampleNumber = $waterSample->numero_muestra;
        $order = Order::findOrFail($waterSample->id_orden);
        $waterSample->delete();
        $samples = $order->muestras_aguas;
        for ($i = 0; $i < count($samples); $i++) {
            $samples[$i]->numero_muestra = ($i + 1);
            $samples[$i]->save();
        }
        return redirect()
            ->route('orders.show', [
                'order' => $order->id
            ])
            ->with('message', "Se ha eliminado la muestra $sampleNumber");
    }
}
