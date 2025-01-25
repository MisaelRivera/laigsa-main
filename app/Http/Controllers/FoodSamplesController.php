<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Rule;
use App\Models\FoodSample;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\FoodSampleStoreRequest;
use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\URL;

class FoodSamplesController extends Controller
{
    public function create ($folio, $numero_muestras, $inicio_muestras)
    {
        $order = Order::where('folio', $folio)->first();
        $previousUrl = URL::previous();

        // Create a request object for the previous URL
        $previousRequest = Request::create($previousUrl);

        // Match the previous request to a route
        $previousRoute = Route::getRoutes()->match($previousRequest);

        // Get the name of the previous route, if it has one
        $previousRouteName = $previousRoute->getName();
        $data = [
            'order' => $order,
            'numeroMuestras' => (int) $numero_muestras,
            'inicioMuestras' => (int) $inicio_muestras,
            'parametersProp' => Rule::where('aguas', 0)->get(),
            'previousRouteName' => $previousRouteName
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
            if ($request->query('request_origin') === 'orders.show') $orden->numero_muestras = $orden->numero_muestras + 1;
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
            
            if ($request->has("peso_muestra_$i")) $validatedData["peso_muestra_$i"] = $request->input("peso_muestra_$i");
            if ($request->has("temperatura_$i")) $validatedData["temperatura_$i"] = $request->input("temperatura_$i");
           
            $validatedData["id_orden_$i"] = (int)$idOrden;
            $validatedData["numero_muestra_$i"] = $i;
            $sample = removeDynamicPostfixFromKeys($validatedData);
            array_push($samples, $sample);
        }

        if ($request->query('request_origin') === 'orders.show') $orden->save();

        for ($i = 0; $i < $numero_muestras; $i++) {
            
            if(array_key_exists("latitud_grados", $samples[$i])) {
                $samples[$i]['latitud'] = '';
                $samples[$i]['longitud'] = '';
                $samples[$i]['latitud'] .= implodingCoordinates($samples[$i]["latitud_grados"], $samples[$i]["latitud_minutos"], $samples[$i]["latitud_segundos"], $samples[$i]["latitud_orientacion"]);
                $samples[$i]['longitud'] .= implodingCoordinates($samples[$i]["longitud_grados"], $samples[$i]["longitud_minutos"], $samples[$i]["longitud_segundos"], $samples[$i]["longitud_orientacion"]);
            }
            FoodSample::create($samples[$i]);
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

    public function edit (FoodSample $foodSample)
    {
        $foodSample->orden = $foodSample->orden()->get()[0];
        if ($foodSample->latitud !== 'N/A') {
            $latitud = explodingCoordinates($foodSample->latitud);
            $longitud = explodingCoordinates($foodSample->longitud);
            $foodSample->latitud_segundos = $latitud['segundos'];
            $foodSample->latitud_minutos = $latitud['minutos'];
            $foodSample->latitud_grados = $latitud['grados'];
            $foodSample->latitud_orientacion = $latitud['orientacion'];
            $foodSample->longitud_segundos = $longitud['segundos'];
            $foodSample->longitud_minutos = $longitud['minutos'];
            $foodSample->longitud_grados = $longitud['grados'];
            $foodSample->longitud_orientacion = $longitud['orientacion'];
        }

        return Inertia::render('samples/EditFood', [
            'foodSample' => $foodSample,
        ]);
    }

    public function update (FoodSample $foodSample)
    {

    }

    public function destroy (FoodSample $foodSample)
    {
        $sampleNumber = $foodSample->numero_muestra;
        $order = Order::findOrFail($foodSample->id_orden);
        
        $foodSample->delete();
        $order->numero_muestras = $order->numero_muestras - 1;
        $order->save();
        $samples = $order->muestras_alimentos;
        for ($i = 0; $i < count($samples); $i++) {
            $samples[$i]->numero_muestra = ($i + 1);
            $samples[$i]->save();
        }
        return redirect()
            ->route('orders.show', [
                'id' => $order->id
            ])
            ->with('message', "Se ha eliminado la muestra $sampleNumber");
    }
}
