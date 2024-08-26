<?php

namespace App\Http\Controllers;

use App\Http\Requests\ParameterStoreRequest;
use Illuminate\Http\Request;
use App\Models\Parameter;
use App\Models\LCP;
use App\Models\Method;
use App\Models\Unit;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Inertia\Inertia;

class ParametersController extends Controller
{
    //
    public function index (Request $request)
    {
        $filters = $request->only('byParameter');
        $parameters = Parameter::orderByDesc('id')
        ->when(
            $filters['byParameter'] ?? false, 
            fn ($query, $filter) => $query->where('parametro', 'like', '%' . $filter . '%')
        )->paginate(10)
        ->withQueryString();

        return Inertia::render('parameters/Index', [
            'parametersProp' => $parameters,
            'filters' => $filters
        ]);
    }

    public function filter ()
    {
        $parameters = Parameter::when($request->query('filter'), function (Builder $query, $filter) {
                $query->where('parametro', 'like', '%' . $filter . '%');
            })
            ->paginate(10);
        return response()->json($parameters);           
    }

    public function create ()
    {
        return Inertia::render('parameters/Create');
    }

    public function store (ParameterStoreRequest $request)
    {
        $parametro = Parameter::create($request->validated());
        $request->session()->flash('message', 'Se ha creado el parametro ' . $parametro->parametro . ' correctamente.');
        return redirect()->route('parameters.index');
    }

    public function show (Parameter $parameter)
    {
        $backUrl = url()->previous();
        $lcps = LCP::where('id_parametro', $parameter->id)
            ->where('obsoleto', 0)
            ->get();
        
        return Inertia::render('parameters/Show', [
            'parameter' => $parameter,
            'backUrl' => $backUrl,
            'lcps' => $lcps,
        ]);
    }

    public function parameterCombination (Parameter $parameter)
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

    public function parameterCombinationStore (Request $request, Parameter $parameter)
    {
        $validated = $request->validate([
            'unidad' => 'required',
            'metodo' => 'required',
            'lcp' => 'required',
        ], 
        [
           'unidad.required' => 'Ingrese la unidad', 
           'metodo.required' => 'Ingrese el metodo', 
           'lcp.required' => 'Ingrese el lcp', 
        ]);

        $idUnidad = Unit::select(['id'])->where('id_parametro', $parameter->id)
            ->where('unidad', $validated['unidad'])->first();
        dd($idUnidad);
    }

    public function edit (Parameter $parameter)
    {
        return Inertia::render('parameters/Edit', ['parameter' => $parameter]);
    }

    public function update (ParameterStoreRequest $request, $id)
    {
        $parameter = Parameter::findOrFail($id);
        $parameter->update($request->validated());
        $request->session()->flash('message', "Se ha editado el parametro $parameter->parametro correctamente");
        return redirect()->route('parameters.index');
    }

    public function destroy (Parameter $parameter)
    {
        $name = $parameter->parametro;
        $parameter->delete();
        return redirect()
            ->route('parameters.index')
            ->with('message', "Se ha eliminado el parametro $name correctamente");
    }

}
