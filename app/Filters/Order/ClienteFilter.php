<?php
    namespace App\Filters\Order;

    use App\Filters\Filter;
    
    class ClienteFilter implements Filter
    {
        public function apply($query, $value)
        {
            return $query->whereHas('cliente', function($query) use ($value) {
                $query->where('cliente', 'like', '%' . urldecode($value) . '%');
            });
        }
    }