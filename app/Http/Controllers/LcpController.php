<?php

namespace App\Http\Controllers;

use App\Models\Lcp;
use App\Models\Parameter;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;

class LcpController extends Controller
{
    //
    public function index (Request $request)
    {
        $filters = $request->only('byLcp');
        $lcps = Lcp::orderByDesc('id')
            ->paginate(10)
            ->withQueryString();
        return Inertia::render('lcps/Index', [
            'filters' => $filters,
            'lcps' => $lcps,
        ]); 
    }

    public function create ()
    {
        return Inertia::render('lcps/Create');
    }

    public function store (Request $request, Parameter $parameter)
    {
        $validated = $request->validate([
            'valor' => 'required',
            'id_parametro' => 'required'
        ]);
        $parameter->lcps()->save(new Lcp($validated));
        return redirect()
            ->route('parameters.show', $parameter)
            ->with('message', 'Se ha agregado un lpc al parametro ' . $parameter->parametro);
    }

    public function show (Lcp $lcp)
    {
        return Inertia::render('lcps/Show', [
            'lcp' => $lcp,
            'backUrl' =>  url()->previous()
        ]);
    }

    public function edit (Lcp $lcp)
    {
        return Inertia::render('lcps/Edit', [
            'lcp' => $lcp,
            'backUrl' =>  url()->previous()
        ]);
    }

    public function update (Request $request, Lcp $lcp)
    {
        $validatedLcp = $request->validate([
            'edit_valor' => 'required',
            'edit_id_parametro' => 'required'
        ]);
        if ($validatedLcp['edit_valor'] === $lcp->valor) {
            throw ValidationException::withMessages(['edit_valor' => 'Se ingreso el mismo valor']);
        }
        $parameter = Parameter::findOrFail($lcp->id_parametro);
        $newLcp = new Lcp([
            'valor' => $validatedLcp['edit_valor'],
            'id_parametro' => $validatedLcp['edit_id_parametro'],
        ]);
        $lcp->obsoleto = 1;
        $lcp->save();
        $parameter->lcps()->save($newLcp);
        return redirect()
            ->route('parameters.show',$parameter)
            ->with('message', 'El Lcp ha sido actualizado correctamente');
    }

    public function destroy (Lcp $lcp)
    {
        
    }
}
