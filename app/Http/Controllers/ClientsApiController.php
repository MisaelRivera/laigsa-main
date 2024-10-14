<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Client;

class ClientsApiController extends Controller
{
    public function getClientById (Client $client)
    {
        return response()->json($client);
    }
}
