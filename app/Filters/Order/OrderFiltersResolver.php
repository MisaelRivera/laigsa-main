<?php
    namespace App\Filters\Order;

    use Illuminate\Database\Eloquent\Builder;
    use Illuminate\Http\Request;
    
    class OrderFiltersResolver
    {
        protected $filters = [
            'folio' => FolioFilter::class,
            'cesavedac' => CesavedacFilter::class,
            'cliente' => ClienteFilter::class,
            'muestreador' => MuestreadorFilter::class,
            'siralab' => SiralabFilter::class,
            'supervision' => SupervisionFilter::class,
        ];
    
        protected $request;
    
        public function __construct(Request $request)
        {
            $this->request = $request;
        }
    
        public function apply(Builder $query)
        {
            $query->with([
                'muestras_aguas.identificacionMuestraRelacion',  // Eager load water samples and their identification
                'muestras_alimentos', // Eager load food samples
                'cliente',
                'siralab'
            ])->select('ordenes.*')
                ->orderBy('fecha_recepcion', 'desc')
                ->orderBy('hora_recepcion', 'desc')
                ->orderBy('folio', 'desc');
            foreach ($this->filters as $key => $filterClass) {
                if ($this->request->filled($key)) {
                    (new $filterClass())->apply($query, $this->request->input($key));
                }
            }
    
            return $query;
        }
    }