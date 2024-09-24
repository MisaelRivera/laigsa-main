<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Order;
use App\Models\Rule;

class SamplesController extends Controller
{
    public function createWater ($folio, $numero_muestras, $inicio_muestras)
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

    

    public function destroy ($id)
    {

    }
}
