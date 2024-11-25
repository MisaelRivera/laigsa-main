<?php

namespace App\Http\Controllers;

use App\Models\ParameterCombination;
use App\Models\Rule;
use App\Models\RuleParameterCombinationWater;
use App\Api\RulesApi;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;

class RulesController extends Controller
{
    public function index (Request $request)
    {
        $filters = $request->only('byRule');
        $rules = Rule::where('obsoleto', 0)
            ->orderBy('id')
            ->when(
                $filters['byRule'] ?? false,
                fn ($query, $filter) => $query->where('norma', 'like', '%' . urldecode($filter) . '%')
            )->paginate(5)
            ->withQueryString();
        if (isset($filters['byRule'])) {
            $filters['byRule'] = urldecode($filters['byRule']);
        }
        return Inertia::render('rules/Index', [
            'rules' => $rules,
            'filters' => $filters
        ]);
    }

    public function create (Request $request)
    {
        $data = [];
        if ($request->has('page')) {
            $data['page'] = $request->query('page');
        }
        return Inertia::render('rules/Create', $data);
    }

    public function store (Request $request)
    {
        $validatedData = $request->validate([
            'norma' => 'required|unique:normas,norma',
            'tipo' => 'required',
            'descripcion' => 'required',
            'cesavedac' => 'required',
            'aguas' => 'required',
        ], [
            'norma.required' => 'Ingrese la norma',
            'norma.unique' => 'Esta norma ya existe',
            'tipo.required' => 'Ingrese el tipo',
            'descripcion.required' => 'Ingrese la descripcion',
        ]);

        $ruleName =  Rule::create($validatedData);
        return redirect()
            ->route('rules.index')
            ->with('message', 'Se ha creado la norma ' .  $ruleName->norma . ' correctamente');
    }

    public function show (Request $request, Rule $rule)
    {
        $data = RulesApi::show($request, $rule);
        return Inertia::render('rules/Show', $data);
    }

    public function edit (Request $request, Rule $rule)
    {
        $data = [
            'rule' => $rule,
        ];
        if ($request->has('page')) {
            $data['page'] = $request->query('page');
        }
        return Inertia::render('rules/Edit', $data);
    }

    public function update (Request $request, Rule $rule)
    {
        $validatedData = $request->validate([
            'norma' => 'required',
            'tipo' => 'required',
            'descripcion' => 'required',
            'cesavedac' => 'required',
            'aguas' => 'required',
        ], [
            'norma.required' => 'Ingrese la norma',
            'tipo.required' => 'Ingrese el tipo',
            'descripcion.required' => 'Ingrese la descripcion',
        ]);
        $rule->update($validatedData);
        return redirect()
            ->route('rules.index')
            ->with('message', "Se ha editado la norma $rule->norma");
    }

    public function destroy (Request $request, Rule $rule)
    {
        $ruleName =  $rule->norma;
        $rule->delete();
        return redirect()
            ->route('rules.index', ['page' => $request->query('page')])
            ->with('message', "Se ha eliminado la norma $ruleName correctamente!");
    }
}
