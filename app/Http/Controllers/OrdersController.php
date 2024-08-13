<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\WaterSample;
use App\Models\FoodSample;
use App\Models\Client;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;

class OrdersController extends Controller
{
    //
    public function index (Request $request)
    {
        $page = $request->has('page') ? $request->query('page'):1;
        $items = $request->has('items') ? $request->query('items'):40;
        $pages = $request->has('pages') ? $request->query('pages'):10;
        $orders = Order::leftJoin('siralab', 'ordenes.id', '=', 'siralab.id_orden')
            ->join('clientes', 'ordenes.id_cliente', '=', 'clientes.id')
            ->select('ordenes.*', 'siralab.id as siralab_id', 'siralab.hoja_campo', 'siralab.cadena_custodia', 'siralab.croquis', 'clientes.cliente')
            ->orderBy('fecha_recepcion', 'desc')
            ->orderBy('hora_recepcion', 'desc')
            ->orderBy('folio', 'desc')
            ->offset($page - 1)
            ->limit($items)
            ->get();
        foreach ($orders as $order) {
            if ($order->aguas_alimentos === 'Aguas') {
                $order->muestras = WaterSample::rightJoin('identificacion_muestras', 'identificacion_muestras.id', '=', 'muestras_aguas.id_identificacion_muestra')
                    ->select('muestras_aguas.*', 'identificacion_muestras.identificacion_muestra', 'identificacion_muestras.latitud', 'identificacion_muestras.longitud', 'identificacion_muestras.siralab')
                    ->where('id_orden', $order->id)
                    ->get();
            } else {
                $order->muestras = FoodSample::where('id_orden', $order->id)
                    ->get();
            }
        }
        return Inertia::render('orders/Index', [
            'ordersProp' => $orders,
            'itemsProp' => $items,
            'pageProp' => $page,
            'pagesProp' => $pages,
            'totalItems' => Order::count()
        ]);
    }

    public function create ()
    {
        $last_order = Order::getOrderWithLastFolio();
        return Inertia::render('orders/Create', ['last_order' => $last_order]);
    }

    public function changePage (Request $request)
    {
        $page = $request->query('page');
        $orders = Order::leftJoin('siralab', 'ordenes.id', '=', 'siralab.id_orden')
            ->join('clientes', 'ordenes.id_cliente', '=', 'clientes.id')
            ->select('ordenes.*', 'siralab.id as siralab_id', 'siralab.hoja_campo', 'siralab.cadena_custodia', 'siralab.croquis', 'clientes.cliente')
            ->orderBy('fecha_recepcion', 'desc')
            ->orderBy('hora_recepcion', 'desc')
            ->orderBy('folio', 'desc')
            ->offset(($page - 1) * 40)
            ->limit(40)
            ->get();
        return response()->json($orders);
    }

    public function getClientForOrder (Request $request)
    {
        $search_text = $request->query('search');
        $clients = Client::where('cliente', 'like', "%" . $search_text . "%")
            ->limit(10)
            ->get();
        return response()->json($clients);
    }

    public function store (Request $request)
    {
        $order = $request->validate([
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
        ]);

        $client = Client::where('cliente', $order['cliente'])->first();
       $order['direccion_muestreo'] = $client->direccion_muestreo;
       unset($order['cliente']);
       $order['id_cliente'] = $client->id;

        $order = Order::create($order);
        $folio = $request->input('folio');
        $numero_muestras = $request->input('numero_muestras');

        /*$order->v_libreta_resultados = 1;
        $order->save();*/
        $route_name = 'samples.create';
        if ($order->aguas_alimentos === 'Aguas') {
            $route_name .= '_water';
        }
        return redirect()
            ->route($route_name, [$folio, $numero_muestras, 1])
            ->with('message', 'Se ha creado una nuva orden correctamente. A continuacion cree las muestras de la orden');
    }

