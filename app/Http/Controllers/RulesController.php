<?php

namespace App\Http\Controllers;

use App\Models\Rule;
use Illuminate\Http\Request;
use Inertia\Inertia;

class RulesController extends Controller
{
    public function index ()
    {
        $rules = Rule::where('obsoleto', 0)->paginate(5);
        return Inertia::render('rules/Index', [
            'rules' => $rules,
            'filters' => ['byRule' => '']
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

    public function show (Rule $rule)
    {
        return Inertia::render('rules/Show', [
            'rule' => $rule
        ]);
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
