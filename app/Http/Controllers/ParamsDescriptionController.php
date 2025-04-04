<?php

namespace App\Http\Controllers;

use App\Models\ParamDescription;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ParamsDescriptionController extends Controller
{
    public function index ()
    {
        return Inertia::render('params_description/Index', [
            'paramsDescription' => ParamDescription::paginate(10)
        ]);
    }

    public function create ()
    {
        return Inertia::render('params_description/Create');
    }

    public function store (Request $request)
    {

    }

    public function edit (ParamDescription $paramDescription)
    {

    }

    public function update (ParamDescription $paramDescription)
    {

    }

    public function destroy (ParamDescription $paramDescription)
    {

    }
}
