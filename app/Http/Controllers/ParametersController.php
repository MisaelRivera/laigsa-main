<?php

namespace App\Http\Controllers;

use App\Http\Requests\ParameterStoreRequest;
use Illuminate\Http\Request;
use App\Models\Parameter;
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

    public function show (Request $request, Parameter $parameter)
    {
        $backUrl = url()->previous();
        return Inertia::render('parameters/Show', [
            'parameter' => $parameter,
            'backUrl' => $backUrl
        ]);
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
        return redirect()->route('parameters.index')->with('message', "Se ha eliminado el parametro $name correctamente");
    }

}
