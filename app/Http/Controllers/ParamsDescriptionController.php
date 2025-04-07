<?php

namespace App\Http\Controllers;

use App\Models\ParamDescription;
use Illuminate\Validation\Rule;
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
                'tipo' => ['required', Rule::in(['Aguas', 'Alimentos'])],
                'cesavedac' => 'boolean'
            ],
            [
                'descripcion.required' => 'Ingrese la descripcion',
                'tipo.required' => 'Elija el tipo',
                'tipo.in' => 'El valor debe ser Aguas o Alimentos',
                'cesavedac.boolean' => 'Cesavedac debe ser un valor booleano'
            ]
        );

        $paramDescription = ParamDescription::create($validatedData);
        return redirect()
            ->route('params_description.index')
            ->with('message', "La descripcion de parametros $paramDescription->descripcion se ha creado correctamente");
    }

    public function edit (ParamDescription $paramDescription)
    {
        return Inertia::render('params_description/Edit', [
            'paramDescription' => $paramDescription
        ]);
    }

    public function update (Request $request, ParamDescription $paramDescription)
    {
        $validatedData = $request->validate(
            [
                'descripcion' => 'required',
            ],
            [
                'descripcion.required' => 'Ingrese la descripcion',
            ]
        );

        $paramDescription->descripcion = $validatedData['descripcion'];
        $paramDescription->save();
        return redirect()
            ->route('params_description.index')
            ->with('message', "La descripcion $paramDescription->descripcion se ha editado correctamente");
    }

    public function destroy (ParamDescription $paramDescription)
    {
        $description = $paramDescription->descripcion;
        $paramDescription->delete();
        return redirect()
            ->route('params_description.index')
            ->with('message', "La descripcion $description ha sido eliminada correctamente");
    }
}
