<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class VueFormController extends Controller
{
    public function test1 (Request $request)
    {
        $samplesNumber = (int)$request->query('samples_number');
        return Inertia::render('test_vue_form/Test1', [
            'samplesNumber' => $samplesNumber
        ]);
    }

    public function test1Store(Request $request) {
        /*$request->validate([
            'samples.*.tipo_muestra' => 'required',
            'samples.*.identificacion_muestra' => 'required',
            'samples.*.caracteristicas' => 'required',
        ],
        [
            'samples.*.tipo_muestra.required' => 'Ingrese el tipo de muestra'
        ]);*/
        dd($request->all());
    }
}
