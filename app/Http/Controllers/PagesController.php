<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthUserRequest;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Order;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;

class PagesController extends Controller
{
    //
    public function index ()
    {
        return Inertia::render('Index', [
            'title' => 'Hi, is working',
            'backgroundUrl' => asset('img/background.jpg'),
        ]);
    }

    public function us ()
    {
        return Inertia::render('Nosotros', ['title' => 'This is Us']);
    }

    public function contact ()
    {
        return Inertia::render('Contacto', ['title' => 'Contacto']);
    }

    public function testingEloquent ()
    {
        $orders = Order::with(['cliente'])
        ->select('ordenes.*', DB::raw("IF(ordenes.aguas_alimentos='Aguas', 'bg-blue-300', 'bg-yellow-300') as color_aguas"))
        ->orderBy('fecha_recepcion', 'DESC')
        ->orderBy('hora_recepcion', 'DESC')
        ->orderBy('folio', 'DESC')
        ->limit(20)
        ->get();
       return Inertia::render('TestingEloquent', [
            'orders' => $orders
        ]);
    }

    public function filters (Request $request)
    {
        $orders = Order::join('clientes_muestreo', 'ordenes.id_cliente', '=', 'clientes_muestreo.id')
        ->when($request->query('cliente'), function ($query) use ($request) {
            // Apply condition to the orders based on the related cliente
                $query->where('clientes_muestreo.cliente', 'like', "%" . $request->query('cliente') . "%");
        })
            ->when($request->query('folio') !== null, function (Builder $query) use($request) {
                $query->where('folio', 'like', "%" . $request->query('folio') . "%");
            })
            ->select('ordenes.*', 'clientes_muestreo.cliente')
            ->limit(40)
            ->get();
        return response()->json($orders);
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
    
        $request->session()->invalidate();
    
        $request->session()->regenerateToken();
    
        return redirect('/');
    }
}
