<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class VueFormController extends Controller
{
    public function test1 ()
    {
        return Inertia::render('test_vue_form/Test1');
    }

    public function test1Store(Request $request) {
        dd($request->all());
    }
}
