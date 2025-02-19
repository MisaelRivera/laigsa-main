<?php
    namespace App\Api;
    use App\Models\Order;
    use App\Models\WaterSample;
    use App\Models\FoodSample;

    class OrdersApi
    {
        public static function getIndexOrders ($filters)
        {
            $orders = Order::with([
                'muestras_aguas.identificacionMuestraRelacion',  // Eager load water samples and their identification
                'muestras_alimentos', // Eager load food samples
                'cliente',
                'siralab'
            ])->select('ordenes.*')
                ->orderBy('fecha_recepcion', 'desc')
                ->orderBy('hora_recepcion', 'desc')
                ->orderBy('folio', 'desc')
                ->when($filters['folio'] ?? false, fn ($query, $filter) => $query->where('folio', 'like', '%' . urldecode($filter) . '%'))
                ->when($filters['cesavedac'] ?? false, fn ($query, $filter) => $query->where('cesavedac', 'like', '%' . (urldecode($filter) ? 1:0) . '%'))
                ->when($filters['cliente'] ?? false, fn ($query, $filter) => $query->whereHas('cliente', function($query) use ($filter) {
                    // Apply filter on the 'clientes' table's 'cliente' column
                    $query->where('clientes.cliente', 'like', '%' . urldecode($filter) . '%');
                }))
                ->when($filters['muestreador'] ?? false, function ($query, $filter) {
                    $query->where(function ($query) use ($filter) {
                        // Filter by muestreador for both water samples and food samples
                        $query->whereHas('muestras_aguas', function ($query) use ($filter) {
                            $query->where('muestreador', 'like', '%' . urldecode($filter) . '%');
                        })
                        ->orWhereHas('muestras_alimentos', function ($query) use ($filter) {
                            $query->where('muestreador', 'like', '%' . urldecode($filter) . '%');
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