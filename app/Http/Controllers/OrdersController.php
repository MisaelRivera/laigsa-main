<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrderStoreRequest;
use App\Models\Order;
use App\Filters\Order\OrderFiltersResolver;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\WaterSample;
use App\Models\FoodSample;
use App\Models\Client;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;
use App\Api\OrdersApi;
use Mpdf\Mpdf;
use Illuminate\Support\Facades\View;


class OrdersController extends Controller
{
    //
    public function index (Request $request)
    {   
        /*$filters = $request->all();
        $orders = (new OrderFiltersResolver($request))
            ->apply(Order::query())
            ->paginate(40);
        foreach ($orders as $order) {
            if ($order->aguas_alimentos === 'Aguas') {
                $order->muestras = $order->muestras_aguas;
                $order->muestras_count = count($order->muestras_aguas);
            } else {
                $order->muestras = $order->muestras_alimentos;
                $order->muestras_count = count($order->muestras_alimentos);
            }
        }
        return Inertia::render('orders/Index', [
            'ordersProp' => $orders,
            'filtersProp' => $filters
        ]);*/
        $filters = $request->only(['muestreador', 'cesavedac', 'siralab', 'supervision']);

        $orders = Order::query()
            ->with(['muestras_aguas.identificacionMuestraRelacion', 'muestras_alimentos']) // eager load
            ->when(isset($filters['cesavedac']) && (int)$filters['cesavedac'] === 1 ? true:null, fn($q) => $q->where('cesavedac', true))

            // Filter by sampler (search in both water and food samples)
            ->when($filters['muestreador'] ?? null, function ($q, $muestreador) {
                $q->where(function ($q) use ($muestreador) {
                    $q->whereHas('muestras_aguas', fn($q) => $q->where('muestreador', $muestreador))
                    ->orWhereHas('muestras_alimentos', fn($q) => $q->where('muestreador', $muestreador));
                });
            })

            // Filter orders that have Siralab water samples
            ->when(isset($filters['siralab']) && (int)$filters['siralab'] === 1 ? true:null, function ($q) {
                $q->whereHas('muestras_aguas.identificacionMuestraRelacion', fn($q) => $q->where('siralab', true));
            })

            // Filter orders that require supervision
            ->when(isset($filters['supervision']) && (int)$filters['supervision'] === 1 ? true:null, function ($q) {
                $muestreadoresForaneos = ['Irving', 'Pedro', 'Crisanta',
            'Julio', 'Miguel', 'Lizeth', 'Cliente']; // update as needed
                $q->where(function ($q) use ($muestreadoresForaneos) {
                    $q->whereHas('muestras_aguas', fn($q) =>
                            $q->whereNotIn('muestreador', $muestreadoresForaneos)
                        )
                    ->orWhereHas('muestras_alimentos', fn($q) =>
                            $q->whereNotIn('muestreador', $muestreadoresForaneos)
                        );
                })->where('cesavedac', false);
            })

            ->paginate(10)
            ->appends($filters); // preserve filters in pagination links

        return Inertia::render('orders/Index', [
            'orders' => $orders,
            'filters' => $filters,
        ]);
    }

    public function handlePreservationSubmit (WaterSample $waterSample, Request $request)
    {
        $waterSample->preservacion_correcta = $request->input('preservacion_correcta');
        $waterSample->save();
        return redirect()
            ->route('orders.show', ['id' => $waterSample->id_orden])
            ->with('message', "La preservacion correcta de la muestra $waterSample->numero_muestra ha sido editada correctamente");
    }

    public function create ()
    {
        $last_order = Order::getOrderWithLastFolio();
        return Inertia::render('orders/Create', ['last_order' => $last_order]);
    }

    public function getClientForOrder (Request $request)
    {
        $search_text = $request->query('search');
        $clients = Client::where('cliente', 'like', "%" . $search_text . "%")
            ->limit(10)
            ->get();
        return response()->json($clients);
    }

    public function store (OrderStoreRequest $request)
    {
        $order = $request->validated();
        unset($order->numero_muestras);
        $client = Client::where('id', $order['id_cliente'])->first();
        if (!$client->identificaciones_muestra_activas) {
            return redirect()
                ->route('orders.create')
                ->with('error', "El cliente no tiene identificaciones de muestra asignadas");
                exit();
        }
        $order['direccion_muestreo'] = $client->direccion_muestreo;
        $order = Order::create($order);
        $numero_muestras = $request->input('numero_muestras');
        $order = Order::find($order->id);
        /*$order->v_libreta_resultados = 1;
        $order->save();*/
        $route_name = '';
        if ($order->aguas_alimentos === 'Aguas') {
            if ((int)$order->v_libreta_resultados)
                $route_name .= 'water_samples.create_v2';
            else $route_name .= 'water_samples.create';
        }

        return redirect()
            ->route($route_name, [$order->id, $numero_muestras])
            ->with('message', 'Se ha creado una nuva orden correctamente. A continuacion cree las muestras de la orden');
    }

    public function show (Order $order)
    {
        $order->load(['cliente']);

        if ($order->aguas_alimentos === 'Aguas') {
            $order->load(['muestras_aguas', 'muestras_aguas.identificacionMuestraRelacion']);
            $order->muestras = $order->muestras_aguas;
        } else {
            $order->muestras = FoodSample::where('id_orden', $order->id)
                ->get();
        }
        
        return Inertia::render('orders/Show', [
            'order' => $order,
            'numeroMuestrasActual' => $order->muestras_aguas->count()
        ]);
    }

