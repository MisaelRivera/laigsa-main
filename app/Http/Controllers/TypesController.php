<?php

namespace App\Http\Controllers;

use App\Models\Type;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TypesController extends Controller
{
    public function index ()
    {
        return Inertia::render('types/Index', [
            'types' => Type::paginate(10)
        ]);
    }

    public function create ()
    {
        return Inertia::render('types/Create');
    }

    public function store (Request $request)
    {
        $validatedData = $request->validate([
            'nombre' => 'required'
        ], [
            'nombre.required' => 'Ingrese el tipo'
        ]);

        $createdType = Type::create($validatedData);

        return redirect()
            ->route('types.index')
            ->with('message', "El tipo $createdType->nombre se ha creado correctamente");
    }

    public function edit (Type $type)
    {
        return Inertia::render('types/Edit', [
            'type' => $type
        ]);
    }

    public function update (Request $request, Type $type)
    {
        $validatedData = $request->validate([
            'nombre' => 'required'
        ], [
            'nombre.required' => 'Ingrese el tipo'
        ]);

        $type->nombre = $validatedData['nombre'];
        $type->save();

        return redirect()
            ->route('types.index')
            ->with('message', "El tipo $type->nombre se ha editado correctamente");
    }
}
