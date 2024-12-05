<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMethodRequest;
use App\Models\Method;
use Illuminate\Http\Request;
use Inertia\Inertia;

class MethodsController extends Controller
{
    //
    public function index (Request $request)
    {
        $filters = $request->only('byMethod');
        $methods = Method::orderBy('id_metodo', 'desc')
        ->where('obsoleto', 0)
        ->when(
                $filters['byMethod'] ?? false, 
                fn ($query, $filter) => $query->where('nombre', 'like', '%' . urldecode($filter) . '%')
            )->paginate(10)
            ->withQueryString();
        if (isset($filters['byMethod'])) {
            $filters['byMethod'] = urldecode($filters['byMethod']);
        }

        $data = [
            'methods' => $methods,
            'filters' => $filters
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

    public function show (Method $method)
    {
        return Inertia::render('methods/Show', [
            'method' => $method,
            'backUrl' => url()->previous()
        ]);
    }

    public function edit (Method $method)
    {
        return Inertia::render('methods/Edit', [
            'method' => $method,
            'backUrl' => url()->previous()
        ]);
    }

    public function update (Request $request, Method $method)
    {
        $newMethod = $request->validate([
            'nombre' => 'required|string|min:8',
        ], [
            'nombre.required' => 'Ingrese el nombre',
            'nombre.string' => 'Ingrese una cadena de texto',
            'nombre.min' => 'El nombre debe tener al menos 8 caracterers'
        ]);
        $newMethod = Method::create($newMethod);
        $method->obsoleto = 1;
        $method->save();
        $name = $newMethod->nombre;
        return redirect()
            ->route('methods.index')
            ->with('message', "Se ha editado correctamente el metodo $name");
    }

    public function destroy (Method $method)
    {
        $name = $method->nombre;
        $method->forceDelete();
        return redirect()
            ->route('methods.index')
            ->with('message', "Se ha eliminado el metodo $name");
    }
}
