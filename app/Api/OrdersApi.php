<?php
    namespace App\Api;
    use App\Models\Order;
    use App\Models\WaterSample;
    use App\Models\FoodSample;

    class OrdersApi
    {
        public static function getIndexOrders ($filters, $results = false)
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
                ->when($filters['cesavedac'] ?? false, fn ($query, $filter) => $query->where('cesavedac', urldecode($filter) === "true" ? 1:0))
                ->when($filters['cliente'] ?? false, fn ($query, $filter) => $query->whereHas('cliente', function($query) use ($filter) {
                    // Apply filter on the 'clientes' table's 'cliente' column
                    $query->where('cliente', 'like', '%' . urldecode($filter) . '%');
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
                })->when($filters['siralab'] ?? false, function ($query, $filter) {
                    $query->where(function ($query) use ($filter) {
                        $query->whereHas('muestras_aguas', function ($query) use ($filter) {
                            $query->whereHas('identificacionMuestraRelacion', function ($query) use ($filter) {
                                if ($filter === 'true') {
                                    $query->where('siralab', 1);
                                } else {
                                    $query->where('siralab', 0);
                                }
                            });
                        });
                    });
                })->when($filters['supervision'] ?? false, function ($query, $filter) {
                    $query->where(function ($query) use ($filter) {
                        $query->whereHas('muestras_aguas', function ($query) use ($filter) {
                            if ($filter === 'true') {
                                $query->whereNotIn('muestreador', [
                                    'Irving (CESAVEDAC)', 'Pedro (CESAVEDAC)', 'Crisanta (CESAVEDAC)', 'Julio (CESAVEDAC)', 'Miguel (CESAVEDAC)', 'Lizeth (CESAVEDAC)', 'Cliente'
                                ]);
                            } else {
                                $query->whereIn('muestreador', [
                                    'Irving (CESAVEDAC)', 'Pedro (CESAVEDAC)', 'Crisanta (CESAVEDAC)', 'Julio (CESAVEDAC)', 'Miguel (CESAVEDAC)', 'Lizeth (CESAVEDAC)', 'Cliente'
                                ]);  
                            }
                        })->orWhereHas('muestras_alimentos', function ($query) use ($filter) {
                            if ($filter === 'true') {
                                $query->whereNotIn('muestreador', [
                                    'Irving (CESAVEDAC)', 'Pedro (CESAVEDAC)', 'Crisanta (CESAVEDAC)', 'Julio (CESAVEDAC)', 'Miguel (CESAVEDAC)', 'Lizeth (CESAVEDAC)', 'Cliente'
                                ]);
                            } else {
                                $query->whereIn('muestreador', [
                                    'Irving (CESAVEDAC)', 'Pedro (CESAVEDAC)', 'Crisanta (CESAVEDAC)', 'Julio (CESAVEDAC)', 'Miguel (CESAVEDAC)', 'Lizeth (CESAVEDAC)', 'Cliente'
                                ]);  
                            }
                        });
                    });
                })->when($results, function ($query) {
                    $query->where('v_libreta_resultados', 1);
                })
                ->paginate(40)
                ->withQueryString();
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

        public static function adjustOrderSamplesNumber ($orderId) {
            $order = Order::find($orderId);
            $existentSamples = $order->muestras_aguas->count();
            $expectedSamples = $order->numero_muestras;
            $diference = $existentSamples - $expectedSamples;
            if ($diference !== 0) {
                $order->numero_muestras = $existentSamples;
                $order->save();
            }
        }
    }