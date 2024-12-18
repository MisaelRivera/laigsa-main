<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Client;
use Inertia\Inertia;

class ClientsController extends Controller
{
    public function index (Request $request)
    {
       
        $data = [
            'clients' => Client::orderBy('cliente')
                ->paginate(10)
            ];
        return Inertia::render('clients/Index', $data);
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
}
