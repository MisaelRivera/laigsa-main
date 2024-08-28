<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Unit;
use App\Models\Method;
use App\Models\Parameter;
use App\Models\LCP;
use App\Models\ParameterCombination;

class ParameterCombinationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $filters = $request->only('byAlias');
        $parametersCombinations = ParameterCombination::orderByDesc('id')
            ->when(
            $filters['byAlias'] ?? false, 
            fn ($query, $filter) => $query->where('alias', 'like', '%' . $filter . '%')
        )->paginate(10)
        ->withQueryString();

        return Inertia::render('parameters_combinations/Index', [
            'parametersCombinations' => $parametersCombinations,
            'filters' => $filters,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('parameters_combinations/Create', [
            'parameters' => Parameter::with(['lcps'])
                ->get()
                ->map(function ($item) {
                return [
                    'label' => $item->parametro,
                    'value' => $item->parametro,
                    'key' => $item->id,
                    'lcps' => $item->lcps
                ];
            }),
            'methods' => Method::where('obsoleto', 0)->get()->map(function ($item) {
                return [
                    'label' => $item->nombre,
                    'value' => $item->nombre,
                    'key' => $item->id
                ];
            }),
            'units' => Unit::where('obsoleto', 0)->get()->map(function ($item) {
                return [
                    'label' => $item->nombre,
                    'value' => $item->nombre,
                    'key' => $item->id
                ];
            }),
        ]);
    }

    public function getLCPs ($parameter)
    {
        $parameter = Parameter::where('parametro', $parameter)
            ->firstOrFail();
        return response()->json(LCP::where('id_parametro', $parameter->id)
            ->get()
            ->map(function ($item) {
                return [
                    'value' => $item->valor,
                    'label' => $item->valor,
                    'key' => $item->id,
                ];
            })
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'parametro' => 'required|exists:parametros,parametro',
            'unidad' => 'required|exists:unidades,nombre',
            'metodo' => 'required|exists:metodos,nombre',
            'lcp' => 'required|exists:lcps,valor',
            'clasificacion' => 'required',
            'alias' => 'required',
        ], [
            'parametro.required' => 'Ingrese el parametro',
            'parametro.exists' => 'El parametro ingresado no existe',
            'unidad.required' => 'Ingrese la unidad',
            'unidad.exists' => 'La unidad ingresada no existe',
            'metodo.required' => 'Ingrese el metodo',
            'metodo.exists' => 'El metodo ingresado no existe',
            'lcp.required' => 'Elija el lcp',
            'lcp.exists' => 'El lcp ingresado no existe',
            'clasificacion.required' => 'Elija la clasificacion',
            'alias.required' => 'Ingrese el alias',
        ]);

        
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
