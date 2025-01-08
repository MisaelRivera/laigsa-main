<?php 
    namespace App\Api;
    use App\Models\Client;

    class ClientsApi
    {
        public static function getIndexClients ($filters)
        {
            $clients = Client::orderBy('cliente')
                ->when(isset($filters['cliente']) ?? false, function ($query, $filter) {
                    $query->where('cliente', 'like', "%$filter%");
                })
                ->when(isset($filters['direccion_muestreo']) ?? false, function ($query, $filter) {
                    $query->where('direccion_muestreo', 'like', "%$filter%");
                })
                ->paginate(40)
                ->withQueryString();

            return $clients;
        }
    }