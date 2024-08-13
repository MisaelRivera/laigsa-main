<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use Illuminate\Http\Request;
use Inertia\Inertia;

class UnitsController extends Controller
{
    //
    public function index ()
    {
        $units = Unit::orderBy('id', 'desc')
            ->limit(40)
            ->get();
        return Inertia::render('units/Index', ['unitsProp' => $units, 'totalItemsProp' => Unit::count()]);
    }

    public function get ()
    {
        $units = Unit::orderBy('id', 'desc')
            ->paginate(40);
        return response()->json($units);
    }

    public function create ()
    {
        return Inertia::render('units/Create');
    }

    public function store (Request $request)
    {
        $unit = $request->validate([
            'nombre' => 'required'
        ],
        [
            'nombre' => 'Por favor ingresa el nombre de la unidad'
        ]);
       
        Unit::create($unit);
        $request->session()->flash('message', 'Se creo una nueva unidad correctamente!');
        return redirect('/units');
    }

    public function show (Request $request)
    {
        return Inertia::render('units/Create');
    }

    public function edit ($id)
    {
        $unit = Unit::find($id);
        return Inertia::render('units/Edit', ['unit' => $unit]);
    }

    public function update (Request $request, $id)
    {
        $request->validate([
            'nombre' => 'required'
        ], [
            'nombre' => 'Introduzca el nombre de la unidad'
        ]);

        $unit = Unit::find($id);
        $unit->nombre = $request->input('nombre');
        $unit->save();
        return redirect('/units')->with('message', 'Se ha actualizado la unidad ' . $unit->nombre . ' correctamente');
    }

    public function destroy (Request $request, $id)
    {
        $request->validate([
            'id' => 'exists:unidades,id'
        ]);

        $unit = Unit::find($id);
        $unitName = $unit->nombre;
        $unit->delete();
        $request->session()->flash('message', "Se ha eliminado la unidad $unitName correctamente.");
        return redirect()->route('units.index');
    }

    public function changePage (Request $request)
    {
        $page = $request->query('page');
        $units = Unit::offset(($page - 1) * 40)
            ->limit(40)
            ->get();
        return response()->json($units);
    }

    public function filter (Request $request)
    {
        $value = $request->query('value');
        $type = $request->query('type');
        $units = Unit::where($type, 'like', "%$value%")
            ->limit(40)
            ->get();
        
        return response()->json($units);
    }
}
