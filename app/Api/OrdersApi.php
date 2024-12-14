<?php
    namespace App\Api;
    use App\Models\Order;
    use App\Models\WaterSample;
    use App\Models\FoodSample;

    class OrdersApi
    {
        public static function getIndexOrders ($filters)
        {
            $orders = Order::leftJoin('siralab', 'ordenes.id', '=', 'siralab.id_orden')
            ->join('clientes', 'ordenes.id_cliente', '=', 'clientes.id')
            ->select('ordenes.*', 'siralab.id as siralab_id', 'siralab.hoja_campo', 'siralab.cadena_custodia', 'siralab.croquis', 'clientes.cliente')
            ->orderBy('fecha_recepcion', 'desc')
            ->orderBy('hora_recepcion', 'desc')
            ->orderBy('folio', 'desc')
            ->when(
                $filters['folio'] ?? false, 
                fn ($query, $filter) => $query->where('folio', 'like', '%' . urldecode($filter) . '%')
            )->when(
                $filters['cliente'] ?? false, 
                fn ($query, $filter) => $query->where('clientes.cliente', 'like', '%' . urldecode($filter) . '%')
            )
            ->paginate(40)
            ->withQueryString();

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
            if(isset($filters['muestreador'])) {
                $userInput = $filters['muestreador'];
                $orders = $orders->filter(function ($order) use ($userInput) {
                    if (count($order->muestras) > 0) {
                        return $order->muestras->contains(function ($muestra) use ($userInput) {
                            return stripos($muestra->muestreador, $userInput) !== false; 
                        });
                    }
                    return false;
                });
            }
            var_dump($orders);
            die();
            return $orders;
        }
    }