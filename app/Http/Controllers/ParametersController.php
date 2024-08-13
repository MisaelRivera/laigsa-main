<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Parameter;
use Inertia\Inertia;

class ParametersController extends Controller
{
    //
    public function index ()
    {
        $parameters = Parameter::paginate(40);
        return Inertia::render('parameters/Index', [
            'parametersProp' => $parameters
        ]);
    }

    public function create ()
    {

    }

    public function store ()
    {

    }

    public function edit ()
    {

    }

    public function update ()
    {

    }

}
