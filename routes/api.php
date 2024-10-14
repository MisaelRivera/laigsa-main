<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClientsApiController;

Route::get('/clients/{client}', [ClientsApiController::class, 'getClientById']);
