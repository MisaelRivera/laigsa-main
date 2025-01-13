<?php

namespace App\Http\Controllers;

use App\Http\Requests\WaterSampleStoreRequest;
use App\Http\Requests\WaterSampleUpdateRequest;
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

    public function editAllWater ($folio, $aguas_alimentos) 
    {
        $order = null;
        
        if ($aguas_alimentos === 'Aguas') {
            $order = Order::with(['cliente.identificaciones_muestra', 'muestras_aguas'])->where('folio', $folio)->first();
        } else {
            $order = Order::with(['muestras_alimentos'])->where('folio', $folio)->first();
        }
        
        $data = [
            'order' => $order,
        ];
      
        if ($order->aguas_alimentos === 'Aguas') {
            $data['parametersProp'] = Rule::where('aguas', 1)
                ->get();
            return Inertia::render('samples/EditAllWater', $data);
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

    public function update (WaterSample $sample, WaterSampleUpdateRequest $request)
    {
        $editedSample = $request->validated();
        $sample->tipo_muestra = $editedSample['tipo_muestra'];
        $sample->id_identificacion_muestra = $editedSample['id_identificacion_muestra'];
        $sample->caracteristicas = $editedSample['caracteristicas'];
        $sample->muestreador = $editedSample['muestreador'];
        $sample->pH = $editedSample['pH'];
        $sample->tratada_biologicamente = $editedSample['tratada_biologicamente'];
        $sample->cloro = $editedSample['cloro'];
        $sample->ph_cromo_hexavalente = $editedSample['ph_cromo_hexavalente'];
        $sample->tipo_muestreo = $editedSample['tipo_muestreo'];
        $sample->fecha_muestreo = $editedSample['fecha_muestreo'];
        $sample->hora_muestreo = $editedSample['hora_muestreo'];
        $sample->preservacion_correcta = $editedSample['preservacion_correcta'];
        $isCloroPresente = $editedSample['cloro'] == 'Presente';
        $isCloroAusente = $editedSample['cloro'] == 'Ausente';
        $isTipoMuestreoSimple = $editedSample['tipo_muestreo'] == 'Simple';
        $isTipoMuestreoCompuesto4 = $editedSample['tipo_muestreo'] == 'Compuesto_4';
        $isTipoMuestreoCompuesto6 = $editedSample['tipo_muestreo'] == 'Compuesto_6';
        $isParametroOtro = $editedSample['parametros'] = 'Otro';
        if (( $isCloroAusente || $isCloroPresente)
          && $isTipoMuestreoSimple) {
            $sample->valor_cloro = $editedSample['valor_cloro'];
        } else {
            $sample->valor_cloro = 'N/A';
        }

        if ($isTipoMuestreoCompuesto4 || $isTipoMuestreoCompuesto6) {
            $sample->fecha_final_muestreo = $editedSample['fecha_final_muestreo'];
            $sample->hora_final_muestreo = $editedSample['hora_final_muestreo'];
            $sample->fecha_composicion = $editedSample['fecha_composicion'];
            $sample->hora_composicion = $editedSample['hora_composicion'];
            $sample->flujo_1 = $editedSample['flujo_1'];
            $sample->flujo_2 = $editedSample['flujo_2'];
            $sample->flujo_3 = $editedSample['flujo_3'];
            $sample->flujo_4 = $editedSample['flujo_4'];
            if ($editedSample['tipo_muestreo'] == 'Compuesto_6') {
                $sample->flujo_5 = $editedSample['flujo_5'];
                $sample->flujo_6 = $editedSample['flujo_6'];
            } else {
                $sample->flujo_5 = 'NA';
                $sample->flujo_6 = 'NA';
            }
        } else {
            $sample->fecha_final_muestreo = null;
            $sample->hora_final_muestreo = null;
            $sample->fecha_composicion = null;
            $sample->hora_composicion = null;
            $sample->flujo_1 = 'NA';
            $sample->flujo_2 = 'NA';
            $sample->flujo_3 = 'NA';
            $sample->flujo_4 = 'NA';
            $sample->flujo_5 = 'NA';
            $sample->flujo_6 = 'NA';
        }

        if ($isParametroOtro) {
            $sample->otros_parametros = 1;
            $sample->parametros = $editedSample['otros'];
        } else {
            $sample->otros_parametros = 0;
            $sample->parametros = $editedSample['parametros'];
        }

        $sample->update();
        return redirect()
            ->route('orders.show', $sample->id_orden)
            ->with('message', "Muestra editada correctamente");

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


