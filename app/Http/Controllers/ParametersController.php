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
        $parameters = Parameter::query()
            ->when($request->query('filter'), function (Builder $query, $filter) {
            $query->where('parametro', 'like', '%' . $filter . '%');
        })->paginate(10);
        return Inertia::render('parameters/Index', [
            'parametersProp' => $parameters,
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

}