    public function edit (Order $order)
    {
        $order->load(['cliente']);
        $clients = Client::all()->map(function ($client) {
            return ['value' => $client->id, 'label' => $client->cliente ];
        });

        $data = [
            'lastOrder' => Order::getOrderWithLastFolio(),
            'order' => $order,
            'clients' => $clients
        ];
        $data['aguas'] = $data['lastOrder']->aguas_alimentos === 'Aguas'; 
        return Inertia::render('orders/Edit', $data);
    }

    public function update (Request $request, Order $order)
    {
        $validatedOrder = $request->validate([
            'id_cliente' => 'required|exists:clientes,id',
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

        foreach ($validatedOrder as $property => $value) {
            $order->{$property} = $value;
        }

        $order->save();

        return redirect()
            ->route("orders.show", [$order->id])
            ->with('message', 'Se ha editado la orden  correctamente.');
    }

    public function editPartialInfo (Request $request, Order $order)
    {

        $order->numero_cotizacion = $request->input('numero_cotizacion');
        $order->numero_termometro = $request->input('numero_termometro');
        $order->temperatura = $request->input('temperatura');

        $order->save();

        return redirect()
            ->route('orders.show', $order->id)
            ->with('message', "Se han editado correctamente los datos de la orden MFQ-$order->folio");
    }

    public function delete (Order $order)
    {        
        $folio = $order->folio;
        $order->delete();
        return redirect()
            ->route('orders.index')
            ->with('message', "Se eliminado la orden $folio correctamente");
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

    public function generatePDF(Order $order)
    {
        $folio = 'MFQ-' . $order->folio;
        $cliente = $order->cliente;
        if ($order->aguas_alimentos === 'Aguas') {
            $muestras = $order->muestras_aguas;
        } else {
            $muestras = $order->muestras_alimentos;
        }
        // Create a new Mpdf instance
        $mpdf = new Mpdf([
            'mode' => 'utf-8',
            'format' => 'Letter'
        ]);

        // Set the document title
        $mpdf->SetTitle($folio . ' ' . $cliente->cliente);
        $urlLogo = public_path('img/logo.png');
        // Render the header using Blade template
        $header = View::make('pdf.order.header', ['urlLogo' => $urlLogo])->render();
        $mpdf->SetHTMLHeader($header);

        // Set footer
        $mpdf->SetFooter('Pag. <span style="font-weight:normal">{PAGENO} de {nb}</span>' . " Folio: <span style='font-weight:normal'>$folio</span>");

        // Get the stylesheet content (optional if you want to style the PDF using CSS)
        $stylesheetUrl = public_path('css/pdf/orden.css');
        $stylesheet = file_get_contents($stylesheetUrl);  // Load the CSS from public folder
        $mpdf->WriteHTML($stylesheet, \Mpdf\HTMLParserMode::HEADER_CSS);
        // Render the body content using Blade template
        $html = View::make('pdf.order.order', [
            'order' => $order,
            'cliente' => $cliente
        ])->render();

        $mpdf->WriteHTML($html);
        $index = 1;
        foreach($muestras as $muestra) {
            $order->hora_recepcion = horaValida($order->hora_recepcion);
            $cliente = $order->cliente;
            if ($order->aguas_alimentos === 'Aguas') {
                $muestra->identificacion_muestra = $muestra->identificacionMuestraRelacion;
                if ($muestra->hora_final_muestreo) {
                    $muestra->hora_final_muestreo = horaValida($muestra->hora_final_muestreo);
                }
                if ($muestra->hora_composicion) {
                    $muestra->hora_composicion = horaValida($muestra->hora_composicion);
                }
            }
            $html = View::make('pdf.order.muestras', [
                'muestra' => $muestra,
                'order' => $order
            ])->render();
            
            if ($index < count($muestras)) {
                $html .= '<hr>';
            }
            if ($mpdf->_getHtmlHeight($html) > ($mpdf->h - $mpdf->y - $mpdf->bMargin - $mpdf->tMargin)) {
                $mpdf->AddPage();
                $html = "<br>$html";
            }
            if (isset($muestra->cloro)) {
                switch($muestra->cloro) {
                    case 'si':
                        $muestra->cloro = 'Presente';
                    break;
                    case 'no':
                        $muestra->cloro = 'Ausente';
                    break;
                    case 'laboratorio':
                        $muestra->cloro = 'Laboratorio';
                    break;
                    default: 
                        $muestra->cloro = 'N/A';
                    break;
                }
                  
              }
            $mpdf->WriteHTML($html);
            $index++;
        }

        if ($order->aguas === 'Aguas') {
            $html = View::make('pdf.order.table', [
                'order' => $order
            ])->render();
        } else {
            $html = View::make('pdf.order.paragraph', [
                'order' => $order
            ])->render();
        }

        if ($mpdf->_getHtmlHeight($html) > ($mpdf->h - $mpdf->y - $mpdf->bMargin - $mpdf->tMargin)) {
            $mpdf->AddPage();
            $html = "<br>$html";
        }
        $mpdf->WriteHTML($html);
        $contieneFirmaLupita = $order->fecha_recepcion > '2023-11-01' && $order->folio > 13932;
        $html = View::make('pdf.order.signs', [
            'order' => $order,
            'contieneFirmaLupita' => $contieneFirmaLupita
        ])->render();

        $mpdf->WriteHTML($html);
        // Output the PDF
        $mpdf->Output($folio . '.pdf', 'I');
        exit();
    }

    public function headerOrderPDF ()
    {
        return view('pdf.order.header');
    }
}
