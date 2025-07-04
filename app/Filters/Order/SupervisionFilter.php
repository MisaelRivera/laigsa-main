<?php
    namespace App\Filters\Order;

    use App\Filters\Filter;
    
    class SupervisionFilter implements Filter
    {
        protected $cesavedacMuestreadores = [
            'Irving', 'Pedro', 'Crisanta',
            'Julio', 'Miguel', 'Lizeth', 'Cliente'
        ];
    
        public function apply($query, $value)
        {
            return $query->where(function ($query) use ($value) {
                $query->whereHas('muestras_aguas', function ($query) use ($value) {
                    $this->applySupervisionCondition($query, $value);
                })->orWhereHas('muestras_alimentos', function ($query) use ($value) {
                    $this->applySupervisionCondition($query, $value);
                });
            });
        }
    
        protected function applySupervisionCondition($query, $value)
        {
            if ((int) $value) {
                $query->whereNotIn('muestreador', $this->cesavedacMuestreadores);
            } else {
                $query->whereIn('muestreador', $this->cesavedacMuestreadores);
            }
        }
    }