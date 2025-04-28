<?php
    namespace App\Filters\Order;

    use App\Filters\Filter;
    
    class MuestreadorFilter implements Filter
    {
        public function apply($query, $value)
        {
            return $query->where(function ($query) use ($value) {
                $query->whereHas('muestras_aguas', function ($query) use ($value) {
                    $query->where('muestreador', 'like', '%' . urldecode($value) . '%');
                })->orWhereHas('muestras_alimentos', function ($query) use ($value) {
                    $query->where('muestreador', 'like', '%' . urldecode($value) . '%');
                });
            });
        }
    }
    