<?php
    namespace App\Filters\Order;

    use App\Filters\Filter;
    
    class SiralabFilter implements Filter
    {
        public function apply($query, $value)
        {
            return $query->where(function ($query) use ($value) {
                $query->whereHas('muestras_aguas', function ($query) use ($value) {
                    $query->whereHas('identificacionMuestraRelacion', function ($query) use ($value) {
                        $query->where('siralab', urldecode($value) === 'true' ? 1 : 0);
                    });
                });
            });
        }
    }