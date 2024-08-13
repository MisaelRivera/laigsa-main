<?php

namespace App\Http\Controllers;

use App\Models\Method;
use Illuminate\Http\Request;
use Inertia\Inertia;

class MethodsController extends Controller
{
    //
    public function index ()
    {
        $methods = Method::orderBy('id_metodo', 'desc')->limit(40)
            ->get();
        $data = [
            'methodsProp' => $methods,
            'totalItemsProp' => Method::count()
        ];


        return Inertia::render('methods/Index', $data);
    }

    public function create ()
    {
        return Inertia::render('methods/Create');
    }

    public function changePage (Request $request)
    {
        $page = $request->query('page');
        $methods = Method::offset($page - 1)
            ->limit(40)
            ->get();
        return response()->json($methods);
    }

    public function store (Request $request)
    {
        $method = $request->validate([
            'nombre' => 'required|unique:metodos,nombre'
        ],
        [
            'required' => 'Ingrese el nombre del metodo',
            'unique' => 'El nombre que ingreso ya existe'
        ]);

        $created_method = Method::create($method);
        $request->session()->flash('message', "Se ha creado el metodo $created_method->nombre correctamente");
        return redirect()->route('methods.index');
    }

    public function show (Request $request)
    {

    }

    public function edit ()
    {

    }

    public function update (Request $request)
    {

    }

    public function destroy (Request $request)
    {
        
    }
}
