<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Unit;
use App\Models\Method;
use App\Models\Parameter;
use App\Models\Rule;
use App\Models\LCP;
use App\Models\ParameterCombination;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\DB;

class ParameterCombinationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $filters = $request->only('byAlias');
        $parametersCombinations = ParameterCombination::with(['parametro', 'lcp'])
            ->where('obsoleto', 0)
            ->orderByDesc('id')
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
        $parameter = Parameter::where('parametro', $validatedData['parametro'])->firstOrFail();
        $parameter->load('lcps');
        $lcpsArr = $parameter->lcps()->pluck('valor')->toArray();
        if (!in_array($validatedData['lcp'], $lcpsArr)) {
            throw ValidationException::withMessages(['lcp' => 'El lcp ingresado no pertenece a ese parametro']);
        }
        $lcp = LCP::where('id_parametro', $parameter->id)
            ->where('valor', $validatedData['lcp'])
            ->firstOrFail();
        $method = Method::where('nombre', $validatedData['metodo'])
            ->firstOrFail();
        $unit = Unit::where('nombre', $validatedData['unidad'])
            ->firstOrFail();
        ParameterCombination::create([
            'id_parametro' => $parameter->id,
            'id_lcp' => $lcp->id,
            'id_unidad' => $unit->id,
            'id_metodo' => $method->id_metodo,
            'clasificacion' => $validatedData['clasificacion'],
            'alias' => $validatedData['alias'],
        ]);

        return redirect()
            ->route('parameters-combinations.index')
            ->with('message', 'Se ha creado una combinacion del parametro ' . $parameter->parametro . ' correctamente');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    public function addParamCombination (Request $request, Rule $rule)
    {
       $validatedData =  $request->validate([
            'alias' => 'required|exists:combinaciones_parametros,alias'
        ],
        [
            'alias.required' => 'Elija un alias',
            'alias.exists' => 'El parametro ingresado no existe'        
        ]);
        $parameterCombination = ParameterCombination::where('alias', $validatedData['alias'])->first();
        DB::table('normas_combinaciones_parametros_aguas')
            ->insert([
                'id_combinacion_parametro' => $parameterCombination->id,
                'id_norma' => $rule->id
            ]);

        return redirect()
            ->route('rules.show', ['rule' => $rule->id])
            ->with('message', 'Se ha agregado un parametro correctamente a la norma ' . $rule->norma);
    }

    public function removeParamCombination ($id)
    {
        $ruleParameterCombination = DB::table('normas_combinaciones_parametros_aguas')
            ->where('id', $id)
            ->first();
        if (!$ruleParameterCombination) {
            throw ValidationException::withMessages(['norma_combinacion' => 'La combinacion que intenta remover no existe']);
        } else {
            $rule = Rule::findOrFail($ruleParameterCombination->id);
            DB::table('normas_combinaciones_parametros_aguas')
                ->where('id', $id)
                ->delete();
            return redirect()
                ->route('rules.show', $rule->id)
                ->with('message', 'Se ha removido la combinacion  del parametro correctamente!');
        }

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $parameterCombination = ParameterCombination::findOrFail($id);
        $parameterCombination->load('parametro', 'lcp', 'unidad', 'metodo');
        return Inertia::render('parameters_combinations/Edit', [
            'parameterCombination' => $parameterCombination,
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

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
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
        $parameter = Parameter::where('parametro', $validatedData['parametro'])->firstOrFail();
        $parameter->load('lcps');
        $lcpsArr = $parameter->lcps()->pluck('valor')->toArray();
        if (!in_array($validatedData['lcp'], $lcpsArr)) {
            throw ValidationException::withMessages(['lcp' => 'El lcp ingresado no pertenece a ese parametro']);
        }
        $oldParameterCombination = ParameterCombination::findOrFail($id);
        $oldParameterCombination->obsoleto = 1;
        $oldParameterCombination->update();
        $lcp = LCP::where('id_parametro', $parameter->id)
            ->where('valor', $validatedData['lcp'])
            ->firstOrFail();
        $method = Method::where('nombre', $validatedData['metodo'])
            ->firstOrFail();
        $unit = Unit::where('nombre', $validatedData['unidad'])
            ->firstOrFail();
        ParameterCombination::create([
            'id_parametro' => $parameter->id,
            'id_lcp' => $lcp->id,
            'id_unidad' => $unit->id,
            'id_metodo' => $method->id_metodo,
            'clasificacion' => $validatedData['clasificacion'],
            'alias' => $validatedData['alias'],
        ]);

        return redirect()
            ->route('parameters-combinations.index')
            ->with('message', 'Se ha editado una combinacion del parametro ' . $parameter->parametro . ' correctamente');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ParameterCombination $parameters_combination)
    {
        $parameters_combination->delete();
        return redirect()
            ->route('parameters-combinations.index')
            ->with('message', 'Se ha eliminado una combinacion de parametro correctamente');
    }
}
