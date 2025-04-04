<?php

namespace App\Http\Controllers;

use App\Models\ParamDescription;
use Illuminate\Contracts\Support\ValidatedData;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ParamsDescriptionController extends Controller
{
    public function index ()
    {
        return Inertia::render('params_description/Index', [
            'paramsDescription' => ParamDescription::paginate(10)
        ]);
    }

    public function create ()
    {
        return Inertia::render('params_description/Create');
    }

    public function store (Request $request)
    {
        $validatedData = $request->validate(
            [
                'descripcion' => 'required',
            ],
            [
                'descripcion.required' => 'Ingrese la descripcion',
            ]
        );

        $paramDescription = ParamDescription::create($validatedData);
        return redirect()
            ->route('params_description.index')
            ->with('message', "La descripcion de parametros $paramDescription->descripcion se ha eliminado correctamente");
    }

    public function edit (ParamDescription $paramDescription)
    {

    }

    public function update (ParamDescription $paramDescription)
    {

    }

    public function destroy (ParamDescription $paramDescription)
    {

    }
}
