<?php

namespace App\Http\Controllers;
use App\Api\OrdersApi;
use App\Models\Order;
use Illuminate\Http\Request;
use Inertia\Inertia;

class WaterSamplesResultsController extends Controller
{
    public function index (Request $request)
    {
        $orders = OrdersApi::getIndexOrders($request->all(), true);
        $filters = $request->all();
        return Inertia::render('results/WaterIndex', [
            'ordersProp' => $orders,
            'filtersProp' => $filters
        ]);
    }

    public function insert (Order $order)
    {
        $order->load('muestras_aguas.resultados_aguas');
        //return Inertia::render('');
    }
}
