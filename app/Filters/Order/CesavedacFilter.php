<?php

namespace App\Filters\Order;

use App\Filters\Filter;

class CesavedacFilter implements Filter
{
    public function apply($query, $value)
    {
        return $query->where('cesavedac', (int)$value);
    }
}