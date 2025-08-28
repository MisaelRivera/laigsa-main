<?php 
    namespace App\Api;
    use App\Models\Client;

    class ClientsApi
    {
        public static function getIndexClients ($filters)
        {
            $clients = Client::orderBy('cliente')
                ->when(isset($filters['cliente']), function ($query) use ($filters) {
                    $query->where('cliente', 'like', "%" . urldecode($filters['cliente']) . "%");
                })
                ->when(isset($filters['direccion_muestreo']), function ($query) use ($filters) {
                    $query->where('direccion_muestreo', 'like', "%" . urldecode($filters['direccion_muestreo']) . "%");
                })
                ->paginate(40)
                ->withQueryString();
            
            return $clients;
        }

        public static function getClientsByName ($filteringName)
        {
            $clients =  Client::where('cliente', 'like', "%" . $filteringName ."%")
            ->limit(10)->get();
            $clients = $clients->map(function ($client) {
                return [
                    'label' => $client->cliente,
                    'value' => $client->cliente,
                ];
            });
            return $clients;
        }
    }