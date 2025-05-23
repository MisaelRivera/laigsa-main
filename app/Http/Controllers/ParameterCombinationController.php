<?php

namespace App\Http\Controllers;

use App\Http\Resources\ListMethodResource;
use App\Http\Resources\ListParameterResource;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Unit;
use App\Models\Method;
use App\Models\Parameter;
use App\Models\Rule;
use App\Models\LCP;
use App\Models\ParameterCombination;
use App\Models\ParameterCombinationAnalist;
use App\Models\ParameterCombinationSupervisor;
use App\Models\RuleParameterCombinationWater;
use App\Models\User;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\DB;

class ParameterCombinationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $filters = $request->only('alias');
        $parametersCombinations = ParameterCombination::with(['parametro', 'lcp'])
            ->where('obsoleto', 0)
            ->orderByDesc('id')
            ->when(
            $filters['alias'] ?? false, 
            fn ($query, $filter) => $query->where('alias', 'like', '%' . urldecode($filter) . '%')
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
                    'value' => $item->id,
                    'key' => $item->id,
                    'lcps' => $item->lcps
                ];
            }),
            'methods' => Method::where('obsoleto', 0)->get()->map(function ($item) {
                return [
                    'label' => $item->nombre,
                    'value' => $item->id_metodo,
                    'key' => $item->id_metodo
                ];
            }),
            'units' => Unit::where('obsoleto', 0)->get()->map(function ($item) {
                return [
                    'label' => $item->nombre,
                    'value' => $item->id,
                    'key' => $item->id
                ];
            }),
        ]);
    }

    public function getLCPs (Parameter $parameter)
    {
        return response()->json(LCP::where('id_parametro', $parameter->id)
            ->get()
            ->map(function ($item) {
                return [
                    'value' => $item->id,
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
            'id_parametro' => 'required|exists:parametros,id',
            'id_unidad' => 'required|exists:unidades,id',
            'id_metodo' => 'required|exists:metodos,id_metodo',
            'id_lcp' => 'required|exists:lcps,id',
            'clasificacion' => 'required',
            'alias' => 'required',
            'abreviacion' => 'required',
            'arrange' => 'required',
        ], [
            'id_parametro.required' => 'Ingrese el parametro',
            'id_parametro.exists' => 'El parametro ingresado no existe',
            'id_unidad.required' => 'Ingrese la unidad',
            'id_unidad.exists' => 'La unidad ingresada no existe',
            'id_metodo.required' => 'Ingrese el metodo',
            'id_metodo.exists' => 'El metodo ingresado no existe',
            'id_lcp.required' => 'Elija el lcp',
            'id_lcp.exists' => 'El lcp ingresado no existe',
            'clasificacion.required' => 'Elija la clasificacion',
            'alias.required' => 'Ingrese el alias',
            'abreviacion.required' => 'Ingrese la abreviacion',
            'arrange.required' => 'Ingrese el orden',
        ]);
        $validatedData['compuesto'] = $request->input('compuesto');
        $validatedData['subcontratado'] = $request->input('subcontratado');
        $validatedData['ema'] = $request->input('ema');
        $validatedData['cna'] = $request->input('cna');
        $validatedData['ssa'] = $request->input('ssa');
        $validatedData['supervisar'] = $request->input('supervisar');
        $validatedData['fecha_resultado_final'] = $request->input('fecha_resultado_final');
        $validatedData['parametro_campo'] = $request->input('parametro_campo');
        $validatedData['incertidumbre'] = $request->input('incertidumbre');
        $parameter = Parameter::where('id', $validatedData['id_parametro'])->firstOrFail();
        $parameter->load('lcps');
        $lcpsArr = $parameter->lcps()->pluck('id')->toArray();
        if (!in_array($validatedData['id_lcp'], $lcpsArr)) {
            throw ValidationException::withMessages(['lcp' => 'El lcp ingresado no pertenece a ese parametro']);
        }
        ParameterCombination::create($validatedData);

        return redirect()
            ->route('parameters-combinations.index')
            ->with('message', 'Se ha creado una combinacion del parametro ' . $parameter->parametro . ' correctamente');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $parameterCombination = ParameterCombination::with(['parametro', 'metodo', 'unidad', 'lcp', 'analistas', 'supervisores'])->findOrFail($id);
        return Inertia::render('parameters_combinations/Show', [
            'parameterCombination' => $parameterCombination,
            'analists' => User::role(['analist', 'sampler'])
                ->get()
                ->map(function ($analist) {
                    return ['value' => $analist->id, 'label'=> $analist->name];
                }),
        ]);
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

    public function removeParamCombination (RuleParameterCombinationWater $ruleParametersCombination)
    {
        $rule = Rule::findOrFail($ruleParametersCombination->id_norma);
        $ruleParametersCombination
            ->delete();
        return redirect()
            ->route('rules.show', $rule->id)
            ->with('message', 'Se ha removido la combinacion  del parametro correctamente!');
    }

    public function removeParamCombinationAnalyst (User $analyst, ParameterCombination $parameterCombination)
    {
        $parameterCombinationAnalyst = ParameterCombinationAnalist::where('id_usuario', $analyst->id)
            ->where('id_combinacion_parametro', $parameterCombination->id)
            ->get();
        $parameterCombinationId = $parameterCombinationAnalyst[0]->id_combinacion_parametro;
        $parameterCombinationAnalyst[0]->delete();
        return redirect()
            ->route('parameters-combinations.show', ['parameters_combination' => $parameterCombinationId]);
    }

    public function removeParamCombinationSupervisor (User $supervisor, ParameterCombination $parameterCombination)
    {
        $parameterCombinationSupervisor = ParameterCombinationSupervisor::where('id_usuario', $supervisor->id)
            ->where('id_combinacion_parametro', $parameterCombination->id)
            ->get();
        $parameterCombinationId = $parameterCombinationSupervisor[0]->id_combinacion_parametro;
        $parameterCombinationSupervisor[0]->delete();
        return redirect()
            ->route('parameters-combinations.show', ['parameters_combination' => $parameterCombinationId]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $parameterCombination = ParameterCombination::findOrFail($id);
        $parameterCombination->load('parametro.lcps', 'lcp', 'unidad', 'metodo');
        $parameterCombination->parametro->formattedLcps = $parameterCombination->parametro->lcps->map(function ($lcp) {
            return [
                'value' => $lcp->id,
                'label' => $lcp->valor
            ];
        });
        return Inertia::render('parameters_combinations/Edit', [
            'parameterCombination' => $parameterCombination,
            'parameters' => ListParameterResource::collection(Parameter::all()),
            'methods' => ListMethodResource::collection(Method::where('obsoleto', 0)->get()),
            'units' => Unit::where('obsoleto', 0)->get()->map(function ($item) {
                return [
                    'label' => $item->nombre,
                    'value' => $item->id,
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
            'id_parametro' => 'required|exists:parametros,id',
            'id_unidad' => 'required|exists:unidades,id',
            'id_metodo' => 'required|exists:metodos,id_metodo',
            'id_lcp' => 'required|exists:lcps,id',
            'clasificacion' => 'required',
            'alias' => 'required',
            'abreviacion' => 'required',
            'arrange' => 'required',
        ], [
            'id_parametro.required' => 'Ingrese el parametro',
            'id_parametro.exists' => 'El parametro ingresado no existe',
            'id_unidad.required' => 'Ingrese la unidad',
            'id_unidad.exists' => 'La unidad ingresada no existe',
            'id_metodo.required' => 'Ingrese el metodo',
            'id_metodo.exists' => 'El metodo ingresado no existe',
            'id_lcp.required' => 'Elija el lcp',
            'id_lcp.exists' => 'El lcp ingresado no existe',
            'clasificacion.required' => 'Elija la clasificacion',
            'alias.required' => 'Ingrese el alias',
            'abreviacion.required' => 'Ingrese la abreviacion',
            'arrange.required' => 'Ingrese el orden',
        ]);
        $validatedData['compuesto'] = $request->input('compuesto');
        $validatedData['subcontratado'] = $request->input('subcontratado');
        $validatedData['ema'] = $request->input('ema');
        $validatedData['cna'] = $request->input('cna');
        $validatedData['ssa'] = $request->input('ssa');
        $validatedData['supervisar'] = $request->input('supervisar');
        $validatedData['fecha_resultado_final'] = $request->input('fecha_resultado_final');
        $validatedData['parametro_campo'] = $request->input('parametro_campo');
        $validatedData['incertidumbre'] = $request->input('incertidumbre');
        $parameter = Parameter::where('id', $validatedData['id_parametro'])->firstOrFail();
        $parameter->load('lcps');
        $lcpsArr = $parameter->lcps()->pluck('id')->toArray();
        if (!in_array($validatedData['id_lcp'], $lcpsArr)) {
            throw ValidationException::withMessages(['lcp' => 'El lcp ingresado no pertenece a ese parametro']);
        }
        $oldParameterCombination = ParameterCombination::findOrFail($id);
        $oldParameterCombination->obsoleto = 1;
        $oldParameterCombination->update();
        ParameterCombination::create($validatedData);

        return redirect()
            ->route('parameters-combinations.index')
            ->with('message', 'Se ha editado una combinacion del parametro ' . $parameter->parametro . ' correctamente');
    }

    public function addAnalyst (User $user, ParameterCombination $parameterCombination)
    {
        ParameterCombinationAnalist::create([
            'id_usuario' => $user->id,
            'id_combinacion_parametro' => $parameterCombination->id
        ]);
        return redirect()
            ->route('parameters-combinations.show', ['parameters_combination' => $parameterCombination->id])
            ->with('message', "Se ha agregado el analista correctamente");
    }

    public function addSupervisor (User $user, ParameterCombination $parameterCombination)
    {
        ParameterCombinationSupervisor::create([
            'id_usuario' => $user->id,
            'id_combinacion_parametro' => $parameterCombination->id
        ]);
        return redirect()
            ->route('parameters-combinations.show', ['parameters_combination' => $parameterCombination->id])
            ->with('message', "Se ha agregado el supervisor correctamente");
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
