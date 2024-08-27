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
            'rules' => $rules
        ]);
    }

    public function create ()
    {
        return Inertia::render('rules/Create');
    }

    public function store (Request $request)
    {

    }

    public function show (Rule $rule)
    {
        return Inertia::render('rules/Show', [
            'rule' => $rule
        ]);
    }

    public function edit (Rule $rule)
    {
        return Inertia::render('rules/Edit', [
            'rule' => $rule
        ]);
    }

    public function update (Request $request, Rule $rule)
    {

    }

    public function destroy (Rule $rule)
    {
        
    }
}
