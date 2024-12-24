<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Client;
use App\Api\ClientApi;
use Inertia\Inertia;

class ClientsController extends Controller
{
    public function index (Request $request)
    {
       $filters = $request->all();
        $data = [
            'clients' => ClientApi::getIndexClients($filters)
        ];
        return Inertia::render('clients/Index', $data);
    }

    public function create()
    {
        return Inertia::render('clients/Create');
    }

    public function store (Request $request)
    {
       $client =  $request->validate([
            'cliente' => 'required|unique:clientes,cliente,except,id',
            'direccion_muestreo' => 'nullable',
            'direccion_fiscal' => 'nullable',
            'observaciones' => 'nullable',
            'observaciones_analistas' => 'nullable',
            'encargado' => 'nullable',
            'telefono' => 'nullable',
            'correo_electronico' => 'nullable',
            'cuarto_transitorio' => 'boolean',
        ], [
            'cliente.required' => 'Ingrese el cliente',
            'cliente.unique' => 'El cliente ingresado ya existe'
        ]);

        $newClient = Client::create($client);
        return redirect()
            ->route('clients.index')
            ->with('message', "Se ha creado el cliente $newClient->cliente correctamente!");
    }

    public function clientsByName ()
    {
        try {
        $filterName = request()->query('name');
        $clients = Client::where('cliente', 'like', "%" . $filterName ."%")
            ->limit(10)->get();
        $clients = $clients->map(function ($client) {
            return [
                'label' => $client->cliente,
                'value' => $client->cliente,
            ];
        });
        return response()->json($clients);
     } catch (\Exception $e) {
            Log::error('Error in clientsByName: ' . $e->getMessage());
            return response()->json(['error' => 'Internal Server Error'], 500);
        }
    }

    public function setCesavedac (Client $client, $cesavedac)
    {
        $client->cesavedac = filter_var($cesavedac, FILTER_VALIDATE_BOOLEAN);;
        $client->save();
        return response(200);
    }
}
