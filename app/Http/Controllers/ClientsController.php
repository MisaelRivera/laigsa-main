<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Client;
use App\Api\ClientsApi;
use App\Http\Requests\SampleIdentificationEditRequest;
use Inertia\Inertia;
use App\Http\Requests\SampleIdentificationStoreRequest;
use App\Models\SampleIdentification;

class ClientsController extends Controller
{
    public function index (Request $request)
    {
       $filters = $request->all();
        $data = [
            'clients' => ClientsApi::getIndexClients($filters)
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

    public function show (Client $client)
    {
        $client->identificacionesMuestra = $client->identificaciones_muestra_activas;
        $client->identificaciones_muestra_obsoletas;
        return Inertia::render('clients/Show', [
            'client' => $client
        ]);
    }

    public function repeated ()
    {
        $clientes = Client::orderBy('id')->get();

        $posiblesDuplicados = [];

        foreach ($clientes as $a) {
            foreach ($clientes as $b) {
                if ($a->id >= $b->id) continue;

                $similaridadNombre = 0;
                similar_text($a->cliente, $b->cliente, $similaridadNombre);


                if ($similaridadNombre > 80) {
                    $posiblesDuplicados[] = [
                        'cliente_a_id' => $a->id,
                        'cliente_b_id' => $b->id,
                        'nombre_a' => $a->cliente,
                        'nombre_b' => $b->cliente,
                        'similitud_nombre' => round($similaridadNombre),
                    ];
                }
            }
        }

        dd($posiblesDuplicados);
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

    public function createSampleIdentificacion (SampleIdentificationStoreRequest $request)
    {
        $clientID = $request->input('id_cliente');
        $validatedData = $request->validated();
        if ($request->has('latitud_grados')) {
            $validatedData['latitud'] = implodingCoordinates(
                $validatedData['latitud_grados'],
                $validatedData['latitud_minutos'],
                $validatedData['latitud_segundos'],
                $validatedData['latitud_orientacion'],
            );
            $validatedData['longitud'] = implodingCoordinates(
                $validatedData['longitud_grados'],
                $validatedData['longitud_minutos'],
                $validatedData['longitud_segundos'],
                $validatedData['longitud_orientacion'],
            );
            $validatedData = unsettingCoordinates($validatedData);
        }
        
        SampleIdentification::create($validatedData);
        return redirect()
            ->route('clients.show', ['client' => $clientID])
            ->with('message', "Se ha creado la identificacion de muestra " . $request->input('identificacion_muestra') . " correctamente!");
    }

    public function editSampleIdentification (SampleIdentification $sampleIdentification, SampleIdentificationEditRequest $request)
    {
        $validatedData = $request->validated();
        $sampleIdentification->obsoleta = $validatedData['obsoleta'];
        if ($request->has('latitud_grados')) {
            $sampleIdentification->latitud = implodingCoordinates(
                $validatedData['latitud_grados'],
                $validatedData['latitud_minutos'],
                $validatedData['latitud_segundos'],
                $validatedData['latitud_orientacion']
            );
            $sampleIdentification->longitud = implodingCoordinates(
                $validatedData['longitud_grados'],
                $validatedData['longitud_minutos'],
                $validatedData['longitud_segundos'],
                $validatedData['longitud_orientacion']
            );
           
        } else {
            $sampleIdentification->latitud = 'Sin Dato';
            $sampleIdentification->longitud = 'Sin Dato';
        }
        $sampleIdentification->save();
        return redirect()
            ->route('clients.show', ['client' => $sampleIdentification->id_cliente]);
    }

    public function restoreSampleIdentification (SampleIdentification $sampleIdentification)
    {
        $sampleIdentification->obsoleta = 0;
        $sampleIdentification->save();
        $name = $sampleIdentification->identificacion_muestra;
        return redirect()
            ->route('clients.show', $sampleIdentification->id_cliente);
    }

    public function filterSampleIdentification (Request $request, $idCliente)
    {
        $sampleIdentifications = SampleIdentification::where('id_cliente',
         $idCliente)
            ->where('identificacion_muestra', 'like', "%" . $request->query('val') . "%")
            ->where('obsoleta', 0)
            ->get();
        return response()
            ->json($sampleIdentifications);

    }

    public function setSampleIdentificationObsolete (SampleIdentification $sampleIdentification)
    {
        $sampleIdentification->obsoleta = 1;
        $sampleIdentification->save();
        return redirect()
            ->route('clients.show', ['client' => $sampleIdentification->id_cliente]);
    }

    public function getSampleIdentificationsByClientId ($clientId) 
    {
        $sampleIdentifications = SampleIdentification::where('id_cliente', $clientId)
            ->get();
        return response()
            ->json($sampleIdentifications);
    }
}
