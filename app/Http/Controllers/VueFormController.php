<?php

namespace App\Http\Controllers;

use App\Models\Parameter;
use Illuminate\Http\Request;
use Inertia\Inertia;

class VueFormController extends Controller
{
    public function test1 (Request $request)
    {
        $samplesNumber = (int)$request->query('samples_number');
        return Inertia::render('test_vue_form/Test1', [
            'samplesNumber' => $samplesNumber
        ]);
    }

    public function test2 ()
    {
        return Inertia::render('test_vue_form/Test2', [
            'parameters' => Parameter::paginate(10)
        ]);
    }

    public function test2Store(Request $request) {
        $request->validate([
            'name' => 'required'
        ]);
    }
}
