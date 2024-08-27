<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Unit;
use App\Models\Method;
use App\Models\Parameter;
use App\Models\ParameterCombination;

class ParameterCombinationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $parameterCombinations = ParameterCombination::with(['parametro', 'unidad', 'metodo', 'lcp'])->where('obsoleto', 0)->get();
        return Inertia::render('parameterCombination/Index', [
            'parameterCombinations' => $parameterCombinations
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Parameter $parameter)
    {
        return Inertia::render('parameters/ParameterCombination', [
            'parameter' => $parameter,
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
            'lcps' => LCP::where('obsoleto', 0)->where('id_parametro', $parameter->id)->get()->map(function ($item) {
                return [
                    'label' => $item->valor,
                    'value' => $item->valor,
                    'key' => $item->id
                ];
            })
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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
