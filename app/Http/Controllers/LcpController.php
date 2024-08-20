<?php

namespace App\Http\Controllers;

use App\Models\Lcp;
use Illuminate\Http\Request;
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

    public function store (Request $request)
    {

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

    }

    public function destroy (Lcp $lcp)
    {
        
    }
}
