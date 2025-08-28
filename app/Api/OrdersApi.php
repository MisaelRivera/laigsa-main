<?php
    namespace App\Api;

use App\Filters\Order\OrderFiltersResolver;
use Illuminate\Http\Request;
    use App\Models\Order;
    use App\Models\WaterSample;
    use App\Models\FoodSample;

    class OrdersApi
    {
        public static function getIndexOrders (Request $request, $filters)
        {
            $orders = (new OrderFiltersResolver($request))
                ->apply(Order::query())
                ->paginate(40)
                ->appends($filters);
            foreach ($orders as $order) {
                if ($order->aguas_alimentos === 'Aguas') {
                    $order->muestras = $order->muestras_aguas;
                    $order->muestras_count = count($order->muestras_aguas);
                } else {
                    $order->muestras = $order->muestras_alimentos;
                    $order->muestras_count = count($order->muestras_alimentos);
                }
            }
            return $orders;
        }
    }