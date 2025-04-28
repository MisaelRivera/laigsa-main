<?php
    namespace App\Filters\Order;

    use App\Filters\Filter;
    
    class FolioFilter implements Filter
    {
        public function apply($query, $value)
        {
            return $query->where('folio', 'like', '%' . urldecode($value) . '%');
        }
    }