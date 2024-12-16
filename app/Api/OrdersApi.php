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
            ->leftJoin('muestras_aguas', 'ordenes.id', '=', 'muestras_aguas.id_orden')
            ->leftJoin('muestras_alimentos', 'ordenes.id', '=', 'muestras_alimentos.id_orden')
            ->leftJoin('identificacion_muestras', 'muestras_aguas.id_identificacion_muestra', '=', 'identificacion_muestras.id')
            ->select('ordenes.*', 'siralab.id as siralab_id', 'siralab.hoja_campo', 'siralab.cadena_custodia', 'siralab.croquis', 'clientes.cliente', 'identificacion_muestras.identificacion_muestra')
            ->orderBy('fecha_recepcion', 'desc')
            ->orderBy('hora_recepcion', 'desc')
            ->orderBy('folio', 'desc')
            ->when($filters['folio'] ?? false, fn ($query, $filter) => $query->where('folio', 'like', '%' . urldecode($filter) . '%'))
            ->when($filters['cliente'] ?? false, fn ($query, $filter) => $query->where('clientes.cliente', 'like', '%' . urldecode($filter) . '%'))
            ->when($filters['muestreador'] ?? false, function ($query, $filter) {
                $query->where(function ($query) use ($filter) {
                    // For water_samples, filter by sampler
                    $query->whereNotNull('muestras_aguas.muestreador')
                          ->where('muestras_aguas.muestreador', 'like', '%' . urldecode($filter) . '%')
                          ->orWhere(function ($query) use ($filter) {
                              // For food_samples, filter by sampler
                              $query->whereNotNull('muestras_alimentos.muestreador')
                                    ->where('muestras_alimentos.muestreador', 'like', '%' . urldecode($filter) . '%');
                          });
                });
            })
            ->paginate(40)
            ->withQueryString();
            foreach ($orders as $order) {
                if ($order->aguas_alimentos === 'Aguas') {
                    $order->muestras = $order->muestras_aguas;
                } else {
                    $order->muestras = $order->muestras_alimentos;
                }
            }
            return $orders;
        }
    }