    public function show ($id)
    {
        $order = Order::where('id', $id)
            ->with(['cliente'])
            ->first();
        
        if(!isset($order)) {
            return redirect()->route('orders.index')->with('error', 'La orden especificada no existe!');
        } 

        if ($order->aguas_alimentos === 'Aguas') {
            $order->muestras = WaterSample::leftJoin('identificacion_muestras', 'muestras_aguas.id_identificacion_muestra', '=', 'identificacion_muestras.id')
                ->select('muestras_aguas.*', 'identificacion_muestras.identificacion_muestra', 'identificacion_muestras.latitud', 'identificacion_muestras.longitud', 'identificacion_muestras.siralab', 'identificacion_muestras.obsoleta')
                ->where('id_orden', $order->id)
                ->get();
        } else {
            $order->muestras = FoodSample::where('id_orden', $order->id)
            ->get();
        }

        return Inertia::render('orders/Show', ['order' => $order]);
    }

    public function edit ($id)
    {
        $order = Order::with(['cliente'])
            ->where('id', $id)
            ->first();
        
        if (!isset($order)) {
            return back()->with('error', 'La orden indicada no existe');
        }

        $data = [
            'lastOrder' => Order::getOrderWithLastFolio(),
            'orderProp' => $order,
        ];
        $data['aguas'] = $data['lastOrder']->aguas_alimentos === 'Aguas'; 
        return Inertia::render('orders/Edit', $data);
    }

    public function update (Request $request, $id)
    {
        $order = $request->validate([
            'cliente' => 'required|exists:clientes,cliente',
            'direccion_muestreo' => 'required|exists:clientes,direccion_muestreo',
            'numero_cotizacion' => 'nullable',
            'numero_termometro' => 'nullable',
            'temperatura' => 'nullable',
            'observacion' => 'nullable',
            'fecha_recepcion' => 'nullable|date',
            'hora_recepcion' => 'nullable',
            'aguas_alimentos' => ['required', Rule::in(['Aguas', 'Alimentos'])],
            'cesavedac' => 'required|boolean',
            'area_recepcion_muestras_limpia' => 'required|boolean',
        ]);

        $order['id_cliente'] = Client::select('id')
            ->where('cliente', $order['cliente'])
            ->first()['id'];
        unset($order['cliente']);

        Order::where('id', $id)
            ->update($order);

        return redirect()
        ->route("orders.show", [$id])
        ->with('message', 'Se ha editado la orden  correctamente.');
    }

    public function editPartialInfo (Request $request, $id)
    {
        $order = Order::where('id', $id)
            ->first();
        if (!isset($order)) {
            return back()->with('error', 'La orden especificada no existe');
        }

        $order->numero_cotizacion = $request->input('numero_cotizacion');
        $order->numero_termometro = $request->input('numero_termometro');
        $order->temperatura = $request->input('temperatura');

        $order->save();

        return redirect()
            ->route('orders.show', $id)
            ->with('message', "Se han editado correctamente los datos de la orden MFQ-$order->folio");
    }

    public function delete ($id)
    {
        $order = Order::where('id', $id)
            ->first();
        
        if (!isset($order)) {
            return redirect()
                ->route('orders.show', $id)
                ->with('error', 'La orden que intenta borrar no existe');
        }

        Order::where('id', $id)
            ->delete();

        return redirect()
            ->route('orders.index')
            ->with('message', "Se eliminado la orden $order->folio correctamente");
    }

    public function toggleCesavedac (Request $request) 
    {
        try {
            $request->validate([
                'order_id' => 'exists:ordenes,id'
            ]);
            $order = Order::find($request->input('order_id'));
            $order->reporte_cesavedac_entregado = !$request->input('value');
            $order->save();
            return response()->json(!$request->input('value'));
         } catch (\Exception $e) {
             Log::error('Error in toggleCesavedac: ' . $e->getMessage());
             return response()->json(['error' => 'Internal Server Error'], 500);
         }
     }
 
