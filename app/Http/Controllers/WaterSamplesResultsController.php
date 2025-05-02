<?php

namespace App\Http\Controllers;
use App\Api\OrdersApi;
use App\Filters\Order\OrderFiltersResolver;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class WaterSamplesResultsController extends Controller
{
    public function index (Request $request)
    {
        $orders = (new OrderFiltersResolver($request))
        ->apply(Order::query())
        ->where('v_libreta_resultados', 1)
        ->paginate(40);
        $filters = $request->all();
        foreach ($orders as $order) {
            if ($order->aguas_alimentos === 'Aguas') {
                $order->muestras = $order->muestras_aguas;
                $order->muestras_count = count($order->muestras_aguas);
            } else {
                $order->muestras = $order->muestras_alimentos;
                $order->muestras_count = count($order->muestras_alimentos);
            }
        }
        return Inertia::render('results/WaterIndex', [
            'ordersProp' => $orders,
            'filters' => $filters
        ]);
    }

    public function insert (Order $order)
    {
        $order->load(['muestras_aguas.resultados_aguas']);
        $user = Auth::user();
       
        return Inertia::render('results/WaterInsert', [
            'order' => $order,
            'user' => $user
        ]);
    }
}