     public function toggleSupervision (Request $request) 
     {
         try {
             $request->validate([
                 'order_id' => 'exists:ordenes,id'
             ]);
             $order = Order::find($request->input('order_id'));
             $order->supervision = !$request->input('value');
             $order->save();
             return response()->json(!$request->input('value'));
          } catch (\Exception $e) {
              Log::error('Error in toggleSupervision: ' . $e->getMessage());
              return response()->json(['error' => 'Internal Server Error'], 500);
          }
      }
 
      public function toggleHojaCampo (Request $request) 
      {
         try {
             $request->validate([
                 'order_id' => 'exists:ordenes,id'
             ]);
             $order = Order::find($request->input('order_id'));
             $siralab = $order->siralab;
             $siralab->hoja_campo = !$request->input('value');
             $siralab->save();
             return response()->json(!$request->input('value'));
          } catch (\Exception $e) {
              Log::error('Error in toggleHojaCampo: ' . $e->getMessage());
              return response()->json(['error' => 'Internal Server Error'], 500);
          }
      }
 
     public function toggleCadenaCustodia (Request $request) 
     {
         try {
             $request->validate([
                 'order_id' => 'exists:ordenes,id'
             ]);
             $order = Order::find($request->input('order_id'));
             $siralab = $order->siralab;
             $siralab->cadena_custodia = !$request->input('value');
             $siralab->save();
             return response()->json(!$request->input('value'));
          } catch (\Exception $e) {
              Log::error('Error in toggleCadenaCustodia: ' . $e->getMessage());
              return response()->json(['error' => 'Internal Server Error'], 500);
          }
     }
 
     public function toggleCroquis (Request $request) 
     {
         try {
             $request->validate([
                 'order_id' => 'exists:ordenes,id'
             ]);
             $order = Order::find($request->input('order_id'));
             $siralab = $order->siralab;
             $siralab->croquis = !$request->input('value');
             $siralab->save();
             return response()->json(!$request->input('value'));
          } catch (\Exception $e) {
              Log::error('Error in toggleCroquis: ' . $e->getMessage());
              return response()->json(['error' => 'Internal Server Error'], 500);
          }
     }
 
     public function toggleReporteEntregado (Request $request) 
     {
         try {
             $request->validate([
                 'order_id' => 'exists:ordenes,id'
             ]);
             $order = Order::find($request->input('order_id'));
             $order->reporte_entregado = !$request->input('value');
             $order->save();
             return response()->json(!$request->input('value'));
          } catch (\Exception $e) {
              Log::error('Error in toggleReporteEntregado: ' . $e->getMessage());
              return response()->json(['error' => 'Internal Server Error'], 500);
          }
     }
 
     public function filter (Request $request) 
     {
         $clientes = Client::where('cliente', 'like', "%" . $request->input('client') . "%")->get()->toArray();
         $orders = Order::whereIn('id_cliente', array_column($clientes, 'id'))
             ->join('clientes', 'ordenes.id_cliente', '=', 'clientes.id')
             ->leftJoin('siralab', 'ordenes.id', '=', 'siralab.id_orden')
             ->select('ordenes.*', 'siralab.id as siralab_id', 'siralab.hoja_campo', 'siralab.cadena_custodia', 'siralab.croquis', 'clientes.cliente')
             ->orderBy('fecha_recepcion', 'desc')
             ->orderBy('hora_recepcion', 'desc')
             ->orderBy('cesavedac', 'asc')
             ->orderBy('folio', 'desc')
             ->paginate(40);
             foreach ($orders as $order) {
                if ($order->aguas_alimentos === 'Aguas') {
                    $order->muestras = WaterSample::leftJoin('identificacion_muestras', 'muestras_aguas.id_identificacion_muestra', '=', 'identificacion_muestras.id')
                        ->select('muestras_aguas.*', 'identificacion_muestras.identificacion_muestra')
                        ->where('id_orden', $order->id)
                        ->get();
                } else {
                    $order->muestras = FoodSample::where('id_orden', $order->id)
                        ->get();
                }
             }
         return response()->json($orders);
    }
}
